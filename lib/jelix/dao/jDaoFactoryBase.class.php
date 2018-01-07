<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
 * @package     jelix
 * @subpackage  dao
 * @author      Laurent Jouanneau
 * @contributor Loic Mathaud
 * @contributor Julien Issler
 * @contributor Thomas
 * @contributor Yoan Blanc
 * @contributor Mickael Fradin
 * @contributor Christophe Thiriot
 * @contributor Yannick Le Guédart
 * @contributor Steven Jehannet, Didier Huguet
 * @contributor Philippe Villiers
 * @copyright   2005-2011 Laurent Jouanneau
 * @copyright   2007 Loic Mathaud
 * @copyright   2007-2009 Julien Issler
 * @copyright   2008 Thomas
 * @copyright   2008 Yoan Blanc
 * @copyright   2009 Mickael Fradin
 * @copyright   2009 Christophe Thiriot
 * @copyright   2010 Yannick Le Guédart
 * @copyright   2010 Steven Jehannet, 2010 Didier Huguet
 * @copyright   2013 Philippe Villiers
 * @link        http://www.jelix.org
 * @licence     http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
 */
abstract class jDaoFactoryBase{
	protected $_tables;
	protected $_primaryTable;
	protected $_conn;
	protected $_selectClause;
	protected $_fromClause;
	protected $_whereClause;
	protected $_DaoRecordClassName;
	protected $_daoSelector;
	protected $_deleteBeforeEvent=false;
	protected $_deleteAfterEvent=false;
	protected $_deleteByBeforeEvent=false;
	protected $_deleteByAfterEvent=false;
	protected $trueValue=1;
	protected $falseValue=0;
	function  __construct($conn){
		$this->_conn=$conn;
		if($this->_conn->hasTablePrefix()){
			foreach($this->_tables as $table_name=>$table){
				$this->_tables[$table_name]['realname']=$this->_conn->prefixTable($table['realname']);
			}
		}
	}
	public function getTables(){
		return $this->_tables;
	}
	public function getPrimaryTable(){
		return $this->_primaryTable;
	}
	public function getProperties(){return static::$_properties;}
	public function getPrimaryKeyNames(){return static::$_pkFields;}
	public function findAll(){
		$rs=$this->_conn->query($this->_selectClause.$this->_fromClause.$this->_whereClause);
		$this->finishInitResultSet($rs);
		return $rs;
	}
	public function countAll(){
		$oracle=($this->_conn->dbms=='oci');
		if(!$oracle){
			$query='SELECT COUNT(*) as c '.$this->_fromClause.$this->_whereClause;
		}else{
			$query='SELECT COUNT(*) as "c" '.$this->_fromClause.$this->_whereClause;
		}
		$rs=$this->_conn->query($query);
		$res=$rs->fetch();
		return intval($res->c);
	}
	final public function get(){
		$args=func_get_args();
		if(count($args)==1&&is_array($args[0])){
			$args=$args[0];
		}
		$keys=@array_combine(static::$_pkFields,$args);
		if($keys===false){
			throw new jException('jelix~dao.error.keys.missing');
		}
		$q=$this->_selectClause.$this->_fromClause.$this->_whereClause;
		$q.=$this->_getPkWhereClauseForSelect($keys);
		$rs=$this->_conn->query($q);
		$this->finishInitResultSet($rs);
		$record=$rs->fetch();
		return $record;
	}
	final public function delete(){
		$args=func_get_args();
		if(count($args)==1&&is_array($args[0])){
			$args=$args[0];
		}
		$keys=array_combine(static::$_pkFields,$args);
		if($keys===false){
			throw new jException('jelix~dao.error.keys.missing');
		}
		$q='DELETE FROM '.$this->_conn->encloseName($this->_tables[$this->_primaryTable]['realname']).' ';
		$q.=$this->_getPkWhereClauseForNonSelect($keys);
		if($this->_deleteBeforeEvent){
			jEvent::notify("daoDeleteBefore",array('dao'=>$this->_daoSelector,'keys'=>$keys));
		}
		$result=$this->_conn->exec($q);
		if($this->_deleteAfterEvent){
			jEvent::notify("daoDeleteAfter",array('dao'=>$this->_daoSelector,'keys'=>$keys,'result'=>$result));
		}
		return $result;
	}
	abstract public function insert($record);
	abstract public function update($record);
	final public function findBy($searchcond,$limitOffset=0,$limitCount=null){
		$query=$this->_selectClause.$this->_fromClause.$this->_whereClause;
		if($searchcond->hasConditions()){
			$query.=($this->_whereClause!='' ? ' AND ' : ' WHERE ');
			$query.=$this->_createConditionsClause($searchcond);
		}
		$query.=$this->_createGroupClause($searchcond);
		$query.=$this->_createOrderClause($searchcond);
		if($limitCount!==null){
			$rs=$this->_conn->limitQuery($query,$limitOffset,$limitCount);
		}else{
			$rs=$this->_conn->query($query);
		}
		$this->finishInitResultSet($rs);
		return $rs;
	}
	final public function countBy($searchcond,$distinct=null){
		$count='*';
		$sqlite=false;
		if($distinct!==null){
			$props=static::$_properties;
			if(isset($props[$distinct]))
				$count='DISTINCT '.$this->_tables[$props[$distinct]['table']]['name'].'.'.$props[$distinct]['fieldName'];
			$sqlite=($this->_conn->dbms=='sqlite');
		}
		$oracle=($this->_conn->dbms=='oci');
		if(!$sqlite){
			if(!$oracle){
				$query='SELECT COUNT('.$count.') as c '.$this->_fromClause.$this->_whereClause;
			}else{
				$query='SELECT COUNT('.$count.') as "c" '.$this->_fromClause.$this->_whereClause;
			}
		}else{
			$query='SELECT COUNT(*) as c FROM (SELECT '.$count.' '.$this->_fromClause.$this->_whereClause;
		}
		if($searchcond->hasConditions()){
			$query.=($this->_whereClause!='' ? ' AND ' : ' WHERE ');
			$query.=$this->_createConditionsClause($searchcond);
		}
		if($sqlite)$query.=')';
		$rs=$this->_conn->query($query);
		$res=$rs->fetch();
		return intval($res->c);
	}
	final public function deleteBy($searchcond){
		if($searchcond->isEmpty()){
			return 0;
		}
		$query='DELETE FROM '.$this->_conn->encloseName($this->_tables[$this->_primaryTable]['realname']).' WHERE ';
		$query.=$this->_createConditionsClause($searchcond,false);
		if($this->_deleteByBeforeEvent){
			jEvent::notify("daoDeleteByBefore",array('dao'=>$this->_daoSelector,'criterias'=>$searchcond));
		}
		$result=$this->_conn->exec($query);
		if($this->_deleteByAfterEvent){
			jEvent::notify("daoDeleteByAfter",array('dao'=>$this->_daoSelector,'criterias'=>$searchcond,'result'=>$result));
		}
		return $result;
	}
	abstract protected function _getPkWhereClauseForSelect($pk);
	abstract protected function _getPkWhereClauseForNonSelect($pk);
	final protected function _createConditionsClause($daocond,$forSelect=true){
		return $this->_generateCondition($daocond->condition,static::$_properties,$forSelect,true);
	}
	final protected function _createOrderClause($daocond){
		$order=array();
		$isOci=($this->_conn->dbms=='oci');
		foreach($daocond->order as $name=>$way){
			if(isset(static::$_properties[$name])){
				if($isOci){
					$order[]=$this->_conn->encloseName(static::$_properties[$name]['table']).'.'.$this->_conn->encloseName(static::$_properties[$name]['fieldName']).' '.$way;
				}
				else{
					$order[]=$this->_conn->encloseName(static::$_properties[$name]['name']).' '.$way;
				}
			}
		}
		if(count($order)){
			return ' ORDER BY '.implode(', ',$order);
		}
		return '';
	}
	final protected function _createGroupClause($daocond){
		$group=array();
		foreach($daocond->group as $name){
			if(isset(static::$_properties[$name]))
				$group[]=$this->_conn->encloseName($name);
		}
		if(count($group)){
			return ' GROUP BY '.implode(', ',$group);
		}
		return '';
	}
	final protected function _generateCondition($condition,&$fields,$forSelect,$principal=true){
		$r=' ';
		$notfirst=false;
		foreach($condition->conditions as $cond){
			if($notfirst){
				$r.=' '.$condition->glueOp.' ';
			}else
				$notfirst=true;
			if(!isset($fields[$cond['field_id']])){
				throw new jException('jelix~dao.error.property.unknown',$cond['field_id']);
			}
			$prop=$fields[$cond['field_id']];
			$pattern=isset($cond['field_pattern'])? $cond['field_pattern'] : '%s';
			if($forSelect){
				if($pattern=='%s'||empty($pattern)){
					$prefixNoCondition=$this->_conn->encloseName($this->_tables[$prop['table']]['name']).'.'.$this->_conn->encloseName($prop['fieldName']);
				}else{
					$prefixNoCondition=str_replace("%s",$this->_conn->encloseName($this->_tables[$prop['table']]['name']).'.'.$this->_conn->encloseName($prop['fieldName']),$pattern);
				}
			}
			else{
				if($pattern=='%s'||empty($pattern)){
					$prefixNoCondition=$this->_conn->encloseName($prop['fieldName']);
				}else{
					$prefixNoCondition=str_replace("%s",$this->_conn->encloseName($prop['fieldName']),$pattern);
				}
			}
			$op=strtoupper($cond['operator']);
			$prefix=$prefixNoCondition.' '.$op.' ';
			if($op=='IN'||$op=='NOT IN'){
				if(is_array($cond['value'])){
					$values=array();
					foreach($cond['value'] as $value)
						$values[]=$this->_prepareValue($value,$prop['unifiedType']);
					$values=join(',',$values);
				}
				else
					$values=$cond['value'];
				$r.=$prefix.'('.$values.')';
			}
			else{
				if($op=='LIKE'||$op=='NOT LIKE'){
					$type='varchar';
				}
				else{
					$type=$prop['unifiedType'];
				}
				if(!is_array($cond['value'])){
					$value=$this->_prepareValue($cond['value'],$type);
					if($cond['value']===null){
						if(in_array($op,array('=','LIKE','IS','IS NULL'))){
							$r.=$prefixNoCondition.' IS NULL';
						}else{
							$r.=$prefixNoCondition.' IS NOT NULL';
						}
					}else{
						$r.=$prefix.$value;
					}
				}else{
					$r.=' ( ';
					$firstCV=true;
					foreach($cond['value'] as $conditionValue){
						if(!$firstCV){
							$r.=' or ';
						}
						$value=$this->_prepareValue($conditionValue,$type);
						if($conditionValue===null){
							if(in_array($op,array('=','LIKE','IS','IS NULL'))){
								$r.=$prefixNoCondition.' IS NULL';
							}else{
								$r.=$prefixNoCondition.' IS NOT NULL';
							}
						}else{
							$r.=$prefix.$value;
						}
						$firstCV=false;
					}
					$r.=' ) ';
				}
			}
		}
		foreach($condition->group as $conditionDetail){
			if($notfirst){
				$r.=' '.$condition->glueOp.' ';
			}else{
				$notfirst=true;
			}
			$r.=$this->_generateCondition($conditionDetail,$fields,$forSelect,false);
		}
		if(strlen(trim($r))> 0&&!$principal){
			$r='('.$r.')';
		}
		return $r;
	}
	final protected function _prepareValue($value,$fieldType,$notNull=false){
		if(!$notNull&&$value===null)
			return 'NULL';
		switch(strtolower($fieldType)){
			case 'integer':
				return intval($value);
			case 'double':
			case 'float':
			case 'numeric':
			case 'decimal':
				return jDb::floatToStr($value);
			case 'boolean':
				if($value===true||strtolower($value)=='true'||intval($value)===1||$value==='t'||$value==='on')
					return $this->trueValue;
				else
					return $this->falseValue;
				break;
			default:
				return $this->_conn->quote2($value,true,($fieldType=='binary'));
		}
	}
	protected function finishInitResultSet($rs){
		$rs->setFetchMode(8,$this->_DaoRecordClassName);
	}
	protected function _callbackQuote($value){
		return $this->_conn->quote2($value);
	}
	protected function _callbackQuoteBin($value){
		return $this->_conn->quote2($value,true,true);
	}
	protected function _callbackBool($value){
		if($value===true||strtolower($value)=='true'||intval($value)===1||$value==='t'||$value==='on')
			return $this->trueValue;
		else
			return $this->falseValue;
	}
}
