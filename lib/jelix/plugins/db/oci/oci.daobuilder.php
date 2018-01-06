<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package    jelix
* @subpackage db_driver
* @author     Laurent Jouanneau
* @contributor Gwendal Jouannic
* @contributor Philippe Villiers
* @copyright  2007-2009 Laurent Jouanneau, 2013 Philippe Villiers
* @link      http://www.jelix.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/
class ociDaoBuilder extends jDaoGenerator{
	protected $aliasWord=' ';
	protected $propertiesListForInsert='PrimaryFieldsExcludeAutoIncrement';
	protected function buildOuterJoins(&$tables,$primaryTableName){
		$sqlFrom='';
		$sqlWhere='';
		foreach($this->_dataParser->getOuterJoins()as $tablejoin){
			$table=$tables[$tablejoin[0]];
			$tablename=$this->_encloseName($table['name']);
			if($table['name']!=$table['realname'])
				$r=$this->_encloseName($table['realname']).' '.$tablename;
			else
				$r=$this->_encloseName($table['realname']);
			$fieldjoin='';
			if($tablejoin[1]==0){
				$operand='=';$opafter='(+)';
			}elseif($tablejoin[1]==1){
				$operand='(+)=';$opafter='';
			}
			foreach($table['fk'] as $k=>$fk){
				$fieldjoin.=' AND '.$primaryTableName.'.'.$this->_encloseName($fk).$operand.$tablename.'.'.$this->_encloseName($table['pk'][$k]).$opafter;
			}
			$sqlFrom.=', '.$r;
			$sqlWhere.=$fieldjoin;
		}
		return array($sqlFrom,$sqlWhere);
	}
	protected function buildSelectPattern($pattern,$table,$fieldname,$propname){
		if($pattern=='%s'){
			if($fieldname!=$propname){
				$field=$table.$this->_encloseName($fieldname).' "'.$propname.'"';
			}else{
				$field=$table.$this->_encloseName($fieldname);
			}
		}else{
			$field=str_replace(array("'","%s"),array("\\'",$table.$this->_encloseName($fieldname)),$pattern).' "'.$propname.'"';
		}
		return $field;
	}
	protected function buildUpdateAutoIncrementPK($pkai){
		return '          $record->'.$pkai->name.'= $this->_conn->query(\'SELECT '.$pkai->sequenceName.'.currval as "'.$pkai->name.'" from dual\')->fetch()->'.$pkai->name.';';
	}
	protected function buildInsertMethod($pkFields){
		$pkai=$this->getAutoIncrementPKField();
		if(is_object($pkai)&&$pkai->autoIncrement&&!$pkai->sequenceName){
			throw new Exception('Please don\'t use auto-increment and use a sequence instead for the table ' .
										$this->tableRealName);
		}
		$src=array();
		$src[]='public function insert ($record){';
		if($this->_dataParser->hasEvent('insertbefore')||$this->_dataParser->hasEvent('insert')){
			$src[]='   jEvent::notify("daoInsertBefore", array(\'dao\'=>$this->_daoSelector, \'record\'=>$record));';
		}
		$src[]='    $query = \'INSERT INTO '.$this->tableRealNameEsc.' (';
		$fields=$this->_getPropertiesBy('PrimaryTable');
		list($fields,$values)=$this->_prepareValues($fields,'insertPattern','record->');
		$fieldsObj=$this->_getPropertiesBy('PrimaryTable');
		$binds=array();
		$returningInto=array();
		$returningBind='';
		foreach($fieldsObj as $k=>$fieldData){
			if(in_array($fieldData->name,$fields)){
				if($fieldData->datatype=='clob'||$fieldData->datatype=='blob'){
					$values[$k]=':' . $fieldData->fieldName;
					$binds[$fieldData->fieldName]=$fieldData->name;
				}else{
					if($fieldData->isPK){
						switch($fieldData->datatype){
							case 'int':
							case 'integer':
							case 'tinyint':
							case 'smallint':
							case 'mediumint':
							case 'bigint':
							case 'number':
								$returningInto['field'][]=$fieldData->fieldName;
								$returningInto['bind'][]=':' . $fieldData->name;
								$returningBind.='    $prep->bindParam(\':' . $fieldData->name . '\', $record->' . $fieldData->name . ', SQLT_INT, -1);' . "\n";
							break;
						}
					}else{
						switch($fieldData->datatype){
							case 'varchar':
							case 'varchar2':
							case 'nvarchar2':
							case 'character':
							case 'character varying':
							case 'char':
							case 'nchar':
							case 'name':
							case 'longvarchar':
							case 'string':
								$values[$k]=':' . $fieldData->fieldName;
								$binds[$fieldData->fieldName]=$fieldData->name;
						}
					}
				}
			}
		}
		$src[]=implode(',',$fields);
		$src[]=') VALUES (';
		$src[]=implode(', ',$values);
		$src[]=")";
		if(!empty($returningInto)){
			$src[]='    RETURNING ' . implode(',',$returningInto['field']). ' INTO ' . implode(',',$returningInto['bind']);
		}
		$src[]="';";
		if(!empty($binds)||!empty($returningInto)){
			$src[]='   $prep = $this->_conn->prepare ($query);';
			if(!empty($binds)){
				foreach($binds as $variable=>$name){
					$src[]='   $prep->bindParam(\':'.$variable.'\', $record->'.$name.');';
				}
			}
			if(!empty($returningInto)){
				$src[]=$returningBind;
			}
			$src[]='   $result = $prep->execute();';
		}else{
			$src[]='   $result = $this->_conn->exec ($query);';
		}
		if($pkai!==null){
			$src[]='   if (!$result) {';
			$src[]='       return false;';
			$src[]='   }';
			$src[]='   if ($record->'.$pkai->name.' < 1 ) {';
			$src[]=$this->buildUpdateAutoIncrementPK($pkai);
			$src[]='   }';
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
		$fieldsObj=$this->_getPropertiesBy('PrimaryFieldsExcludePk');
		if(count($fields)){
			if($this->_dataParser->hasEvent('updatebefore')||$this->_dataParser->hasEvent('update')){
				$src[]='   jEvent::notify("daoUpdateBefore", array(\'dao\'=>$this->_daoSelector, \'record\'=>$record));';
			}
			$src[]='   $query = \'UPDATE '.$this->tableRealNameEsc.' SET ';
			$sqlSet='';
			$binds=array();
			foreach($fieldsObj as $k=>$fieldData){
				if($fieldData->updatePattern!=''){
					switch($fieldData->datatype){
						case 'clob':
						case 'blob':
						case 'varchar':
						case 'varchar2':
						case 'nvarchar2':
						case 'character':
						case 'character varying':
						case 'char':
						case 'nchar':
						case 'name':
						case 'longvarchar':
						case 'string':
							$values[$k]=':' . $fieldData->fieldName;
							$binds[$fieldData->fieldName]=$fieldData->name;
							$sqlSet.=', '.$fieldData->fieldName. '= :' . $fieldData->fieldName;
						break;
						default:
							$sqlSet.=', '.$fieldData->fieldName. '= '. $values[$k];
					}
				}
			}
			$src[]=substr($sqlSet,1);
			$sqlCondition=$this->buildSimpleConditions($pkFields,'record->',false);
			if($sqlCondition!=''){
				$src[]=' where '.$sqlCondition;
			}
			$src[]="';";
			if(!empty($binds)){
				$src[]='   $prep = $this->_conn->prepare ($query);';
				foreach($binds as $variable=>$name){
					$src[]='   $prep->bindParam(\':'.$variable.'\', $record->'.$name.');';
				}
				$src[]='   $result = $prep->execute();';
			}else{
				$src[]='   $result = $this->_conn->exec ($query);';
			}
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
}
