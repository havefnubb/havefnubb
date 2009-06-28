<?php  require_once ( JELIX_LIB_PATH .'dao/jDaoRecordBase.class.php');
 require_once ( JELIX_LIB_PATH .'dao/jDaoFactoryBase.class.php');

class cDaoRecord_jtags_Jx_tags_Jx_mysql extends jDaoRecordBase {
 public $tag_id;
 public $tag_name;
 public $nbuse=0;
   public function getProperties() { return cDao_jtags_Jx_tags_Jx_mysql::$_properties; }
   public function getPrimaryKeyNames() { return cDao_jtags_Jx_tags_Jx_mysql::$_pkFields; }
}

class cDao_jtags_Jx_tags_Jx_mysql extends jDaoFactoryBase {
   protected $_tables = array (
  'sc_tags' => 
  array (
    'name' => 'sc_tags',
    'realname' => 'sc_tags',
    'pk' => 
    array (
      0 => 'tag_id',
    ),
    'fields' => 
    array (
      0 => 'tag_id',
      1 => 'tag_name',
      2 => 'nbuse',
    ),
  ),
);
   protected $_primaryTable = 'sc_tags';
   protected $_selectClause='SELECT `sc_tags`.`tag_id`, `sc_tags`.`tag_name`, `sc_tags`.`nbuse`';
   protected $_fromClause;
   protected $_whereClause='';
   protected $_DaoRecordClassName='cDaoRecord_jtags_Jx_tags_Jx_mysql';
   protected $_daoSelector = 'jtags~tags';
   public static $_properties = array (
  'tag_id' => 
  array (
    'name' => 'tag_id',
    'fieldName' => 'tag_id',
    'regExp' => NULL,
    'required' => false,
    'requiredInConditions' => true,
    'isPK' => true,
    'isFK' => false,
    'datatype' => 'autoincrement',
    'table' => 'sc_tags',
    'updatePattern' => '%s',
    'insertPattern' => '%s',
    'selectPattern' => '%s',
    'sequenceName' => '',
    'maxlength' => NULL,
    'minlength' => NULL,
    'ofPrimaryTable' => true,
    'defaultValue' => NULL,
    'needsQuotes' => false,
  ),
  'tag_name' => 
  array (
    'name' => 'tag_name',
    'fieldName' => 'tag_name',
    'regExp' => NULL,
    'required' => true,
    'requiredInConditions' => true,
    'isPK' => false,
    'isFK' => false,
    'datatype' => 'string',
    'table' => 'sc_tags',
    'updatePattern' => '%s',
    'insertPattern' => '%s',
    'selectPattern' => '%s',
    'sequenceName' => '',
    'maxlength' => 50,
    'minlength' => NULL,
    'ofPrimaryTable' => true,
    'defaultValue' => NULL,
    'needsQuotes' => true,
  ),
  'nbuse' => 
  array (
    'name' => 'nbuse',
    'fieldName' => 'nbuse',
    'regExp' => NULL,
    'required' => false,
    'requiredInConditions' => false,
    'isPK' => false,
    'isFK' => false,
    'datatype' => 'int',
    'table' => 'sc_tags',
    'updatePattern' => '%s',
    'insertPattern' => '%s',
    'selectPattern' => '%s',
    'sequenceName' => '',
    'maxlength' => NULL,
    'minlength' => NULL,
    'ofPrimaryTable' => true,
    'defaultValue' => 0,
    'needsQuotes' => false,
  ),
);
   public static $_pkFields = array('tag_id');
 
public function __construct($conn){
   parent::__construct($conn);
   $this->_fromClause = ' FROM `'.$this->_conn->prefixTable('sc_tags').'` AS `sc_tags`';
}
   public function getProperties() { return self::$_properties; }
   public function getPrimaryKeyNames() { return self::$_pkFields;}
 
 protected function _getPkWhereClauseForSelect($pk){
   extract($pk);
 return ' WHERE  `sc_tags`.`tag_id`'.'='.intval($tag_id).'';
}
 
protected function _getPkWhereClauseForNonSelect($pk){
   extract($pk);
   return ' where  `tag_id`'.'='.intval($tag_id).'';
}
public function insert ($record){
 if($record->tag_id > 0 ){
    $query = 'INSERT INTO `'.$this->_conn->prefixTable('sc_tags').'` (
`tag_id`,`tag_name`,`nbuse`
) VALUES (
'.intval($record->tag_id).', '.($record->tag_name === null ? 'NULL' : $this->_conn->quote($record->tag_name,false)).', '.($record->nbuse === null ? 'NULL' : intval($record->nbuse)).'
)';
}else{
    $query = 'INSERT INTO `'.$this->_conn->prefixTable('sc_tags').'` (
`tag_name`,`nbuse`
) VALUES (
'.($record->tag_name === null ? 'NULL' : $this->_conn->quote($record->tag_name,false)).', '.($record->nbuse === null ? 'NULL' : intval($record->nbuse)).'
)';
}
   $result = $this->_conn->exec ($query);
   if(!$result)
       return false;
   if($record->tag_id < 1 ) 
       $record->tag_id= $this->_conn->lastInsertId();
    return $result;
}
public function update ($record){
   $query = 'UPDATE `'.$this->_conn->prefixTable('sc_tags').'` SET 
 `tag_name`= '.($record->tag_name === null ? 'NULL' : $this->_conn->quote($record->tag_name,false)).', `nbuse`= '.($record->nbuse === null ? 'NULL' : intval($record->nbuse)).'
 where  `tag_id`'.'='.intval($record->tag_id).'
';
   $result = $this->_conn->exec ($query);
   return $result;
 }
 function findAll (){
    $__query =  $this->_selectClause.$this->_fromClause.$this->_whereClause;
$__query .=' WHERE  1=1  ORDER BY `sc_tags`.`tag_name` asc';
    $__rs = $this->_conn->query($__query);
    $__rs->setFetchMode(8,'cDaoRecord_jtags_Jx_tags_Jx_mysql');
    return $__rs;
}
 function tagExiste ($tag){
    $__query =  $this->_selectClause.$this->_fromClause.$this->_whereClause;
$__query .=' WHERE  `sc_tags`.`tag_name` '.'='.$this->_conn->quote($tag).'';
    $__rs = $this->_conn->limitQuery($__query,0,1);
    $__rs->setFetchMode(8,'cDaoRecord_jtags_Jx_tags_Jx_mysql');
    return $__rs->fetch();
}
}
?>