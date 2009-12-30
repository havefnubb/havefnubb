<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package    jelix
* @subpackage dao
* @author     Croes Gérald, Laurent Jouanneau
* @contributor Laurent Jouanneau
* @copyright  2001-2005 CopixTeam, 2005-2007 Laurent Jouanneau
* Ideas and some parts of this file were get originally from the Copix project
* (CopixDAOGeneratorV1, CopixDAODefinitionV1, Copix 2.3dev20050901, http://www.copix.org)
* Few lines of code are still copyrighted 2001-2005 CopixTeam (LGPL licence).
* Initial authors of this lines of code are Gerald Croes and Laurent Jouanneau,
* and classes were adapted/improved for Jelix by Laurent Jouanneau
*
* @link        http://www.jelix.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/
class jDaoParser{
	private $_properties = array();
	private $_tables = array();
	private $_primaryTable = '';
	private $_ojoins = array();
	private $_ijoins = array();
	private $_methods = array();
	private $_eventList = array();
	public $hasOnlyPrimaryKeys = false;
	function __construct(){
	}
	public function parse( $xml, $debug=0){
		if(isset($xml->datasources) && isset($xml->datasources[0]->primarytable)){
			$t = $this->_parseTable(0, $xml->datasources[0]->primarytable[0]);
			$this->_primaryTable = $t['name'];
			if(isset($xml->datasources[0]->primarytable[1])){
				throw new jDaoXmlException('table.two.many');
			}
			foreach($xml->datasources[0]->foreigntable as $table){
				$this->_parseTable(1, $table);
			}
			foreach($xml->datasources[0]->optionalforeigntable as $table){
				$this->_parseTable(2, $table);
			}
		}else{
			throw new jDaoXmlException('datasource.missing');
		}
		if($debug == 2) return;
		$countprop = 0;
		if(isset($xml->record) && isset($xml->record[0]->property)){
			foreach($xml->record[0]->property as $prop){
				$p = new jDaoProperty($prop->attributes(), $this);
				$this->_properties[$p->name] = $p;
				$this->_tables[$p->table]['fields'][] = $p->name;
				if(($p->table == $this->_primaryTable) && !$p->isPK)
					$countprop ++;
			}
			$this->hasOnlyPrimaryKeys =($countprop == 0);
		}else
			throw new jDaoXmlException('properties.missing');
		if($debug == 1) return;
		if(isset($xml->factory)){
			if(isset($xml->factory[0]['events'])){
				$events = (string)$xml->factory[0]['events'];
				$this->_eventList = preg_split("/[\s,]+/", $events);
			}
			if(isset($xml->factory[0]->method)){
				foreach($xml->factory[0]->method as $method){
					$m = new jDaoMethod($method, $this);
					if(isset($this->_methods[$m->name])){
						throw new jDaoXmlException('method.duplicate',$m->name);
					}
					$this->_methods[$m->name] = $m;
				}
			}
		}
	}
	private function _parseTable($typetable, $tabletag){
		$infos = $this->getAttr($tabletag, array('name','realname','primarykey','onforeignkey'));
		if($infos['name'] === null)
			throw new jDaoXmlException('table.name');
		if($infos['realname'] === null)
			$infos['realname'] = $infos['name'];
		if($infos['primarykey'] === null)
			throw new jDaoXmlException('primarykey.missing');
		$infos['pk']= preg_split("/[\s,]+/", $infos['primarykey']);
		unset($infos['primarykey']);
		if(count($infos['pk']) == 0 || $infos['pk'][0] == '')
			throw new jDaoXmlException('primarykey.missing');
		if($typetable){
			if($infos['onforeignkey'] === null)
				throw new jDaoXmlException('foreignkey.missing');
			$infos['fk']=preg_split("/[\s,]+/",$infos['onforeignkey']);
			unset($infos['onforeignkey']);
			if(count($infos['fk']) == 0 || $infos['fk'][0] == '')
				throw new jDaoXmlException('foreignkey.missing');
			if(count($infos['fk']) != count($infos['pk']))
				throw new jDaoXmlException('foreignkey.missing');
			if($typetable == 1){
				$this->_ijoins[]=$infos['name'];
			}else{
				$this->_ojoins[]=array($infos['name'],0);
			}
		}else{
			unset($infos['onforeignkey']);
		}
		$infos['fields'] = array();
		$this->_tables[$infos['name']] = $infos;
		return $infos;
	}
	public function getAttr($tag, $requiredattr){
		$res=array();
		foreach($requiredattr as $attr){
			if(isset($tag[$attr]) && trim((string)$tag[$attr]) != '')
				$res[$attr]=(string)$tag[$attr];
			else
				$res[$attr]=null;
		}
		return $res;
	}
	public function getBool($value){
		return in_array(trim($value), array('true', '1', 'yes'));
	}
	public function getProperties(){ return $this->_properties;}
	public function getTables(){  return $this->_tables;}
	public function getPrimaryTable(){  return $this->_primaryTable;}
	public function getMethods(){  return $this->_methods;}
	public function getOuterJoins(){  return $this->_ojoins;}
	public function getInnerJoins(){  return $this->_ijoins;}
	public function hasEvent($event){ return in_array($event,$this->_eventList);}
}
class jDaoProperty{
	public $name = '';
	public $fieldName = '';
	public $regExp = null;
	public $required = false;
	public $requiredInConditions = false;
	public $isPK = false;
	public $isFK = false;
	public $datatype;
	public $table=null;
	public $updatePattern='%s';
	public $insertPattern='%s';
	public $selectPattern='%s';
	public $sequenceName='';
	public $maxlength = null;
	public $minlength = null;
	public $ofPrimaryTable = true;
	public $defaultValue = null;
	function __construct($aParams, $def){
		$needed = array('name', 'fieldname', 'table', 'datatype', 'required',
						'minlength', 'maxlength', 'regexp', 'sequence', 'default');
		$params = $def->getAttr($aParams, $needed);
		if($params['name']===null){
			throw new jDaoXmlException('missing.attr', array('name', 'property'));
		}
		$this->name	   = $params['name'];
		if(!preg_match('/^[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*$/', $this->name)){
			throw new jDaoXmlException('property.invalid.name', $this->name);
		}
		$this->fieldName  = $params['fieldname'] !==null ? $params['fieldname'] : $this->name;
		$this->table	  = $params['table'] !==null ? $params['table'] : $def->getPrimaryTable();
		$tables = $def->getTables();
		if(!isset( $tables[$this->table])){
			throw new jDaoXmlException('property.unknow.table', $this->name);
		}
		$this->required   = $this->requiredInConditions = $def->getBool($params['required']);
		$this->maxlength  = $params['maxlength'] !== null ? intval($params['maxlength']) : null;
		$this->minlength  = $params['minlength'] !== null ? intval($params['minlength']) : null;
		$this->regExp	 = $params['regexp'];
		if($params['datatype']===null){
			throw new jDaoXmlException('missing.attr', array('datatype', 'property'));
		}
		$params['datatype']=trim(strtolower($params['datatype']));
		if(!in_array($params['datatype'],
					   array('autoincrement', 'bigautoincrement', 'int',
							  'datetime', 'time', 'integer', 'varchar', 'string',
							  'text', 'varchardate', 'date', 'numeric', 'double',
							  'float', 'boolean'))){
		   throw new jDaoXmlException('wrong.attr', array($params['datatype'],
														   $this->fieldName,
														   'property'));
		}
		$this->datatype = strtolower($params['datatype']);
		$this->needsQuotes = in_array($params['datatype'],
				array('string', 'varchar', 'text', 'date', 'datetime', 'time'));
		$this->isPK = in_array($this->fieldName, $tables[$this->table]['pk']);
		if(!$this->isPK){
			$this->isFK = isset($tables[$this->table]['fk'][$this->fieldName]);
		} else{
			$this->required = true;
			$this->requiredInConditions = true;
		}
		if($this->datatype == 'autoincrement' || $this->datatype == 'bigautoincrement'){
			if($params['sequence'] !==null){
				$this->sequenceName = $params['sequence'];
			}
			$this->required = false;
			$this->requiredInConditions = true;
		}
		if($params['default'] !== null){
			switch($this->datatype){
			  case 'autoincrement':
			  case 'int':
			  case 'integer':
				$this->defaultValue = intval($params['default']);
				break;
			  case 'double':
			  case 'float':
				$this->defaultValue = doubleval($params['default']);
				break;
			  case 'boolean':
				$v = $params['default'];
				$this->defaultValue =($v =='1'|| $v=='t'|| strtolower($v) =='true');
				break;
			  default:
				$this->defaultValue = $params['default'];
			}
		}
		if(!$this->isPK && !$this->isFK){
			if(isset($aParams['updatepattern'])){
				$this->updatePattern=(string)$aParams['updatepattern'];
			}
			if(isset($aParams['insertpattern'])){
				$this->insertPattern=(string)$aParams['insertpattern'];
			}
			if(isset($aParams['selectpattern'])){
				$this->selectPattern=(string)$aParams['selectpattern'];
			}
		}
		if($this->table != $def->getPrimaryTable()){
			$this->updatePattern = '';
			$this->insertPattern = '';
			$this->required = false;
			$this->requiredInConditions = false;
			$this->ofPrimaryTable = false;
		}else{
			$this->ofPrimaryTable=true;
		}
	}
}
class jDaoMethod{
	public $name;
	public $type;
	public $distinct=false;
	public $eventBeforeEnabled = false;
	public $eventAfterEnabled = false;
	private $_conditions = null;
	private $_parameters   = array();
	private $_parametersDefaultValues = array();
	private $_limit = null;
	private $_values = array();
	private $_def = null;
	private $_procstock=null;
	private $_body=null;
	private $_groupBy=null;
	function __construct($method, $def){
		$this->_def = $def;
		$params = $def->getAttr($method, array('name', 'type', 'call','distinct', 'eventbefore', 'eventafter', 'groupby'));
		if($params['name']===null){
			throw new jDaoXmlException('missing.attr', array('name', 'method'));
		}
		$this->name = $params['name'];
		$this->type = $params['type'] ? strtolower($params['type']) : 'select';
		if(isset($method->parameter)){
			foreach($method->parameter as $param){
				$attr = $param->attributes();
				if(!isset($attr['name'])){
					throw new jDaoXmlException('method.parameter.unknowname', array($this->name));
				}
				$this->_parameters[]=(string)$attr['name'];
				if(isset($attr['default'])){
					$this->_parametersDefaultValues[(string)$attr['name']]=(string)$attr['default'];
				}
			}
		}
		if($this->type == 'sql'){
			if($params['call'] === null){
				throw new jDaoXmlException('method.procstock.name.missing');
			}
			$this->_procstock=$params['call'];
			return;
		}
		if($this->type == 'php'){
			if(isset($method->body)){
				$this->_body = (string)$method->body;
			}else{
				throw new jDaoXmlException('method.body.missing');
			}
			return;
		}
		$this->_conditions = new jDaoConditions();
		if(isset($method->conditions)){
			$this->_parseConditions($method->conditions[0],false);
		}
		if($this->type == 'update' || $this->type == 'delete'){
			if($params['eventbefore'] == 'true')
				$this->eventBeforeEnabled = true;
			if($params['eventafter'] == 'true')
				$this->eventAfterEnabled = true;
		}
		if($this->type == 'update'){
			if($this->_def->hasOnlyPrimaryKeys)
				throw new jDaoXmlException('method.update.forbidden',array($this->name));
			if(isset($method->values) && isset($method->values[0]->value)){
				foreach($method->values[0]->value as $val){
					$this->_addValue($val);
				}
			}else{
				throw new jDaoXmlException('method.values.undefine',array($this->name));
			}
			return;
		}
		if(strlen($params['distinct'])){
			if($this->type == 'select'){
				$this->distinct=$this->_def->getBool($params['distinct']);
			}elseif($this->type == 'count'){
				$props = $this->_def->getProperties();
				if(!isset($props[$params['distinct']])){
					throw new jDaoXmlException('method.property.unknown', array($this->name, $params['distinct']));
				}
				$this->distinct=$params['distinct'];
			}else{
				throw new jDaoXmlException('forbidden.attr.context', array('distinct', '<method name="'.$this->name.'"'));
			}
		}
		if($this->type == 'count')
			return;
		if(isset($method->order) && isset($method->order[0]->orderitem)){
			foreach($method->order[0]->orderitem as $item){
				$this->_addOrder($item);
			}
		}
		if(strlen($params['groupby'])){
			if($this->type == 'select' || $this->type == 'selectfirst'){
				$this->_groupBy = preg_split("/[\s,]+/", $params['groupby']);
				$props = $this->_def->getProperties();
				foreach($this->_groupBy as $p){
					if(!isset($props[$p])){
						throw new jDaoXmlException('method.property.unknown', array($this->name, $p));
					}
				}
			}else{
				throw new jDaoXmlException('forbidden.attr.context', array('groupby', '<method name="'.$this->name.'"'));
			}
		}
		if(isset($method->limit)){
			if(isset($method->limit[1])){
				throw new jDaoXmlException('tag.duplicate', array('limit', $this->name));
			}
			if($this->type == 'select'){
				$this->_addLimit($method->limit[0]);
			}else{
				throw new jDaoXmlException('method.limit.forbidden', $this->name);
			}
		}
	}
	public function getConditions(){ return $this->_conditions;}
	public function getParameters(){ return $this->_parameters;}
	public function getParametersDefaultValues(){ return $this->_parametersDefaultValues;}
	public function getLimit(){ return $this->_limit;}
	public function getValues(){ return $this->_values;}
	public function getProcStock(){ return $this->_procstock;}
	public function getBody(){ return $this->_body;}
	public function getGroupBy(){ return $this->_groupBy;}
	private function _parseConditions($conditions, $subcond=true){
		if(isset($conditions['logic'])){
			$kind = strtoupper((string)$conditions['logic']);
		}else{
			$kind = 'AND';
		}
		if($subcond){
			$this->_conditions->startGroup($kind);
		}else{
			$this->_conditions->condition->glueOp =$kind;
		}
		foreach($conditions->children() as $op=>$cond){
			if($op !='conditions')
				$this->_addCondition($op,$cond);
			else
				$this->_parseConditions($cond);
		}
		if($subcond){
			$this->_conditions->endGroup();
		}
	}
	private $_op = array('eq'=>'=', 'neq'=>'<>', 'lt'=>'<', 'gt'=>'>', 'lteq'=>'<=', 'gteq'=>'>=',
		'like'=>'LIKE', 'notlike'=>'NOT LIKE', 'isnull'=>'IS NULL', 'isnotnull'=>'IS NOT NULL','in'=>'IN', 'notin'=>'NOT IN',
		'binary_op'=>'dummy');
	private $_attrcond = array('property', 'expr', 'operator', 'driver');
	private function _addCondition($op, $cond){
		$attr = $this->_def->getAttr($cond, $this->_attrcond);
		$field_id =($attr['property']!==null? $attr['property']:'');
		if(!isset($this->_op[$op])){
			throw new jDaoXmlException('method.condition.unknown', array($this->name, $op));
		}
		$operator = $this->_op[$op];
		$props = $this->_def->getProperties();
		if(!isset($props[$field_id])){
			throw new jDaoXmlException('method.property.unknown', array($this->name, $field_id));
		}
		if($this->type=='update'){
			if($props[$field_id]->table != $this->_def->getPrimaryTable()){
				throw new jDaoXmlException('method.property.forbidden', array($this->name, $field_id));
			}
		}
		if(isset($cond['value']))
			$value=(string)$cond['value'];
		else
			$value = null;
		if($value!==null && $attr['expr']!==null){
			throw new jDaoXmlException('method.condition.valueexpr.together', array($this->name, $op));
		}else if($value!==null){
			if($op == 'isnull' || $op =='isnotnull'){
				throw new jDaoXmlException('method.condition.valueexpr.notallowed', array($this->name, $op,$field_id));
			}
			if($op == 'binary_op'){
				if(!isset($attr['operator']) || empty($attr['operator'])){
					throw new jDaoXmlException('method.condition.operator.missing', array($this->name, $op,$field_id));
				}
				if(isset($attr['driver']) && !empty($attr['driver'])){
					if(jDaoCompiler::$dbType != $attr['driver']){
						throw new jDaoXmlException('method.condition.driver.notallowed', array($this->name, $op,$field_id));
					}
				}
				$operator = $attr['operator'];
			}
			$this->_conditions->addCondition($field_id, $operator, $value);
		}else if($attr['expr']!==null){
			if($op == 'isnull' || $op =='isnotnull'){
				throw new jDaoXmlException('method.condition.valueexpr.notallowed', array($this->name, $op, $field_id));
			}
			if(($op == 'in' || $op =='notin')&& !preg_match('/^\$[a-zA-Z0-9_]+$/', $attr['expr'])){
				throw new jDaoXmlException('method.condition.innotin.bad.expr', array($this->name, $op, $field_id));
			}
			if($op == 'binary_op'){
				if(!isset($attr['operator']) || empty($attr['operator'])){
					throw new jDaoXmlException('method.condition.operator.missing', array($this->name, $op,$field_id));
				}
				if(isset($attr['driver']) && !empty($attr['driver'])){
					if(jDaoCompiler::$dbType != $attr['driver']){
						throw new jDaoXmlException('method.condition.driver.notallowed', array($this->name, $op,$field_id));
					}
				}
				$operator = $attr['operator'];
			}
			$this->_conditions->addCondition($field_id, $operator, $attr['expr'], true);
		}else{
			if($op != 'isnull' && $op !='isnotnull'){
				throw new jDaoXmlException('method.condition.valueexpr.missing', array($this->name, $op, $field_id));
			}
			$this->_conditions->addCondition($field_id, $operator, '', false);
		}
	}
	private function _addOrder($order){
		$attr = $this->_def->getAttr($order, array('property','way'));
		$way  =($attr['way'] !== null ? $attr['way']:'ASC');
		if(substr($way,0,1) == '$'){
			if(!in_array(substr($way,1),$this->_parameters)){
				throw new jDaoXmlException('method.orderitem.parameter.unknow', array($this->name, $way));
			}
		}
		if($attr['property'] != ''){
			$prop =$this->_def->getProperties();
			if(isset($prop[$attr['property']])){
				$this->_conditions->addItemOrder($attr['property'], $way);
			}elseif(substr($attr['property'],0,1) == '$'){
				if(!in_array(substr($attr['property'],1),$this->_parameters)){
					throw new jDaoXmlException('method.orderitem.parameter.unknow', array($this->name, $way));
				}
				$this->_conditions->addItemOrder($attr['property'], $way);
			}else{
				throw new jDaoXmlException('method.orderitem.bad', array($attr['property'], $this->name));
			}
		}else{
			throw new jDaoXmlException('method.orderitem.property.missing', array($this->name));
		}
	}
	private function _addValue($attr){
		if(isset($attr['value']))
			$value=(string)$attr['value'];
		else
			$value = null;
		$attr = $this->_def->getAttr($attr, array('property','expr'));
		$prop = $attr['property'];
		$props =$this->_def->getProperties();
		if($prop === null){
			throw new jDaoXmlException('method.values.property.unknow', array($this->name, $prop));
		}
		if(!isset($props[$prop])){
			throw new jDaoXmlException('method.values.property.unknow', array($this->name, $prop));
		}
		if($props[$prop]->table != $this->_def->getPrimaryTable()){
			throw new jDaoXmlException('method.values.property.bad', array($this->name,$prop));
		}
		if($props[$prop]->isPK){
			throw new jDaoXmlException('method.values.property.pkforbidden', array($this->name,$prop));
		}
		if($value!==null && $attr['expr']!==null){
			throw new jDaoXmlException('method.values.valueexpr', array($this->name, $prop));
		}else if($value!==null){
			$this->_values [$prop]= array( $value, false);
		}else if($attr['expr']!==null){
			$this->_values [$prop]= array( $attr['expr'], true);
		}else{
			$this->_values [$prop]= array( '', false);
		}
	}
	private function _addLimit($limit){
		$attr = $this->_def->getAttr($limit, array('offset','count'));
		extract($attr);
		if( $offset === null){
			throw new jDaoXmlException('missing.attr',array('offset','limit'));
		}
		if($count === null){
			throw new jDaoXmlException('missing.attr',array('count','limit'));
		}
		if(substr($offset,0,1) == '$'){
			if(in_array(substr($offset,1),$this->_parameters)){
				$offsetparam=true;
			}else{
				throw new jDaoXmlException('method.limit.parameter.unknow', array($this->name, $offset));
			}
		}else{
			if(is_numeric($offset)){
				$offsetparam=false;
				$offset = intval($offset);
			}else{
				throw new jDaoXmlException('method.limit.badvalue', array($this->name, $offset));
			}
		}
		if(substr($count,0,1) == '$'){
			if(in_array(substr($count,1),$this->_parameters)){
				$countparam=true;
			}else{
				throw new jDaoXmlException('method.limit.parameter.unknow', array($this->name, $count));
			}
		}else{
			if(is_numeric($count)){
				$countparam=false;
				$count=intval($count);
			}else{
				throw new jDaoXmlException('method.limit.badvalue', array($this->name, $count));
			}
		}
		$this->_limit= compact('offset', 'count', 'offsetparam','countparam');
	}
}
class jDaoGenerator{
	protected $_dataParser = null;
	protected $_DaoRecordClassName = null;
	protected $_DaoClassName = null;
	protected $propertiesListForInsert = 'PrimaryTable';
	protected $aliasWord = ' AS ';
	protected $trueValue = 1;
	protected $falseValue = 0;
	function __construct($factoryClassName, $recordClassName, $daoDefinition){
		$this->_dataParser = $daoDefinition;
		$this->_DaoClassName = $factoryClassName;
		$this->_DaoRecordClassName = $recordClassName;
	}
	public function buildClasses(){
		$src = array();
		$src[] = ' require_once ( JELIX_LIB_PATH .\'dao/jDaoRecordBase.class.php\');';
		$src[] = ' require_once ( JELIX_LIB_PATH .\'dao/jDaoFactoryBase.class.php\');';
		list($sqlFromClause, $sqlWhereClause)= $this->_getFromClause();
		$tables			= $this->_dataParser->getTables();
		$sqlSelectClause   = $this->_getSelectClause();
		$pkFields		  = $this->_getPropertiesBy('PkFields');
		$pTableRealName	= $tables[$this->_dataParser->getPrimaryTable()]['realname'];
		$pTableRealNameEsc = $this->_encloseName('\'.$this->_conn->prefixTable(\''.$pTableRealName.'\').\'');
		$pkai			  = $this->_getAutoIncrementPKField();
		$sqlPkCondition	= $this->_buildSimpleConditions($pkFields);
		if($sqlPkCondition != ''){
			$sqlPkCondition=($sqlWhereClause !='' ? ' AND ':' WHERE ').$sqlPkCondition;
		}
		$src[] = "\nclass ".$this->_DaoRecordClassName.' extends jDaoRecordBase {';
		$properties=array();
		foreach($this->_dataParser->getProperties() as $id=>$field){
			$properties[$id] = get_object_vars($field);
			if($field->defaultValue !== null)
				$src[] =' public $'.$id.'='.var_export($field->defaultValue, true).';';
			else
				$src[] =' public $'.$id.';';
		}
		$src[] = '   public function getProperties() { return '.$this->_DaoClassName.'::$_properties; }';
		$src[] = '   public function getPrimaryKeyNames() { return '.$this->_DaoClassName.'::$_pkFields; }';
		$src[] = '}';
		$src[] = "\nclass ".$this->_DaoClassName.' extends jDaoFactoryBase {';
		$src[] = '   protected $_tables = '.var_export($tables, true).';';
		$src[] = '   protected $_primaryTable = \''.$this->_dataParser->getPrimaryTable().'\';';
		$src[] = '   protected $_selectClause=\''.$sqlSelectClause.'\';';
		$src[] = '   protected $_fromClause;';
		$src[] = '   protected $_whereClause=\''.$sqlWhereClause.'\';';
		$src[] = '   protected $_DaoRecordClassName=\''.$this->_DaoRecordClassName.'\';';
		$src[] = '   protected $_daoSelector = \''.jDaoCompiler::$daoId.'\';';
		if($this->trueValue != 1){
			$src[]='   protected $trueValue ='.var_export($this->trueValue,true).';';
			$src[]='   protected $falseValue ='.var_export($this->falseValue,true).';';
		}
		if($this->_dataParser->hasEvent('deletebefore') || $this->_dataParser->hasEvent('delete'))
			$src[] = '   protected $_deleteBeforeEvent = true;';
		if($this->_dataParser->hasEvent('deleteafter') || $this->_dataParser->hasEvent('delete'))
			$src[] = '   protected $_deleteAfterEvent = true;';
		if($this->_dataParser->hasEvent('deletebybefore') || $this->_dataParser->hasEvent('deleteby'))
			$src[] = '   protected $_deleteByBeforeEvent = true;';
		if($this->_dataParser->hasEvent('deletebyafter') || $this->_dataParser->hasEvent('deleteby'))
			$src[] = '   protected $_deleteByAfterEvent = true;';
		$src[] = '   public static $_properties = '.var_export($properties, true).';';
		$src[] = '   public static $_pkFields = array('.$this->_writeFieldNamesWith($start = '\'', $end='\'', $beetween = ',', $pkFields).');';
		$src[] = ' ';
		$src[] = 'public function __construct($conn){';
		$src[] = '   parent::__construct($conn);';
		$src[] = '   $this->_fromClause = \''.$sqlFromClause.'\';';
		$src[] = '}';
		$src[] = '   public function getProperties() { return self::$_properties; }';
		$src[] = '   public function getPrimaryKeyNames() { return self::$_pkFields;}';
		$src[] = ' ';
		$src[] = ' protected function _getPkWhereClauseForSelect($pk){';
		$src[] = '   extract($pk);';
		$src[] = ' return \''.$sqlPkCondition.'\';';
		$src[] = '}';
		$src[] = ' ';
		$src[] = 'protected function _getPkWhereClauseForNonSelect($pk){';
		$src[] = '   extract($pk);';
		$src[] = '   return \' where '.$this->_buildSimpleConditions($pkFields,'',false).'\';';
		$src[] = '}';
		$src[] = 'public function insert ($record){';
		if($pkai !== null){
			$src[]=' if($record->'.$pkai->name.' > 0 ){';
			$src[] = '    $query = \'INSERT INTO '.$pTableRealNameEsc.' (';
			$fields = $this->_getPropertiesBy('PrimaryTable');
			list($fields, $values) = $this->_prepareValues($fields,'insertPattern', 'record->');
			$src[] = implode(',',$fields);
			$src[] = ') VALUES (';
			$src[] = implode(', ',$values);
			$src[] = ")';";
			$src[] = '}else{';
			$fields = $this->_getPropertiesBy($this->propertiesListForInsert);
		}else{
			$fields = $this->_getPropertiesBy('PrimaryTable');
		}
		if($this->_dataParser->hasEvent('insertbefore') || $this->_dataParser->hasEvent('insert')){
		  $src[] = '   jEvent::notify("daoInsertBefore", array(\'dao\'=>$this->_daoSelector, \'record\'=>$record));';
		}
		$src[] = '    $query = \'INSERT INTO '.$pTableRealNameEsc.' (';
		list($fields, $values) = $this->_prepareValues($fields,'insertPattern', 'record->');
		$src[] = implode(',',$fields);
		$src[] = ') VALUES (';
		$src[] = implode(', ',$values);
		$src[] = ")';";
		if($pkai !== null)
			$src[] = '}';
		$src[] = '   $result = $this->_conn->exec ($query);';
		if($pkai !== null){
			$src[] = '   if(!$result)';
			$src[] = '       return false;';
			$src[] = '   if($record->'.$pkai->name.' < 1 ) ';
			$src[] = $this->genUpdateAutoIncrementPK($pkai, $pTableRealName);
		}
		$fields = $this->_getPropertiesBy('FieldToUpdate');
		if(count($fields)){
			$result = array();
			foreach($fields as $id=>$prop){
				$result[]= $this->genSelectPattern($prop->selectPattern, '', $prop->fieldName, $prop->name);
			}
			$sql = 'SELECT '.(implode(', ',$result)). ' FROM '.$pTableRealNameEsc.' WHERE ';
			$sql.= $this->_buildSimpleConditions($pkFields, 'record->', false);
			$src[] = '  $query =\''.$sql.'\';';
			$src[] = '  $rs  =  $this->_conn->query ($query);';
			$src[] = '  $newrecord =  $rs->fetch ();';
			foreach($fields as $id=>$prop){
				$src[] = '  $record->'.$prop->name.' = $newrecord->'.$prop->name.';';
			}
		}
		if($this->_dataParser->hasEvent('insertafter') || $this->_dataParser->hasEvent('insert')){
			$src[] = '   jEvent::notify("daoInsertAfter", array(\'dao\'=>$this->_daoSelector, \'record\'=>$record));';
		}
		$src[] = '    return $result;';
		$src[] = '}';
		$src[] = 'public function update ($record){';
		list($fields, $values) = $this->_prepareValues($this->_getPropertiesBy('PrimaryFieldsExcludePk'),'updatePattern', 'record->');
		if(count($fields)){
			if($this->_dataParser->hasEvent('updatebefore') || $this->_dataParser->hasEvent('update')){
				$src[] = '   jEvent::notify("daoUpdateBefore", array(\'dao\'=>$this->_daoSelector, \'record\'=>$record));';
			}
			$src[] = '   $query = \'UPDATE '.$pTableRealNameEsc.' SET ';
			$sqlSet='';
			foreach($fields as $k=> $fname){
				$sqlSet.= ', '.$fname. '= '. $values[$k];
			}
			$src[] = substr($sqlSet,1);
			$sqlCondition = $this->_buildSimpleConditions($pkFields, 'record->', false);
			if($sqlCondition!='')
				$src[] = ' where '.$sqlCondition;
			$src[] = "';";
			$src[] = '   $result = $this->_conn->exec ($query);';
			$fields = $this->_getPropertiesBy('FieldToUpdateOnUpdate');
			if(count($fields)){
				$result = array();
				foreach($fields as $id=>$prop){
					$result[]= $this->genSelectPattern($prop->selectPattern, '', $prop->fieldName, $prop->name);
				}
				$sql = 'SELECT '.(implode(', ',$result)). ' FROM '.$pTableRealNameEsc.' WHERE ';
				$sql.= $this->_buildSimpleConditions($pkFields, 'record->', false);
				$src[] = '  $query =\''.$sql.'\';';
				$src[] = '  $rs  =  $this->_conn->query ($query, jDbConnection::FETCH_INTO, $record);';
				$src[] = '  $record =  $rs->fetch ();';
			}
			if($this->_dataParser->hasEvent('updateafter') || $this->_dataParser->hasEvent('update'))
				$src[] = '   jEvent::notify("daoUpdateAfter", array(\'dao\'=>$this->_daoSelector, \'record\'=>$record));';
			$src[] = '   return $result;';
			$src[] = ' }';
		}else{
			$src[] = "     throw new jException('jelix~dao.error.update.impossible',array('".jDaoCompiler::$daoId."','".jDaoCompiler::$daoPath."'));";
			$src[] = " }";
		}
		$allField = $this->_getPropertiesBy('All');
		$primaryFields = $this->_getPropertiesBy('PrimaryTable');
		$ct=null;
		foreach($this->_dataParser->getMethods() as $name=>$method){
			$defval = $method->getParametersDefaultValues();
			if(count($defval)){
				$mparam='';
				foreach($method->getParameters() as $param){
					$mparam.=', $'.$param;
					if(isset($defval[$param]))
						$mparam.='=\''.str_replace("'","\'",$defval[$param]).'\'';
				}
				$mparam = substr($mparam,1);
			}else{
				$mparam=implode(', $',$method->getParameters());
				if($mparam != '') $mparam ='$'.$mparam;
			}
			$src[] = ' function '.$method->name.' ('. $mparam.'){';
			$limit='';
			switch($method->type){
				case 'delete':
					$src[] = '    $__query = \'DELETE FROM '.$pTableRealNameEsc.' \';';
					$glueCondition =' WHERE ';
					break;
				case 'update':
					$src[] = '    $__query = \'UPDATE '.$pTableRealNameEsc.' SET ';
					$updatefields = $this->_getPropertiesBy('PrimaryFieldsExcludePk');
					$sqlSet='';
					foreach($method->getValues() as $propname=>$value){
						if($value[1]){
							foreach($method->getParameters() as $param){
								$value[0] = str_replace('$'.$param, '\'.'.$this->_preparePHPExpr('$'.$param, $updatefields[$propname],true).'.\'',$value[0]);
							}
							$sqlSet.= ', '.$this->_encloseName($updatefields[$propname]->fieldName). '= '. $value[0];
						}else{
							$sqlSet.= ', '.$this->_encloseName($updatefields[$propname]->fieldName). '= '.
								$this->_preparePHPValue($value[0],$updatefields[$propname]->datatype,false);
						}
					}
					$src[] =substr($sqlSet,1).'\';';
					$glueCondition =' WHERE ';
					break;
				case 'php':
					$src[] = $method->getBody();
					$src[] = '}';
					break;
				case 'count':
					if($method->distinct !=''){
						$prop = $this->_dataParser->getProperties();
						$prop = $prop[$method->distinct];
						$count=' DISTINCT '.$this->_encloseName($tables[$prop->table]['name']) .'.'.$this->_encloseName($prop->fieldName);
					}else{
						$count='*';
					}
					$src[] = '    $__query = \'SELECT COUNT('.$count.') as c \'.$this->_fromClause.$this->_whereClause;';
					$glueCondition =($sqlWhereClause !='' ? ' AND ':' WHERE ');
					break;
				case 'selectfirst':
				case 'select':
				default:
					if($method->distinct !=''){
						$select = '\''.$this->_getSelectClause($method->distinct).'\'';
					}else{
						$select=' $this->_selectClause';
					}
					$src[] = '    $__query = '.$select.'.$this->_fromClause.$this->_whereClause;';
					$glueCondition =($sqlWhereClause !='' ? ' AND ':' WHERE ');
					if( $method->type == 'select' &&($lim = $method->getLimit()) !==null){
						$limit=', '.$lim['offset'].', '.$lim['count'];
					}
			}
			if($method->type == 'php')
				continue;
			$cond = $method->getConditions();
			$sqlCond = '';
			if($cond !== null){
				if($method->type == 'delete' || $method->type == 'update')
					$sqlCond = $this->_buildConditions($cond, $primaryFields, $method->getParameters(), false);
				else if($method->type == 'count')
					$sqlCond = $this->_buildConditions($cond, $allField, $method->getParameters(), true);
				else
					$sqlCond = $this->_buildConditions($cond, $allField, $method->getParameters(), true, $method->getGroupBy());
			} else if(($method->type == 'select' || $method->type == 'selectfirst')){
				$sqlCond = $this->_buildConditions(null, $allField, $method->getParameters(), true, $method->getGroupBy());
			}
			if(trim($sqlCond) != '')
				$src[] = '$__query .=\''.$glueCondition.$sqlCond."';";
			switch($method->type){
				case 'delete':
				case 'update' :
					if($method->eventBeforeEnabled || $method->eventAfterEnabled){
						$src[] = '   $args = func_get_args();';
						$methname =($method->type == 'update'?'Update':'Delete');
						if($method->eventBeforeEnabled){
						$src[] = '   jEvent::notify("daoSpecific'.$methname.'Before", array(\'dao\'=>$this->_daoSelector,\'method\'=>\''.
							$method->name.'\', \'params\'=>$args));';
						}
						if($method->eventAfterEnabled){
							$src[] = '   $result = $this->_conn->exec ($__query);';
							$src[] = '   jEvent::notify("daoSpecific'.$methname.'After", array(\'dao\'=>$this->_daoSelector,\'method\'=>\''.
								$method->name.'\', \'params\'=>$args));';
							$src[] = '   return $result;';
						} else{
							$src[] = '    return $this->_conn->exec ($__query);';
						}
					} else{
						$src[] = '    return $this->_conn->exec ($__query);';
					}
					break;
				case 'count':
					$src[] = '    $__rs = $this->_conn->query($__query);';
					$src[] = '    $__res = $__rs->fetch();';
					$src[] = '    return intval($__res->c);';
					break;
				case 'selectfirst':
					$src[] = '    $__rs = $this->_conn->limitQuery($__query,0,1);';
					$src[] = '    $__rs->setFetchMode(8,\''.$this->_DaoRecordClassName.'\');';
					$src[] = '    return $__rs->fetch();';
					break;
				case 'select':
				default:
					if($limit)
						$src[] = '    $__rs = $this->_conn->limitQuery($__query'.$limit.');';
					else
						$src[] = '    $__rs = $this->_conn->query($__query);';
					$src[] = '    $__rs->setFetchMode(8,\''.$this->_DaoRecordClassName.'\');';
					$src[] = '    return $__rs;';
			}
			$src[] = '}';
		}
		$src[] = '}';
		return implode("\n",$src);
	}
	protected function _getFromClause(){
		$tables = $this->_dataParser->getTables();
		foreach($tables as $table_name => $table){
			$tables[$table_name]['realname'] = '\'.$this->_conn->prefixTable(\''.$table['realname'].'\').\'';
		}
		$primarytable = $tables[$this->_dataParser->getPrimaryTable()];
		$ptrealname = $this->_encloseName($primarytable['realname']);
		$ptname = $this->_encloseName($primarytable['name']);
		list($sqlFrom, $sqlWhere) = $this->genOuterJoins($tables, $ptname);
		if($primarytable['name']!=$primarytable['realname'])
			$sqlFrom =$ptrealname.$this->aliasWord.$ptname.$sqlFrom;
		else
			$sqlFrom =$ptrealname.$sqlFrom;
		foreach($this->_dataParser->getInnerJoins() as $tablejoin){
			$table= $tables[$tablejoin];
			$tablename = $this->_encloseName($table['name']);
			if($table['name']!=$table['realname'])
				$sqlFrom .=', '.$this->_encloseName($table['realname']).$this->aliasWord.$tablename;
			else
				$sqlFrom .=', '.$this->_encloseName($table['realname']);
			foreach($table['fk'] as $k => $fk){
				$sqlWhere.=' AND '.$ptname.'.'.$this->_encloseName($fk).'='.$tablename.'.'.$this->_encloseName($table['pk'][$k]);
			}
		}
		$sqlWhere=($sqlWhere !='') ? ' WHERE '.substr($sqlWhere,4) :'';
		return array(' FROM '.$sqlFrom,$sqlWhere);
	}
	protected function genOuterJoins(&$tables, $primaryTableName){
		$sqlFrom = '';
		foreach($this->_dataParser->getOuterJoins() as $tablejoin){
			$table= $tables[$tablejoin[0]];
			$tablename = $this->_encloseName($table['name']);
			if($table['name']!=$table['realname'])
				$r =$this->_encloseName($table['realname']).$this->aliasWord.$tablename;
			else
				$r =$this->_encloseName($table['realname']);
			$fieldjoin='';
			foreach($table['fk'] as $k => $fk){
				$fieldjoin.=' AND '.$primaryTableName.'.'.$this->_encloseName($fk).'='.$tablename.'.'.$this->_encloseName($table['pk'][$k]);
			}
			$fieldjoin=substr($fieldjoin,4);
			if($tablejoin[1] == 0){
				$sqlFrom.=' LEFT JOIN '.$r.' ON ('.$fieldjoin.')';
			}elseif($tablejoin[1] == 1){
				$sqlFrom.=' RIGHT JOIN '.$r.' ON ('.$fieldjoin.')';
			}
		}
		return array($sqlFrom, '');
	}
	protected function _getSelectClause($distinct=false){
		$result = array();
		$tables = $this->_dataParser->getTables();
		foreach($this->_dataParser->getProperties() as $id=>$prop){
			$table = $this->_encloseName($tables[$prop->table]['name']) .'.';
			if($prop->selectPattern !=''){
				$result[]= $this->genSelectPattern($prop->selectPattern, $table, $prop->fieldName, $prop->name);
			}
		}
		return 'SELECT '.($distinct?'DISTINCT ':'').(implode(', ',$result));
	}
	protected function genSelectPattern($pattern, $table, $fieldname, $propname){
		if($pattern =='%s'){
			if($fieldname != $propname){
				$field = $table.$this->_encloseName($fieldname).' as '.$this->_encloseName($propname);
			}else{
				$field = $table.$this->_encloseName($fieldname);
			}
		}else{
			$field = str_replace(array("'", "%s"), array("\\'",$table.$this->_encloseName($fieldname)),$pattern).' as '.$this->_encloseName($propname);
		}
		return $field;
	}
	protected function _writeFieldsInfoWith($info, $start = '', $end='', $beetween = '', $using = null){
		$result = array();
		if($using === null){
			$using = $this->_dataParser->getProperties();
		}
		foreach($using as $id=>$field){
			$result[] = $start . $field->$info . $end;
		}
		return implode($beetween,$result);;
	}
	protected function _writeFieldNamesWith($start = '', $end='', $beetween = '', $using = null){
		return $this->_writeFieldsInfoWith('name', $start, $end, $beetween, $using);
	}
	protected function _getPropertiesBy($captureMethod){
		$captureMethod = '_capture'.$captureMethod;
		$result = array();
		foreach($this->_dataParser->getProperties() as $field){
			if( $this->$captureMethod($field)){
				$result[$field->name] = $field;
			}
		}
		return $result;
	}
	protected function _capturePkFields(&$field){
		return($field->table == $this->_dataParser->getPrimaryTable()) && $field->isPK;
	}
	protected function _capturePrimaryFieldsExcludeAutoIncrement(&$field){
		return($field->table == $this->_dataParser->getPrimaryTable()) &&
		($field->datatype != 'autoincrement') &&($field->datatype != 'bigautoincrement');
	}
	protected function _capturePrimaryFieldsExcludePk(&$field){
		return($field->table == $this->_dataParser->getPrimaryTable()) && !$field->isPK;
	}
	protected function _capturePrimaryTable(&$field){
		return($field->table == $this->_dataParser->getPrimaryTable());
	}
	protected function _captureAll(&$field){
		return true;
	}
	protected function _captureFieldToUpdate(&$field){
		return($field->table == $this->_dataParser->getPrimaryTable()
				&& !$field->isPK
				&& !$field->isFK
				&&( $field->datatype == 'autoincrement' || $field->datatype == 'bigautoincrement'
					||($field->insertPattern != '%s' && $field->selectPattern != '')));
	}
	protected function _captureFieldToUpdateOnUpdate(&$field){
		return($field->table == $this->_dataParser->getPrimaryTable()
				&& !$field->isPK
				&& !$field->isFK
				&&( $field->datatype == 'autoincrement' || $field->datatype == 'bigautoincrement'
					||($field->updatePattern != '%s' && $field->selectPattern != '')));
	}
	protected function _getAutoIncrementPKField($using = null){
		if($using === null){
			$using = $this->_dataParser->getProperties();
		}
		$tb = $this->_dataParser->getTables();
		$tb = $tb[$this->_dataParser->getPrimaryTable()]['realname'];
		foreach($using as $id=>$field){
			if(!$field->isPK)
				continue;
			if($field->datatype == 'autoincrement' || $field->datatype == 'bigautoincrement'){
				return $field;
			}
		}
		return null;
	}
	protected function _buildSimpleConditions(&$fields, $fieldPrefix='', $forSelect=true){
		$r = ' ';
		$first = true;
		foreach($fields as $field){
			if(!$first){
				$r .= ' AND ';
			}else{
				$first = false;
			}
			if($forSelect){
				$condition = $this->_encloseName($field->table).'.'.$this->_encloseName($field->fieldName);
			}else{
				$condition = $this->_encloseName($field->fieldName);
			}
			$var = '$'.$fieldPrefix.$field->name;
			$value = $this->_preparePHPExpr($var, $field, !$field->requiredInConditions, '=');
			$r .= $condition.'\'.'.$value.'.\'';
		}
		return $r;
	}
	protected function _prepareValues($fieldList, $pattern='', $prefixfield=''){
		$values = $fields = array();
		foreach((array)$fieldList as $fieldName=>$field){
			if($pattern != '' && $field->$pattern == ''){
				continue;
			}
			$value = $this->_preparePHPExpr('$'.$prefixfield.$fieldName, $field, true);
			if($pattern != ''){
				$values[$field->name] = sprintf($field->$pattern,'\'.'.$value.'.\'');
			}else{
				$values[$field->name] = '\'.'.$value.'.\'';
			}
			$fields[$field->name] = $this->_encloseName($field->fieldName);
		}
		return array($fields, $values);
	}
	protected function _buildConditions($cond, $fields, $params=array(), $withPrefix=true, $groupby=null){
		if($cond)
			$sql = $this->_buildSQLCondition($cond->condition, $fields, $params, $withPrefix, true);
		else
			$sql = '';
		if($groupby && count($groupby)){
			if(trim($sql) ==''){
				$sql = ' 1=1 ';
			}
			foreach($groupby as $k=>$f){
				if($withPrefix)
					$groupby[$k]= $this->_encloseName($fields[$f]->table).'.'.$this->_encloseName($fields[$f]->fieldName);
				else
					$groupby[$k]= $this->_encloseName($fields[$f]->fieldName);
			}
			$sql .= ' GROUP BY '.implode(', ', $groupby);
		}
		$order = array();
		foreach($cond->order as $name => $way){
			$ord='';
			if(isset($fields[$name])){
				if($withPrefix)
					$ord = $this->_encloseName($fields[$name]->table).'.'.$this->_encloseName($fields[$name]->fieldName);
				else
					$ord = $this->_encloseName($fields[$name]->fieldName);
			}elseif($name[0] == '$'){
				$ord = '\'.'.$name.'.\'';
			}else{
				continue;
			}
			if($way[0] == '$'){
				$order[]=$ord.' \'.( strtolower('.$way.') ==\'asc\'?\'asc\':\'desc\').\'';
			}else{
				$order[]=$ord.' '.$way;
			}
		}
		if(count($order) > 0){
			if(trim($sql) ==''){
				$sql = ' 1=1 ';
			}
			$sql.=' ORDER BY '.implode(', ', $order);
		}
		return $sql;
	}
	protected function _buildSQLCondition($condition, $fields, $params, $withPrefix, $principal=false){
		$r = ' ';
		$first = true;
		foreach($condition->conditions as $cond){
			if(!$first){
				$r .= ' '.$condition->glueOp.' ';
			}
			$first = false;
			$prop = $fields[$cond['field_id']];
			if($withPrefix){
				$f = $this->_encloseName($prop->table).'.'.$this->_encloseName($prop->fieldName);
			}else{
				$f = $this->_encloseName($prop->fieldName);
			}
			$r .= $f.' ';
			if($cond['operator'] == 'IN' || $cond['operator'] == 'NOT IN'){
				if($cond['isExpr']){
					$phpvalue= $this->_preparePHPExpr('$__e', $prop, false);
					if(strpos($phpvalue,'$this->_conn->quote')===0){
						$phpvalue = str_replace('$this->_conn->quote(',"'\''.str_replace('\\'','\\\\\\'',",$phpvalue).".'\''";
						$phpvalue = str_replace('\\','\\\\', $phpvalue);
						$phpvalue = str_replace('\'','\\\'', $phpvalue);
					}
					$phpvalue = 'implode(\',\', array_map( create_function(\'$__e\',\'return '.$phpvalue.';\'), '.$cond['value'].'))';
					$value= '(\'.'.$phpvalue.'.\')';
				}else{
					$value= '('.$cond['value'].')';
				}
				$r.=$cond['operator'].' '.$value;
			}elseif($cond['operator'] == 'IS NULL' || $cond['operator'] == 'IS NOT NULL'){
				$r.=$cond['operator'].' ';
			}else{
				if($cond['isExpr']){
					$value=str_replace("'","\\'",$cond['value']);
					if(strpos($value, '$') === 0){
						$value = '\'.'.$this->_preparePHPExpr($value, $prop, !$prop->requiredInConditions,$cond['operator']).'.\'';
					}else{
						foreach($params as $param){
							$value = str_replace('$'.$param, '\'.'.$this->_preparePHPExpr('$'.$param, $prop, !$prop->requiredInConditions).'.\'',$value);
						}
						$value = $cond['operator'].' '.$value;
					}
				} else{
					$value = $cond['operator'].' ';
					if($cond['operator'] == 'LIKE' || $cond['operator'] == 'NOT LIKE'){
						$value .= $this->_preparePHPValue($cond['value'], 'string', false);
					} else{
						$value .= $this->_preparePHPValue($cond['value'], $prop->datatype, false);
					}
				}
				$r.=$value;
			}
		}
		foreach($condition->group as $conditionDetail){
			if(!$first){
				$r .= ' '.$condition->glueOp.' ';
			}
			$r .= $this->_buildSQLCondition($conditionDetail, $fields, $params, $withPrefix);
			$first=false;
		}
		if(strlen(trim($r)) > 0 &&(!$principal ||($principal && $condition->glueOp != 'AND'))){
			$r = '('.$r.')';
		}
		return $r;
	}
	protected function _preparePHPValue($value, $fieldType, $checknull=true){
		if($checknull){
			if($value == 'null' || $value == 'NULL' || $value === null)
				return 'NULL';
		}
		switch(strtolower($fieldType)){
			case 'int':
			case 'integer':
			case 'autoincrement':
				return intval($value);
			case 'double':
			case 'float':
				return doubleval($value);
			case 'numeric':
			case 'bigautoincrement':
				if(is_numeric($value))
					return $value;
				else
					return intval($value);
			case 'boolean':
				return $this->getBooleanValue($value);
			default:
				if(strpos($value,"'") !== false){
					return '\'.$this->_conn->quote(\''.str_replace('\'','\\\'',$value).'\').\'';
				}else{
					return "\\'".$value."\\'";
				}
		}
	}
	protected function _preparePHPExpr($expr, $field, $checknull=true, $forCondition=''){
		$opnull = $opval = '';
		if($checknull && $forCondition != ''){
			if($forCondition == '=')
				$opnull = 'IS ';
			elseif($forCondition == '<>')
				$opnull = 'IS NOT ';
			else
				$checknull=false;
		}
		$type = '';
		if($forCondition != 'LIKE' && $forCondition != 'NOT LIKE')
			$type = strtolower($field->datatype);
		if($forCondition != '')
			$forCondition = '\' '.$forCondition.' \'.';
		switch($type){
			case 'int':
			case 'integer':
				if($checknull){
					$expr= '('.$expr.' === null ? \''.$opnull.'NULL\' : '.$forCondition.'intval('.$expr.'))';
				}else{
					$expr= $forCondition.'intval('.$expr.')';
				}
				break;
			case 'autoincrement':
				$expr= $forCondition.'intval('.$expr.')';
				break;
			case 'double':
			case 'float':
				if($checknull){
					$expr= '('.$expr.' === null ? \''.$opnull.'NULL\' : '.$forCondition.'doubleval('.$expr.'))';
				}else{
					$expr= $forCondition.'doubleval('.$expr.')';
				}
				break;
			case 'numeric':
				if($checknull){
					$expr='('.$expr.' === null ? \''.$opnull.'NULL\' : '.$forCondition.'(is_numeric ('.$expr.') ? '.$expr.' : intval('.$expr.')))';
				}else{
					$expr=$forCondition.'(is_numeric ('.$expr.') ? '.$expr.' : intval('.$expr.'))';
				}
				break;
			case 'bigautoincrement':
				$expr=$forCondition.'(is_numeric ('.$expr.') ? '.$expr.' : intval('.$expr.'))';
				break;
			case 'boolean':
				if($checknull){
					$expr= '('.$expr.' === null ? \''.$opnull.'NULL\' : '.$forCondition.'$this->_prepareValue('.$expr.', "boolean", true))';
				}else{
					$expr= $forCondition.'$this->_prepareValue('.$expr.', "boolean", true)';
				}
				break;
			default:
				if($checknull){
					$expr= '('.$expr.' === null ? \''.$opnull.'NULL\' : '.$forCondition.'$this->_conn->quote('.$expr.',false))';
				}else{
					$expr= $forCondition.'$this->_conn->quote('.$expr.')';
				}
		}
		return $expr;
	}
	protected function _encloseName($name){
		return $name;
	}
	protected function genUpdateAutoIncrementPK($pkai, $pTableRealName){
		return '       $record->'.$pkai->name.'= $this->_conn->lastInsertId();';
	}
	protected function getBooleanValue($value){
		return(strtolower($value)=='true'|| $value =='1'|| $value=='t'?$this->trueValue:$this->falseValue);
	}
}
class jDaoCompiler  implements jISimpleCompiler{
	static public $daoId = '';
	static public $daoPath = '';
	static public $dbType='';
	public function compile($selector){
		jDaoCompiler::$daoId = $selector->toString();
		jDaoCompiler::$daoPath = $selector->getPath();
		jDaoCompiler::$dbType = $selector->driver;
		$doc = new DOMDocument();
		if(!$doc->load(jDaoCompiler::$daoPath)){
			throw new jException('jelix~daoxml.file.unknow', jDaoCompiler::$daoPath);
		}
		if($doc->documentElement->namespaceURI != JELIX_NAMESPACE_BASE.'dao/1.0'){
			throw new jException('jelix~daoxml.namespace.wrong',array(jDaoCompiler::$daoPath, $doc->namespaceURI));
		}
		$parser = new jDaoParser();
		$parser->parse(simplexml_import_dom($doc));
		global $gJConfig;
		require_once($gJConfig->_pluginsPathList_db[$selector->driver].$selector->driver.'.daobuilder.php');
		$class = $selector->driver.'DaoBuilder';
		$generator = new $class($selector->getDaoClass(), $selector->getDaoRecordClass(), $parser);
		$compiled = '<?php '.$generator->buildClasses()."\n?>";
		jFile::write($selector->getCompiledFilePath(), $compiled);
		return true;
	}
}
class jDaoXmlException extends jException{
	public function __construct($localekey, $localeParams=array()){
		$localekey= 'jelix~daoxml.'.$localekey;
		$arg=array(jDaoCompiler::$daoId, jDaoCompiler::$daoPath);
		if(is_array($localeParams)){
			$arg=array_merge($arg, $localeParams);
		}else{
			$arg[]=$localeParams;
		}
		parent::__construct($localekey, $arg);
	}
}