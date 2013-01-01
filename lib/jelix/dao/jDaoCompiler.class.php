<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package    jelix
* @subpackage dao
* @author     Gérald Croes, Laurent Jouanneau
* @contributor Laurent Jouanneau
* @copyright  2001-2005 CopixTeam, 2005-2012 Laurent Jouanneau
* Ideas and some parts of this file were get originally from the Copix project
* (CopixDAOGeneratorV1, CopixDAODefinitionV1, Copix 2.3dev20050901, http://www.copix.org)
* Few lines of code are still copyrighted 2001-2005 CopixTeam (LGPL licence).
* Initial authors of this lines of code are Gerald Croes and Laurent Jouanneau,
* and classes were adapted/improved for Jelix by Laurent Jouanneau
*
* @link        http://www.jelix.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/
class jDaoXmlException extends jException{
	public function __construct($selector,$localekey,$localeParams=array()){
		$localekey='jelix~daoxml.'.$localekey;
		$arg=array($selector->toString(),$selector->getPath());
		if(is_array($localeParams)){
			$arg=array_merge($arg,$localeParams);
		}else{
			$arg[]=$localeParams;
		}
		parent::__construct($localekey,$arg);
	}
}
class jDaoParser{
	private $_properties=array();
	private $_tables=array();
	private $_primaryTable='';
	private $_ojoins=array();
	private $_ijoins=array();
	private $_methods=array();
	private $_eventList=array();
	public $hasOnlyPrimaryKeys=false;
	public $selector;
	function __construct($selector){
		$this->selector=$selector;
	}
	public function parse($xml,$tools){
		$this->parseDatasource($xml);
		$this->parseRecord($xml,$tools);
		$this->parseFactory($xml);
	}
	protected function parseDatasource($xml){
		if(isset($xml->datasources)&&isset($xml->datasources[0]->primarytable)){
			$t=$this->_parseTable(0,$xml->datasources[0]->primarytable[0]);
			$this->_primaryTable=$t['name'];
			if(isset($xml->datasources[0]->primarytable[1])){
				throw new jDaoXmlException($this->selector,'table.two.many');
			}
			foreach($xml->datasources[0]->foreigntable as $table){
				$this->_parseTable(1,$table);
			}
			foreach($xml->datasources[0]->optionalforeigntable as $table){
				$this->_parseTable(2,$table);
			}
		}else{
			throw new jDaoXmlException($this->selector,'datasource.missing');
		}
	}
	protected function parseRecord($xml,$tools){
		$countprop=0;
		if(isset($xml->record)&&isset($xml->record[0]->property)){
			foreach($xml->record[0]->property as $prop){
				$p=new jDaoProperty($prop->attributes(),$this,$tools);
				$this->_properties[$p->name]=$p;
				$this->_tables[$p->table]['fields'][]=$p->name;
				if($p->ofPrimaryTable&&!$p->isPK)
					$countprop ++;
			}
			$this->hasOnlyPrimaryKeys=($countprop==0);
		}else
			throw new jDaoXmlException($this->selector,'properties.missing');
	}
	protected function parseFactory($xml){
		if(isset($xml->factory)){
			if(isset($xml->factory[0]['events'])){
				$events=(string)$xml->factory[0]['events'];
				$this->_eventList=preg_split("/[\s,]+/",$events);
			}
			if(isset($xml->factory[0]->method)){
				foreach($xml->factory[0]->method as $method){
					$m=new jDaoMethod($method,$this);
					if(isset($this->_methods[$m->name])){
						throw new jDaoXmlException($this->selector,'method.duplicate',$m->name);
					}
					$this->_methods[$m->name]=$m;
				}
			}
		}
	}
	private function _parseTable($typetable,$tabletag){
		$infos=$this->getAttr($tabletag,array('name','realname','primarykey','onforeignkey'));
		if($infos['name']===null)
			throw new jDaoXmlException($this->selector,'table.name');
		if($infos['realname']===null)
			$infos['realname']=$infos['name'];
		if($infos['primarykey']===null)
			throw new jDaoXmlException($this->selector,'primarykey.missing');
		$infos['pk']=preg_split("/[\s,]+/",$infos['primarykey']);
		unset($infos['primarykey']);
		if(count($infos['pk'])==0||$infos['pk'][0]=='')
			throw new jDaoXmlException($this->selector,'primarykey.missing');
		if($typetable){
			if($infos['onforeignkey']===null)
				throw new jDaoXmlException($this->selector,'foreignkey.missing');
			$infos['fk']=preg_split("/[\s,]+/",$infos['onforeignkey']);
			unset($infos['onforeignkey']);
			if(count($infos['fk'])==0||$infos['fk'][0]=='')
				throw new jDaoXmlException($this->selector,'foreignkey.missing');
			if(count($infos['fk'])!=count($infos['pk']))
				throw new jDaoXmlException($this->selector,'foreignkey.missing');
			if($typetable==1){
				$this->_ijoins[]=$infos['name'];
			}else{
				$this->_ojoins[]=array($infos['name'],0);
			}
		}else{
			unset($infos['onforeignkey']);
		}
		$infos['fields']=array();
		$this->_tables[$infos['name']]=$infos;
		return $infos;
	}
	public function getAttr($tag,$requiredattr){
		$res=array();
		foreach($requiredattr as $attr){
			if(isset($tag[$attr])&&trim((string)$tag[$attr])!='')
				$res[$attr]=(string)$tag[$attr];
			else
				$res[$attr]=null;
		}
		return $res;
	}
	public function getBool($value){
		return in_array(trim($value),array('true','1','yes'));
	}
	public function getProperties(){return $this->_properties;}
	public function getTables(){return $this->_tables;}
	public function getPrimaryTable(){return $this->_primaryTable;}
	public function getMethods(){return $this->_methods;}
	public function getOuterJoins(){return $this->_ojoins;}
	public function getInnerJoins(){return $this->_ijoins;}
	public function hasEvent($event){return in_array($event,$this->_eventList);}
}
class jDaoProperty{
	public $name='';
	public $fieldName='';
	public $regExp=null;
	public $required=false;
	public $requiredInConditions=false;
	public $isPK=false;
	public $isFK=false;
	public $datatype;
	public $unifiedType;
	public $table=null;
	public $updatePattern='%s';
	public $insertPattern='%s';
	public $selectPattern='%s';
	public $sequenceName='';
	public $maxlength=null;
	public $minlength=null;
	public $ofPrimaryTable=true;
	public $defaultValue=null;
	public $autoIncrement=false;
	function __construct($aAttributes,$parser,$tools){
		$needed=array('name','fieldname','table','datatype','required',
						'minlength','maxlength','regexp','sequence','default','autoincrement');
		$allowed=array('name','fieldname','table','datatype','required',
						'minlength','maxlength','regexp','sequence','default','autoincrement',
						'updatepattern','insertpattern','selectpattern');
		foreach($aAttributes as $attributeName=>$attributeValue){
			if(!in_array($attributeName,$allowed)){
				throw new jDaoXmlException($parser->selector,'unknown.attr',array($attributeName,'property'));
			}
		}
		$params=$parser->getAttr($aAttributes,$needed);
		if($params['name']===null){
			throw new jDaoXmlException($parser->selector,'missing.attr',array('name','property'));
		}
		$this->name=$params['name'];
		if(!preg_match('/^[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*$/',$this->name)){
			throw new jDaoXmlException($parser->selector,'property.invalid.name',$this->name);
		}
		$this->fieldName=$params['fieldname']!==null ? $params['fieldname'] : $this->name;
		$this->table=$params['table']!==null ? $params['table'] : $parser->getPrimaryTable();
		$tables=$parser->getTables();
		if(!isset($tables[$this->table])){
			throw new jDaoXmlException($parser->selector,'property.unknown.table',$this->name);
		}
		$this->required=$this->requiredInConditions=$parser->getBool($params['required']);
		$this->maxlength=$params['maxlength']!==null ? intval($params['maxlength']): null;
		$this->minlength=$params['minlength']!==null ? intval($params['minlength']): null;
		$this->regExp=$params['regexp'];
		$this->autoIncrement=$parser->getBool($params['autoincrement']);
		if($params['datatype']===null){
			throw new jDaoXmlException($parser->selector,'missing.attr',array('datatype','property'));
		}
		$params['datatype']=trim(strtolower($params['datatype']));
		if($params['datatype']==''){
			throw new jDaoXmlException($parser->selector,'wrong.attr',array($params['datatype'],
															$this->fieldName,
															'property'));
		}
		$this->datatype=strtolower($params['datatype']);
		$ti=$tools->getTypeInfo($this->datatype);
		$this->unifiedType=$ti[1];
		if(!$this->autoIncrement)
			$this->autoIncrement=$ti[6];
		if($this->unifiedType=='integer'||$this->unifiedType=='numeric'){
			if($params['sequence']!==null){
				$this->sequenceName=$params['sequence'];
				$this->autoIncrement=true;
			}
		}
		$this->isPK=in_array($this->fieldName,$tables[$this->table]['pk']);
		if(!$this->isPK&&$this->table==$parser->getPrimaryTable()){
			foreach($tables as $table=>$info){
				if($table==$this->table)
					continue;
				if(isset($info['fk'])&&in_array($this->fieldName,$info['fk'])){
					$this->isFK=true;
					break;
				}
			}
		}
		else{
			$this->required=true;
			$this->requiredInConditions=true;
		}
		if($this->autoIncrement){
			$this->required=false;
			$this->requiredInConditions=true;
		}
		if($params['default']!==null){
			$this->defaultValue=$tools->stringToPhpValue($this->unifiedType,$params['default']);
		}
		if($this->isPK&&!$this->autoIncrement&&isset($aAttributes['insertpattern'])){
			$this->insertPattern=(string)$aAttributes['insertpattern'];
		}
		if($this->isPK){
			$this->updatePattern='';
		}
		if(!$this->isPK&&!$this->isFK){
			if(isset($aAttributes['updatepattern'])){
				$this->updatePattern=(string)$aAttributes['updatepattern'];
			}
			if(isset($aAttributes['insertpattern'])){
				$this->insertPattern=(string)$aAttributes['insertpattern'];
			}
			if(isset($aAttributes['selectpattern'])){
				$this->selectPattern=(string)$aAttributes['selectpattern'];
			}
		}
		if($this->table!=$parser->getPrimaryTable()){
			$this->updatePattern='';
			$this->insertPattern='';
			$this->required=false;
			$this->requiredInConditions=false;
			$this->ofPrimaryTable=false;
		}
		else{
			$this->ofPrimaryTable=true;
		}
	}
}
class jDaoMethod{
	public $name;
	public $type;
	public $distinct=false;
	public $eventBeforeEnabled=false;
	public $eventAfterEnabled=false;
	private $_conditions=null;
	private $_parameters=array();
	private $_parametersDefaultValues=array();
	private $_limit=null;
	private $_values=array();
	private $_parser=null;
	private $_procstock=null;
	private $_body=null;
	private $_groupBy=null;
	function __construct($method,$parser){
		$this->_parser=$parser;
		$params=$parser->getAttr($method,array('name','type','call','distinct',
												'eventbefore','eventafter','groupby'));
		if($params['name']===null){
			throw new jDaoXmlException($this->_parser->selector,'missing.attr',array('name','method'));
		}
		$this->name=$params['name'];
		$this->type=$params['type'] ? strtolower($params['type']): 'select';
		if(isset($method->parameter)){
			foreach($method->parameter as $param){
				$attr=$param->attributes();
				if(!isset($attr['name'])){
					throw new jDaoXmlException($this->_parser->selector,'method.parameter.unknowname',array($this->name));
				}
				if(!preg_match('/[a-zA-Z_][a-zA-Z0-9_]*/',(string)$attr['name'])){
					throw new jDaoXmlException($this->_parser->selector,'method.parameter.invalidname',array($method->name,$attr['name']));
				}
				$this->_parameters[]=(string)$attr['name'];
				if(isset($attr['default'])){
					$this->_parametersDefaultValues[(string)$attr['name']]=(string)$attr['default'];
				}
			}
		}
		if($this->type=='sql'){
			if($params['call']===null){
				throw new jDaoXmlException($this->_parser->selector,'method.procstock.name.missing');
			}
			$this->_procstock=$params['call'];
			return;
		}
		if($this->type=='php'){
			if(isset($method->body)){
				$this->_body=(string)$method->body;
			}else{
				throw new jDaoXmlException($this->_parser->selector,'method.body.missing');
			}
			return;
		}
		$this->_conditions=new jDaoConditions();
		if(isset($method->conditions)){
			$this->_parseConditions($method->conditions[0],false);
		}
		if($this->type=='update'||$this->type=='delete'){
			if($params['eventbefore']=='true')
				$this->eventBeforeEnabled=true;
			if($params['eventafter']=='true')
				$this->eventAfterEnabled=true;
		}
		if($this->type=='update'){
			if($this->_parser->hasOnlyPrimaryKeys)
				throw new jDaoXmlException($this->_parser->selector,'method.update.forbidden',array($this->name));
			if(isset($method->values)&&isset($method->values[0]->value)){
				foreach($method->values[0]->value as $val){
					$this->_addValue($val);
				}
			}else{
				throw new jDaoXmlException($this->_parser->selector,'method.values.undefine',array($this->name));
			}
			return;
		}
		if(strlen($params['distinct'])){
			if($this->type=='select'){
				$this->distinct=$this->_parser->getBool($params['distinct']);
			}elseif($this->type=='count'){
				$props=$this->_parser->getProperties();
				if(!isset($props[$params['distinct']])){
					throw new jDaoXmlException($this->_parser->selector,'method.property.unknown',array($this->name,$params['distinct']));
				}
				$this->distinct=$params['distinct'];
			}else{
				throw new jDaoXmlException($this->_parser->selector,'forbidden.attr.context',array('distinct','<method name="'.$this->name.'"'));
			}
		}
		if($this->type=='count')
			return;
		if(isset($method->order)&&isset($method->order[0]->orderitem)){
			foreach($method->order[0]->orderitem as $item){
				$this->_addOrder($item);
			}
		}
		if(strlen($params['groupby'])){
			if($this->type=='select'||$this->type=='selectfirst'){
				$this->_groupBy=preg_split("/[\s,]+/",$params['groupby']);
				$props=$this->_parser->getProperties();
				foreach($this->_groupBy as $p){
					if(!isset($props[$p])){
						throw new jDaoXmlException($this->_parser->selector,'method.property.unknown',array($this->name,$p));
					}
				}
			}else{
				throw new jDaoXmlException($this->_parser->selector,'forbidden.attr.context',array('groupby','<method name="'.$this->name.'"'));
			}
		}
		if(isset($method->limit)){
			if(isset($method->limit[1])){
				throw new jDaoXmlException($this->_parser->selector,'tag.duplicate',array('limit',$this->name));
			}
			if($this->type=='select'){
				$this->_addLimit($method->limit[0]);
			}else{
				throw new jDaoXmlException($this->_parser->selector,'method.limit.forbidden',$this->name);
			}
		}
	}
	public function getConditions(){return $this->_conditions;}
	public function getParameters(){return $this->_parameters;}
	public function getParametersDefaultValues(){return $this->_parametersDefaultValues;}
	public function getLimit(){return $this->_limit;}
	public function getValues(){return $this->_values;}
	public function getProcStock(){return $this->_procstock;}
	public function getBody(){return $this->_body;}
	public function getGroupBy(){return $this->_groupBy;}
	private function _parseConditions($conditions,$subcond=true){
		if(isset($conditions['logic'])){
			$kind=strtoupper((string)$conditions['logic']);
		}else{
			$kind='AND';
		}
		if($subcond){
			$this->_conditions->startGroup($kind);
		}else{
			$this->_conditions->condition->glueOp=$kind;
		}
		foreach($conditions->children()as $op=>$cond){
			if($op!='conditions')
				$this->_addCondition($op,$cond);
			else
				$this->_parseConditions($cond);
		}
		if($subcond){
			$this->_conditions->endGroup();
		}
	}
	private $_op=array('eq'=>'=','neq'=>'<>','lt'=>'<','gt'=>'>','lteq'=>'<=','gteq'=>'>=',
		'like'=>'LIKE','notlike'=>'NOT LIKE','isnull'=>'IS NULL','isnotnull'=>'IS NOT NULL','in'=>'IN','notin'=>'NOT IN',
		'binary_op'=>'dummy');
	private $_attrcond=array('property','expr','operator','driver');
	private function _addCondition($op,$cond){
		$attr=$this->_parser->getAttr($cond,$this->_attrcond);
		$field_id=($attr['property']!==null? $attr['property']:'');
		if(!isset($this->_op[$op])){
			throw new jDaoXmlException($this->_parser->selector,'method.condition.unknown',array($this->name,$op));
		}
		$operator=$this->_op[$op];
		$props=$this->_parser->getProperties();
		if(!isset($props[$field_id])){
			throw new jDaoXmlException($this->_parser->selector,'method.property.unknown',array($this->name,$field_id));
		}
		if($this->type=='update'){
			if($props[$field_id]->table!=$this->_parser->getPrimaryTable()){
				throw new jDaoXmlException($this->_parser->selector,'method.property.forbidden',array($this->name,$field_id));
			}
		}
		if(isset($cond['value']))
			$value=(string)$cond['value'];
		else
			$value=null;
		if($value!==null&&$attr['expr']!==null){
			throw new jDaoXmlException($this->_parser->selector,'method.condition.valueexpr.together',array($this->name,$op));
		}else if($value!==null){
			if($op=='isnull'||$op=='isnotnull'){
				throw new jDaoXmlException($this->_parser->selector,'method.condition.valueexpr.notallowed',array($this->name,$op,$field_id));
			}
			if($op=='binary_op'){
				if(!isset($attr['operator'])||empty($attr['operator'])){
					throw new jDaoXmlException($this->_parser->selector,'method.condition.operator.missing',array($this->name,$op,$field_id));
				}
				if(isset($attr['driver'])&&!empty($attr['driver'])){
					if($this->_parser->selector->driver!=$attr['driver']){
						throw new jDaoXmlException($this->_parser->selector,'method.condition.driver.notallowed',array($this->name,$op,$field_id));
					}
				}
				$operator=$attr['operator'];
			}
			$this->_conditions->addCondition($field_id,$operator,$value);
		}else if($attr['expr']!==null){
			if($op=='isnull'||$op=='isnotnull'){
				throw new jDaoXmlException($this->_parser->selector,'method.condition.valueexpr.notallowed',array($this->name,$op,$field_id));
			}
			if(($op=='in'||$op=='notin')&&!preg_match('/^\$[a-zA-Z0-9_]+$/',$attr['expr'])){
				throw new jDaoXmlException($this->_parser->selector,'method.condition.innotin.bad.expr',array($this->name,$op,$field_id));
			}
			if($op=='binary_op'){
				if(!isset($attr['operator'])||empty($attr['operator'])){
					throw new jDaoXmlException($this->_parser->selector,'method.condition.operator.missing',array($this->name,$op,$field_id));
				}
				if(isset($attr['driver'])&&!empty($attr['driver'])){
					if($this->_parser->selector->driver!=$attr['driver']){
						throw new jDaoXmlException($this->_parser->selector,'method.condition.driver.notallowed',array($this->name,$op,$field_id));
					}
				}
				$operator=$attr['operator'];
			}
			$this->_conditions->addCondition($field_id,$operator,$attr['expr'],true);
		}else{
			if($op!='isnull'&&$op!='isnotnull'){
				throw new jDaoXmlException($this->_parser->selector,'method.condition.valueexpr.missing',array($this->name,$op,$field_id));
			}
			$this->_conditions->addCondition($field_id,$operator,'',false);
		}
	}
	private function _addOrder($order){
		$attr=$this->_parser->getAttr($order,array('property','way'));
		$way=($attr['way']!==null ? $attr['way']:'ASC');
		if(substr($way,0,1)=='$'){
			if(!in_array(substr($way,1),$this->_parameters)){
				throw new jDaoXmlException($this->_parser->selector,'method.orderitem.parameter.unknown',array($this->name,$way));
			}
		}
		if($attr['property']!=''){
			$prop=$this->_parser->getProperties();
			if(isset($prop[$attr['property']])){
				$this->_conditions->addItemOrder($attr['property'],$way);
			}elseif(substr($attr['property'],0,1)=='$'){
				if(!in_array(substr($attr['property'],1),$this->_parameters)){
					throw new jDaoXmlException($this->_parser->selector,'method.orderitem.parameter.unknown',array($this->name,$way));
				}
				$this->_conditions->addItemOrder($attr['property'],$way);
			}else{
				throw new jDaoXmlException($this->_parser->selector,'method.orderitem.bad',array($attr['property'],$this->name));
			}
		}else{
			throw new jDaoXmlException($this->_parser->selector,'method.orderitem.property.missing',array($this->name));
		}
	}
	private function _addValue($attr){
		if(isset($attr['value']))
			$value=(string)$attr['value'];
		else
			$value=null;
		$attr=$this->_parser->getAttr($attr,array('property','expr'));
		$prop=$attr['property'];
		$props=$this->_parser->getProperties();
		if($prop===null){
			throw new jDaoXmlException($this->_parser->selector,'method.values.property.unknown',array($this->name,$prop));
		}
		if(!isset($props[$prop])){
			throw new jDaoXmlException($this->_parser->selector,'method.values.property.unknown',array($this->name,$prop));
		}
		if($props[$prop]->table!=$this->_parser->getPrimaryTable()){
			throw new jDaoXmlException($this->_parser->selector,'method.values.property.bad',array($this->name,$prop));
		}
		if($props[$prop]->isPK){
			throw new jDaoXmlException($this->_parser->selector,'method.values.property.pkforbidden',array($this->name,$prop));
		}
		if($value!==null&&$attr['expr']!==null){
			throw new jDaoXmlException($this->_parser->selector,'method.values.valueexpr',array($this->name,$prop));
		}else if($value!==null){
			$this->_values [$prop]=array($value,false);
		}else if($attr['expr']!==null){
			$this->_values [$prop]=array($attr['expr'],true);
		}else{
			$this->_values [$prop]=array('',false);
		}
	}
	private function _addLimit($limit){
		$attr=$this->_parser->getAttr($limit,array('offset','count'));
		extract($attr);
		if($offset===null){
			throw new jDaoXmlException($this->_parser->selector,'missing.attr',array('offset','limit'));
		}
		if($count===null){
			throw new jDaoXmlException($this->_parser->selector,'missing.attr',array('count','limit'));
		}
		if(substr($offset,0,1)=='$'){
			if(in_array(substr($offset,1),$this->_parameters)){
				$offsetparam=true;
			}else{
				throw new jDaoXmlException($this->_parser->selector,'method.limit.parameter.unknown',array($this->name,$offset));
			}
		}else{
			if(is_numeric($offset)){
				$offsetparam=false;
				$offset=intval($offset);
			}else{
				throw new jDaoXmlException($this->_parser->selector,'method.limit.badvalue',array($this->name,$offset));
			}
		}
		if(substr($count,0,1)=='$'){
			if(in_array(substr($count,1),$this->_parameters)){
				$countparam=true;
			}else{
				throw new jDaoXmlException($this->_parser->selector,'method.limit.parameter.unknown',array($this->name,$count));
			}
		}else{
			if(is_numeric($count)){
				$countparam=false;
				$count=intval($count);
			}else{
				throw new jDaoXmlException($this->_parser->selector,'method.limit.badvalue',array($this->name,$count));
			}
		}
		$this->_limit=compact('offset','count','offsetparam','countparam');
	}
}
class jDaoGenerator{
	protected $_dataParser=null;
	protected $_DaoRecordClassName=null;
	protected $_DaoClassName=null;
	protected $propertiesListForInsert='PrimaryTable';
	protected $aliasWord=' AS ';
	protected $tools;
	protected $_daoId;
	protected $_daoPath;
	protected $_dbType;
	protected $tableRealName='';
	protected $tableRealNameEsc='';
	protected $sqlWhereClause='';
	protected $sqlFromClause='';
	protected $sqlSelectClause='';
	function __construct($selector,$tools,$daoParser){
		$this->_daoId=$selector->toString();
		$this->_daoPath=$selector->getPath();
		$this->_dbType=$selector->driver;
		$this->_dataParser=$daoParser;
		$this->_DaoClassName=$selector->getDaoClass();
		$this->_DaoRecordClassName=$selector->getDaoRecordClass();
		$this->tools=$tools;
	}
	public function buildClasses(){
		$src=array();
		$src[]=' require_once ( JELIX_LIB_PATH .\'dao/jDaoRecordBase.class.php\');';
		$src[]=' require_once ( JELIX_LIB_PATH .\'dao/jDaoFactoryBase.class.php\');';
		$this->buildFromWhereClause();
		$this->sqlSelectClause=$this->buildSelectClause();
		$tables=$this->_dataParser->getTables();
		$pkFields=$this->_getPrimaryFieldsList();
		$this->tableRealName=$tables[$this->_dataParser->getPrimaryTable()]['realname'];
		$this->tableRealNameEsc=$this->_encloseName('\'.$this->_conn->prefixTable(\''.$this->tableRealName.'\').\'');
		$sqlPkCondition=$this->buildSimpleConditions($pkFields);
		if($sqlPkCondition!=''){
			$sqlPkCondition=($this->sqlWhereClause!='' ? ' AND ':' WHERE ').$sqlPkCondition;
		}
		$src[]="\nclass ".$this->_DaoRecordClassName.' extends jDaoRecordBase {';
		$properties=array();
		foreach($this->_dataParser->getProperties()as $id=>$field){
			$properties[$id]=get_object_vars($field);
			if($field->defaultValue!==null){
				$src[]=' public $'.$id.'='.var_export($field->defaultValue,true).';';
			}
			else
				$src[]=' public $'.$id.';';
		}
		$src[]='   public function getSelector() { return "'.$this->_daoId.'"; }';
		$src[]='   public function getProperties() { return '.$this->_DaoClassName.'::$_properties; }';
		$src[]='   public function getPrimaryKeyNames() { return '.$this->_DaoClassName.'::$_pkFields; }';
		$src[]='}';
		$src[]="\nclass ".$this->_DaoClassName.' extends jDaoFactoryBase {';
		$src[]='   protected $_tables = '.var_export($tables,true).';';
		$src[]='   protected $_primaryTable = \''.$this->_dataParser->getPrimaryTable().'\';';
		$src[]='   protected $_selectClause=\''.$this->sqlSelectClause.'\';';
		$src[]='   protected $_fromClause;';
		$src[]='   protected $_whereClause=\''.$this->sqlWhereClause.'\';';
		$src[]='   protected $_DaoRecordClassName=\''.$this->_DaoRecordClassName.'\';';
		$src[]='   protected $_daoSelector = \''.$this->_daoId.'\';';
		if($this->tools->trueValue!='1'){
			$src[]='   protected $trueValue ='.var_export($this->tools->trueValue,true).';';
			$src[]='   protected $falseValue ='.var_export($this->tools->falseValue,true).';';
		}
		if($this->_dataParser->hasEvent('deletebefore')||$this->_dataParser->hasEvent('delete'))
			$src[]='   protected $_deleteBeforeEvent = true;';
		if($this->_dataParser->hasEvent('deleteafter')||$this->_dataParser->hasEvent('delete'))
			$src[]='   protected $_deleteAfterEvent = true;';
		if($this->_dataParser->hasEvent('deletebybefore')||$this->_dataParser->hasEvent('deleteby'))
			$src[]='   protected $_deleteByBeforeEvent = true;';
		if($this->_dataParser->hasEvent('deletebyafter')||$this->_dataParser->hasEvent('deleteby'))
			$src[]='   protected $_deleteByAfterEvent = true;';
		$src[]='   public static $_properties = '.var_export($properties,true).';';
		$src[]='   public static $_pkFields = array('.$this->_writeFieldNamesWith($start='\'',$end='\'',$beetween=',',$pkFields).');';
		$src[]=' ';
		$src[]='public function __construct($conn){';
		$src[]='   parent::__construct($conn);';
		$src[]='   $this->_fromClause = \''.$this->sqlFromClause.'\';';
		$src[]='}';
		$src[]='   public function getProperties() { return self::$_properties; }';
		$src[]='   public function getPrimaryKeyNames() { return self::$_pkFields;}';
		$src[]=' ';
		$src[]=' protected function _getPkWhereClauseForSelect($pk){';
		$src[]='   extract($pk);';
		$src[]=' return \''.$sqlPkCondition.'\';';
		$src[]='}';
		$src[]=' ';
		$src[]='protected function _getPkWhereClauseForNonSelect($pk){';
		$src[]='   extract($pk);';
		$src[]='   return \' where '.$this->buildSimpleConditions($pkFields,'',false).'\';';
		$src[]='}';
		$src[]=$this->buildInsertMethod($pkFields);
		$src[]=$this->buildUpdateMethod($pkFields);
		$src[]=$this->buildUserMethods();
		$src[]=$this->buildEndOfClass();
		$src[]='}';
		return implode("\n",$src);
	}
	protected function buildInsertMethod($pkFields){
		$pkai=$this->getAutoIncrementPKField();
		$src=array();
		$src[]='public function insert ($record){';
		if($pkai!==null){
			$src[]=' if($record->'.$pkai->name.' > 0 ){';
			$src[]='    $query = \'INSERT INTO '.$this->tableRealNameEsc.' (';
			$fields=$this->_getPropertiesBy('PrimaryTable');
			list($fields,$values)=$this->_prepareValues($fields,'insertPattern','record->');
			$src[]=implode(',',$fields);
			$src[]=') VALUES (';
			$src[]=implode(', ',$values);
			$src[]=")';";
			$src[]='}else{';
			$fields=$this->_getPropertiesBy($this->propertiesListForInsert);
		}else{
			$fields=$this->_getPropertiesBy('PrimaryTable');
		}
		if($this->_dataParser->hasEvent('insertbefore')||$this->_dataParser->hasEvent('insert')){
			$src[]='   jEvent::notify("daoInsertBefore", array(\'dao\'=>$this->_daoSelector, \'record\'=>$record));';
		}
		$src[]='    $query = \'INSERT INTO '.$this->tableRealNameEsc.' (';
		list($fields,$values)=$this->_prepareValues($fields,'insertPattern','record->');
		$src[]=implode(',',$fields);
		$src[]=') VALUES (';
		$src[]=implode(', ',$values);
		$src[]=")';";
		if($pkai!==null)
			$src[]='}';
		$src[]='   $result = $this->_conn->exec ($query);';
		if($pkai!==null){
			$src[]='   if(!$result)';
			$src[]='       return false;';
			$src[]='   if($record->'.$pkai->name.' < 1 ) ';
			$src[]=$this->buildUpdateAutoIncrementPK($pkai);
		}
		$fields=$this->_getPropertiesBy('FieldToUpdate');
		if(count($fields)){
			$result=array();
			foreach($fields as $id=>$prop){
				$result[]=$this->buildSelectPattern($prop->selectPattern,'',$prop->fieldName,$prop->name);
			}
			$sql='SELECT '.(implode(', ',$result)). ' FROM '.$this->tableRealNameEsc.' WHERE ';
			$sql.=$this->buildSimpleConditions($pkFields,'record->',false);
			$src[]='  $query =\''.$sql.'\';';
			$src[]='  $rs  =  $this->_conn->query ($query);';
			$src[]='  $newrecord =  $rs->fetch ();';
			foreach($fields as $id=>$prop){
				$src[]='  $record->'.$prop->name.' = $newrecord->'.$prop->name.';';
			}
		}
		if($this->_dataParser->hasEvent('insertafter')||$this->_dataParser->hasEvent('insert')){
			$src[]='   jEvent::notify("daoInsertAfter", array(\'dao\'=>$this->_daoSelector, \'record\'=>$record));';
		}
		$src[]='    return $result;';
		$src[]='}';
		return implode("\n",$src);
	}
	protected function buildUpdateMethod($pkFields){
		$src=array();
		$src[]='public function update ($record){';
		list($fields,$values)=$this->_prepareValues($this->_getPropertiesBy('PrimaryFieldsExcludePk'),'updatePattern','record->');
		if(count($fields)){
			if($this->_dataParser->hasEvent('updatebefore')||$this->_dataParser->hasEvent('update')){
				$src[]='   jEvent::notify("daoUpdateBefore", array(\'dao\'=>$this->_daoSelector, \'record\'=>$record));';
			}
			$src[]='   $query = \'UPDATE '.$this->tableRealNameEsc.' SET ';
			$sqlSet='';
			foreach($fields as $k=>$fname){
				$sqlSet.=', '.$fname. '= '. $values[$k];
			}
			$src[]=substr($sqlSet,1);
			$sqlCondition=$this->buildSimpleConditions($pkFields,'record->',false);
			if($sqlCondition!='')
				$src[]=' where '.$sqlCondition;
			$src[]="';";
			$src[]='   $result = $this->_conn->exec ($query);';
			$fields=$this->_getPropertiesBy('FieldToUpdateOnUpdate');
			if(count($fields)){
				$result=array();
				foreach($fields as $id=>$prop){
					$result[]=$this->buildSelectPattern($prop->selectPattern,'',$prop->fieldName,$prop->name);
				}
				$sql='SELECT '.(implode(', ',$result)). ' FROM '.$this->tableRealNameEsc.' WHERE ';
				$sql.=$this->buildSimpleConditions($pkFields,'record->',false);
				$src[]='  $query =\''.$sql.'\';';
				$src[]='  $rs  =  $this->_conn->query ($query, jDbConnection::FETCH_INTO, $record);';
				$src[]='  $record =  $rs->fetch ();';
			}
			if($this->_dataParser->hasEvent('updateafter')||$this->_dataParser->hasEvent('update'))
				$src[]='   jEvent::notify("daoUpdateAfter", array(\'dao\'=>$this->_daoSelector, \'record\'=>$record));';
			$src[]='   return $result;';
		}else{
			$src[]="     throw new jException('jelix~dao.error.update.impossible',array('".$this->_daoId."','".$this->_daoPath."'));";
		}
		$src[]=' }';
		return implode("\n",$src);
	}
	protected function buildUserMethods(){
		$allField=$this->_getPropertiesBy('All');
		$primaryFields=$this->_getPropertiesBy('PrimaryTable');
		$src=array();
		foreach($this->_dataParser->getMethods()as $name=>$method){
			$defval=$method->getParametersDefaultValues();
			if(count($defval)){
				$mparam='';
				foreach($method->getParameters()as $param){
					$mparam.=', $'.$param;
					if(isset($defval[$param]))
						$mparam.='=\''.str_replace("'","\'",$defval[$param]).'\'';
				}
				$mparam=substr($mparam,1);
			}else{
				$mparam=implode(', $',$method->getParameters());
				if($mparam!='')$mparam='$'.$mparam;
			}
			$src[]=' function '.$method->name.' ('. $mparam.'){';
			$limit='';
			$glueCondition=' WHERE ';
			switch($method->type){
				case 'delete':
					$this->buildDeleteUserQuery($method,$src,$primaryFields);
					break;
				case 'update':
					$this->buildUpdateUserQuery($method,$src,$primaryFields);
					break;
				case 'php':
					$src[]=$method->getBody();
					$src[]='}';
					break;
				case 'count':
					$this->buildCountUserQuery($method,$src,$allField);
					break;
				case 'selectfirst':
				case 'select':
				default:
					$limit=$this->buildSelectUserQuery($method,$src,$allField);
			}
			if($method->type=='php')
				continue;
			switch($method->type){
				case 'delete':
				case 'update' :
					if($method->eventBeforeEnabled||$method->eventAfterEnabled){
						$src[]='   $args = func_get_args();';
						$methname=($method->type=='update'?'Update':'Delete');
						if($method->eventBeforeEnabled){
							$src[]='   jEvent::notify("daoSpecific'.$methname.'Before", array(\'dao\'=>$this->_daoSelector,\'method\'=>\''.
							$method->name.'\', \'params\'=>$args));';
						}
						if($method->eventAfterEnabled){
							$src[]='   $result = $this->_conn->exec ($__query);';
							$src[]='   jEvent::notify("daoSpecific'.$methname.'After", array(\'dao\'=>$this->_daoSelector,\'method\'=>\''.
								$method->name.'\', \'params\'=>$args));';
							$src[]='   return $result;';
						}else{
							$src[]='    return $this->_conn->exec ($__query);';
						}
					}else{
						$src[]='    return $this->_conn->exec ($__query);';
					}
					break;
				case 'count':
					$src[]='    $__rs = $this->_conn->query($__query);';
					$src[]='    $__res = $__rs->fetch();';
					$src[]='    return intval($__res->c);';
					break;
				case 'selectfirst':
					$src[]='    $__rs = $this->_conn->limitQuery($__query,0,1);';
					$src[]='    $this->finishInitResultSet($__rs);';
					$src[]='    return $__rs->fetch();';
					break;
				case 'select':
				default:
					if($limit)
						$src[]='    $__rs = $this->_conn->limitQuery($__query'.$limit.');';
					else
						$src[]='    $__rs = $this->_conn->query($__query);';
					$src[]='    $this->finishInitResultSet($__rs);';
					$src[]='    return $__rs;';
			}
			$src[]='}';
		}
		return implode("\n",$src);
	}
	protected function buildDeleteUserQuery($method,&$src,&$primaryFields){
		$src[]='    $__query = \'DELETE FROM '.$this->tableRealNameEsc.' \';';
		$cond=$method->getConditions();
		if($cond!==null){
			$sqlCond=$this->buildConditions($cond,$primaryFields,$method->getParameters(),false);
			if(trim($sqlCond)!='')
				$src[]='$__query .=\' WHERE '.$sqlCond."';";
		}
	}
	protected function buildUpdateUserQuery($method,&$src,&$primaryFields){
		$src[]='    $__query = \'UPDATE '.$this->tableRealNameEsc.' SET ';
		$updatefields=$this->_getPropertiesBy('PrimaryFieldsExcludePk');
		$sqlSet='';
		foreach($method->getValues()as $propname=>$value){
			if($value[1]){
				preg_match_all('/\$([a-zA-Z0-9_]+)/',$value[0],$varMatches,PREG_OFFSET_CAPTURE);
				$parameters=$method->getParameters();
				if(count($varMatches[0])){
					$result='';
					$len=0;
					foreach($varMatches[1] as $k=>$var){
						$result.=substr($value[0],$len,$len+$varMatches[0][$k][1]);
						$len+=$varMatches[0][$k][1] + strlen($varMatches[0][$k][0]);
						if(in_array($var[0],$parameters)){
							$result.='\'.'.$this->_preparePHPExpr($varMatches[0][$k][0],$updatefields[$propname],true).'.\'';
						}
						else{
							$result.=$varMatches[0][$k][0];
						}
					}
					$value[0]=$result;
				}
				$sqlSet.=', '.$this->_encloseName($updatefields[$propname]->fieldName). '= '. $value[0];
			}else{
				$sqlSet.=', '.$this->_encloseName($updatefields[$propname]->fieldName). '= '.
					$this->tools->escapeValue($updatefields[$propname]->unifiedType,$value[0],false,true);
			}
		}
		$src[]=substr($sqlSet,1).'\';';
		$cond=$method->getConditions();
		if($cond!==null){
			$sqlCond=$this->buildConditions($cond,$primaryFields,$method->getParameters(),false);
			if(trim($sqlCond)!='')
				$src[]='$__query .=\' WHERE '.$sqlCond."';";
		}
	}
	protected function buildCountUserQuery($method,&$src,&$allField){
		if($method->distinct!=''){
			$properties=$this->_dataParser->getProperties();
			$tables=$this->_dataParser->getTables();
			$prop=$properties[$method->distinct];
			$count=' DISTINCT '.$this->_encloseName($tables[$prop->table]['name']).'.'.$this->_encloseName($prop->fieldName);
		}
		else{
			$count='*';
		}
		$src[]='    $__query = \'SELECT COUNT('.$count.') as c \'.$this->_fromClause.$this->_whereClause;';
		$glueCondition=($this->sqlWhereClause!='' ? ' AND ':' WHERE ');
		$cond=$method->getConditions();
		if($cond!==null){
			$sqlCond=$this->buildConditions($cond,$allField,$method->getParameters(),true);
			if(trim($sqlCond)!='')
				$src[]='$__query .=\''.$glueCondition.$sqlCond."';";
		}
	}
	protected function buildSelectUserQuery($method,&$src,&$allField){
		$limit='';
		if($method->distinct!=''){
			$select='\''.$this->buildSelectClause($method->distinct).'\'';
		}
		else{
			$select=' $this->_selectClause';
		}
		$src[]='    $__query = '.$select.'.$this->_fromClause.$this->_whereClause;';
		$glueCondition=($this->sqlWhereClause!='' ? ' AND ':' WHERE ');
		if($method->type=='select'&&($lim=$method->getLimit())!==null){
			$limit=', '.$lim['offset'].', '.$lim['count'];
		}
		$sqlCond=$this->buildConditions($method->getConditions(),$allField,$method->getParameters(),true,$method->getGroupBy());
		if(trim($sqlCond)!='')
			$src[]='$__query .=\''.$glueCondition.$sqlCond."';";
		return $limit;
	}
	protected function buildFromWhereClause(){
		$tables=$this->_dataParser->getTables();
		foreach($tables as $table_name=>$table){
			$tables[$table_name]['realname']='\'.$this->_conn->prefixTable(\''.$table['realname'].'\').\'';
		}
		$primarytable=$tables[$this->_dataParser->getPrimaryTable()];
		$ptrealname=$this->_encloseName($primarytable['realname']);
		$ptname=$this->_encloseName($primarytable['name']);
		list($sqlFrom,$sqlWhere)=$this->buildOuterJoins($tables,$ptname);
		$sqlFrom=$ptrealname.$this->aliasWord.$ptname.$sqlFrom;
		foreach($this->_dataParser->getInnerJoins()as $tablejoin){
			$table=$tables[$tablejoin];
			$tablename=$this->_encloseName($table['name']);
			$sqlFrom.=', '.$this->_encloseName($table['realname']).$this->aliasWord.$tablename;
			foreach($table['fk'] as $k=>$fk){
				$sqlWhere.=' AND '.$ptname.'.'.$this->_encloseName($fk).'='.$tablename.'.'.$this->_encloseName($table['pk'][$k]);
			}
		}
		$this->sqlWhereClause=($sqlWhere!='' ? ' WHERE '.substr($sqlWhere,4):'');
		$this->sqlFromClause=' FROM '.$sqlFrom;
	}
	protected function buildOuterJoins(&$tables,$primaryTableName){
		$sqlFrom='';
		foreach($this->_dataParser->getOuterJoins()as $tablejoin){
			$table=$tables[$tablejoin[0]];
			$tablename=$this->_encloseName($table['name']);
			$r=$this->_encloseName($table['realname']).$this->aliasWord.$tablename;
			$fieldjoin='';
			foreach($table['fk'] as $k=>$fk){
				$fieldjoin.=' AND '.$primaryTableName.'.'.$this->_encloseName($fk).'='.$tablename.'.'.$this->_encloseName($table['pk'][$k]);
			}
			$fieldjoin=substr($fieldjoin,4);
			if($tablejoin[1]==0){
				$sqlFrom.=' LEFT JOIN '.$r.' ON ('.$fieldjoin.')';
			}elseif($tablejoin[1]==1){
				$sqlFrom.=' RIGHT JOIN '.$r.' ON ('.$fieldjoin.')';
			}
		}
		return array($sqlFrom,'');
	}
	protected function buildSelectClause($distinct=false){
		$result=array();
		$tables=$this->_dataParser->getTables();
		foreach($this->_dataParser->getProperties()as $id=>$prop){
			$table=$this->_encloseName($tables[$prop->table]['name']).'.';
			if($prop->selectPattern!=''){
				$result[]=$this->buildSelectPattern($prop->selectPattern,$table,$prop->fieldName,$prop->name);
			}
		}
		return 'SELECT '.($distinct?'DISTINCT ':'').(implode(', ',$result));
	}
	protected function buildSelectPattern($pattern,$table,$fieldname,$propname){
		if($pattern=='%s'){
			$field=$table.$this->_encloseName($fieldname);
			if($fieldname!=$propname){
				$field.=' as '.$this->_encloseName($propname);
			}
		}else{
			$field=str_replace(array("'","%s"),array("\\'",$table.$this->_encloseName($fieldname)),$pattern).' as '.$this->_encloseName($propname);
		}
		return $field;
	}
	protected function buildEndOfClass(){
		return '';
	}
	protected function _writeFieldsInfoWith($info,$start='',$end='',$beetween='',$using=null){
		$result=array();
		if($using===null){
			$using=$this->_dataParser->getProperties();
		}
		foreach($using as $id=>$field){
			$result[]=$start . $field->$info . $end;
		}
		return implode($beetween,$result);;
	}
	protected function _writeFieldNamesWith($start='',$end='',$beetween='',$using=null){
		return $this->_writeFieldsInfoWith('name',$start,$end,$beetween,$using);
	}
	protected function _getPrimaryFieldsList(){
		$tables=$this->_dataParser->getTables();
		$pkFields=array();
		$primTable=$tables[$this->_dataParser->getPrimaryTable()];
		$props=$this->_dataParser->getProperties();
		foreach($primTable['pk'] as $pkname){
			foreach($primTable['fields'] as $f){
				if($props[$f]->fieldName==$pkname){
					$pkFields[$props[$f]->name]=$props[$f];
					break;
				}
			}
		}
		return $pkFields;
	}
	protected function _getPropertiesBy($captureMethod){
		$captureMethod='_capture'.$captureMethod;
		$result=array();
		foreach($this->_dataParser->getProperties()as $field){
			if($this->$captureMethod($field)){
				$result[$field->name]=$field;
			}
		}
		return $result;
	}
	protected function _capturePrimaryFieldsExcludeAutoIncrement(&$field){
		return($field->table==$this->_dataParser->getPrimaryTable()&&!$field->autoIncrement);
	}
	protected function _capturePrimaryFieldsExcludePk(&$field){
		return($field->table==$this->_dataParser->getPrimaryTable())&&!$field->isPK;
	}
	protected function _capturePrimaryTable(&$field){
		return($field->table==$this->_dataParser->getPrimaryTable());
	}
	protected function _captureAll(&$field){
		return true;
	}
	protected function _captureFieldToUpdate(&$field){
		return($field->table==$this->_dataParser->getPrimaryTable()
				&&!$field->isPK
				&&!$field->isFK
				&&($field->autoIncrement||($field->insertPattern!='%s'&&$field->selectPattern!='')));
	}
	protected function _captureFieldToUpdateOnUpdate(&$field){
		return($field->table==$this->_dataParser->getPrimaryTable()
				&&!$field->isPK
				&&!$field->isFK
				&&($field->autoIncrement||($field->updatePattern!='%s'&&$field->selectPattern!='')));
	}
	protected function _captureBinaryField(&$field){
		return($field->unifiedType=='binary'||$field->unifiedType=='varbinary');
	}
	protected function getAutoIncrementPKField($using=null){
		if($using===null){
			$using=$this->_dataParser->getProperties();
		}
		$tb=$this->_dataParser->getTables();
		$tb=$tb[$this->_dataParser->getPrimaryTable()]['realname'];
		foreach($using as $id=>$field){
			if(!$field->isPK)
				continue;
			if($field->autoIncrement){
				return $field;
			}
		}
		return null;
	}
	protected function buildSimpleConditions(&$fields,$fieldPrefix='',$forSelect=true){
		$r=' ';
		$first=true;
		foreach($fields as $field){
			if(!$first){
				$r.=' AND ';
			}else{
				$first=false;
			}
			if($forSelect){
				$condition=$this->_encloseName($field->table).'.'.$this->_encloseName($field->fieldName);
			}else{
				$condition=$this->_encloseName($field->fieldName);
			}
			$var='$'.$fieldPrefix.$field->name;
			$value=$this->_preparePHPExpr($var,$field,!$field->requiredInConditions,'=');
			$r.=$condition.'\'.'.$value.'.\'';
		}
		return $r;
	}
	protected function _prepareValues($fieldList,$pattern='',$prefixfield=''){
		$values=$fields=array();
		foreach((array)$fieldList as $fieldName=>$field){
			if($pattern!=''&&$field->$pattern==''){
				continue;
			}
			$value=$this->_preparePHPExpr('$'.$prefixfield.$fieldName,$field,true);
			if($pattern!=''){
				$values[$field->name]=sprintf($field->$pattern,'\'.'.$value.'.\'');
			}else{
				$values[$field->name]='\'.'.$value.'.\'';
			}
			$fields[$field->name]=$this->_encloseName($field->fieldName);
		}
		return array($fields,$values);
	}
	protected function buildConditions($cond,$fields,$params=array(),$withPrefix=true,$groupby=null){
		if($cond)
			$sql=$this->buildOneSQLCondition($cond->condition,$fields,$params,$withPrefix,true);
		else
			$sql='';
		if($groupby&&count($groupby)){
			if(trim($sql)==''){
				$sql=' 1=1 ';
			}
			foreach($groupby as $k=>$f){
				if($withPrefix)
					$groupby[$k]=$this->_encloseName($fields[$f]->table).'.'.$this->_encloseName($fields[$f]->fieldName);
				else
					$groupby[$k]=$this->_encloseName($fields[$f]->fieldName);
			}
			$sql.=' GROUP BY '.implode(', ',$groupby);
		}
		$order=array();
		foreach($cond->order as $name=>$way){
			$ord='';
			if(isset($fields[$name])){
				if($withPrefix)
					$ord=$this->_encloseName($fields[$name]->table).'.'.$this->_encloseName($fields[$name]->fieldName);
				else
					$ord=$this->_encloseName($fields[$name]->fieldName);
			}elseif($name[0]=='$'){
				$ord='\'.'.$name.'.\'';
			}else{
				continue;
			}
			if($way[0]=='$'){
				$order[]=$ord.' \'.( strtolower('.$way.') ==\'asc\'?\'asc\':\'desc\').\'';
			}else{
				$order[]=$ord.' '.$way;
			}
		}
		if(count($order)> 0){
			if(trim($sql)==''){
				$sql=' 1=1 ';
			}
			$sql.=' ORDER BY '.implode(', ',$order);
		}
		return $sql;
	}
	protected function buildOneSQLCondition($condition,$fields,$params,$withPrefix,$principal=false){
		$r=' ';
		$first=true;
		foreach($condition->conditions as $cond){
			if(!$first){
				$r.=' '.$condition->glueOp.' ';
			}
			$first=false;
			$prop=$fields[$cond['field_id']];
			if($withPrefix){
				$f=$this->_encloseName($prop->table).'.'.$this->_encloseName($prop->fieldName);
			}else{
				$f=$this->_encloseName($prop->fieldName);
			}
			$r.=$f.' ';
			if($cond['operator']=='IN'||$cond['operator']=='NOT IN'){
				if($cond['isExpr']){
					$phpexpr=$this->_preparePHPCallbackExpr($prop);
					$phpvalue='implode(\',\', array_map( '.$phpexpr.', '.$cond['value'].'))';
					$value='(\'.'.$phpvalue.'.\')';
				}else{
					$value='('.str_replace("'","\\'",$cond['value']).')';
				}
				$r.=$cond['operator'].' '.$value;
			}elseif($cond['operator']=='IS NULL'||$cond['operator']=='IS NOT NULL'){
				$r.=$cond['operator'].' ';
			}else{
				if($cond['isExpr']){
					$value=str_replace("'","\\'",$cond['value']);
					if($value[0]=='$'){
						$value='\'.'.$this->_preparePHPExpr($value,$prop,!$prop->requiredInConditions,$cond['operator']).'.\'';
					}else{
						foreach($params as $param){
							$value=str_replace('$'.$param,'\'.'.$this->_preparePHPExpr('$'.$param,$prop,!$prop->requiredInConditions).'.\'',$value);
						}
						$value=$cond['operator'].' '.$value;
					}
				}else{
					$value=$cond['operator'].' ';
					if($cond['operator']=='LIKE'||$cond['operator']=='NOT LIKE'){
						$value.=$this->tools->escapeValue('varchar',$cond['value'],false,true);
					}else{
						$value.=$this->tools->escapeValue($prop->unifiedType,$cond['value'],false,true);
					}
				}
				$r.=$value;
			}
		}
		foreach($condition->group as $conditionDetail){
			if(!$first){
				$r.=' '.$condition->glueOp.' ';
			}
			$r.=$this->buildOneSQLCondition($conditionDetail,$fields,$params,$withPrefix);
			$first=false;
		}
		if(strlen(trim($r))> 0&&(!$principal||($principal&&$condition->glueOp!='AND'))){
			$r='('.$r.')';
		}
		return $r;
	}
	protected function _preparePHPExpr($expr,$field,$checknull=true,$forCondition=''){
		$opnull=$opval='';
		if($checknull&&$forCondition!=''){
			if($forCondition=='=')
				$opnull='IS ';
			elseif($forCondition=='<>')
				$opnull='IS NOT ';
			else
				$checknull=false;
		}
		$type='';
		if($forCondition!='LIKE'&&$forCondition!='NOT LIKE')
			$type=strtolower($field->unifiedType);
		if($forCondition!=''){
			$forCondition='\' '.$forCondition.' \'.';
		}
		switch($type){
			case 'integer':
				if($checknull){
					$expr='('.$expr.' === null ? \''.$opnull.'NULL\' : '.$forCondition.'intval('.$expr.'))';
				}else{
					$expr=$forCondition.'intval('.$expr.')';
				}
				break;
			case 'double':
			case 'float':
			case 'numeric':
			case 'decimal':
				if($checknull){
					$expr='('.$expr.' === null ? \''.$opnull.'NULL\' : '.$forCondition.'jDb::floatToStr('.$expr.'))';
				}else{
					$expr=$forCondition.'jDb::floatToStr('.$expr.')';
				}
				break;
			case 'boolean':
				if($checknull){
					$expr='('.$expr.' === null ? \''.$opnull.'NULL\' : '.$forCondition.'$this->_prepareValue('.$expr.', "boolean", true))';
				}else{
					$expr=$forCondition.'$this->_prepareValue('.$expr.', "boolean", true)';
				}
				break;
			default:
				if($type=='varbinary'||$type=='binary')
					$qparam=',true';
				else
					$qparam='';
				if($checknull){
					$expr='('.$expr.' === null ? \''.$opnull.'NULL\' : '.$forCondition.'$this->_conn->quote2('.$expr.',false'.$qparam.'))';
				}
				else{
					$expr=$forCondition.'$this->_conn->quote'.($qparam?'2('.$expr.',true,true)':'('.$expr.')');
				}
		}
		return $expr;
	}
	protected function _preparePHPCallbackExpr($field){
		$type=strtolower($field->unifiedType);
		switch($type){
			case 'integer':
				return 'create_function(\'$__e\',\'return intval($__e);\')';
			case 'double':
			case 'float':
			case 'numeric':
			case 'decimal':
				return 'create_function(\'$__e\',\'return jDb::floatToStr($__e);\')';
			case 'boolean':
				return 'array($this, \'_callbackBool\')';
			default:
				if($type=='varbinary'||$type=='binary')
					return 'array($this, \'_callbackQuoteBin\')';
				else
					return 'array($this, \'_callbackQuote\')';
		}
	}
	protected function _encloseName($name){
		return $this->tools->encloseName($name);
	}
	protected function buildUpdateAutoIncrementPK($pkai){
		return '       $record->'.$pkai->name.'= $this->_conn->lastInsertId();';
	}
}
class jDaoCompiler  implements jISimpleCompiler{
	public function compile($selector){
		$daoPath=$selector->getPath();
		$doc=new DOMDocument();
		if(!$doc->load($daoPath)){
			throw new jException('jelix~daoxml.file.unknown',$daoPath);
		}
		if($doc->documentElement->namespaceURI!=JELIX_NAMESPACE_BASE.'dao/1.0'){
			throw new jException('jelix~daoxml.namespace.wrong',array($daoPath,$doc->namespaceURI));
		}
		$tools=jApp::loadPlugin($selector->driver,'db','.dbtools.php',$selector->driver.'DbTools');
		if(is_null($tools))
			throw new jException('jelix~db.error.driver.notfound',$selector->driver);
		$parser=new jDaoParser($selector);
		$parser->parse(simplexml_import_dom($doc),$tools);
		require_once(jApp::config()->_pluginsPathList_db[$selector->driver].$selector->driver.'.daobuilder.php');
		$class=$selector->driver.'DaoBuilder';
		$generator=new $class($selector,$tools,$parser);
		$compiled='<?php '.$generator->buildClasses()."\n?>";
		jFile::write($selector->getCompiledFilePath(),$compiled);
		return true;
	}
}
