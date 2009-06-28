<?php  require_once ( JELIX_LIB_PATH .'dao/jDaoRecordBase.class.php');
 require_once ( JELIX_LIB_PATH .'dao/jDaoFactoryBase.class.php');

class cDaoRecord_havefnubb_Jx_category_Jx_mysql extends jDaoRecordBase {
 public $id_cat;
 public $cat_name;
 public $cat_order;
   public function getProperties() { return cDao_havefnubb_Jx_category_Jx_mysql::$_properties; }
   public function getPrimaryKeyNames() { return cDao_havefnubb_Jx_category_Jx_mysql::$_pkFields; }
}

class cDao_havefnubb_Jx_category_Jx_mysql extends jDaoFactoryBase {
   protected $_tables = array (
  'category' => 
  array (
    'name' => 'category',
    'realname' => 'category',
    'pk' => 
    array (
      0 => 'id_cat',
    ),
    'fields' => 
    array (
      0 => 'id_cat',
      1 => 'cat_name',
      2 => 'cat_order',
    ),
  ),
);
   protected $_primaryTable = 'category';
   protected $_selectClause='SELECT `category`.`id_cat`, `category`.`cat_name`, `category`.`cat_order`';
   protected $_fromClause;
   protected $_whereClause='';
   protected $_DaoRecordClassName='cDaoRecord_havefnubb_Jx_category_Jx_mysql';
   protected $_daoSelector = 'havefnubb~category';
   public static $_properties = array (
  'id_cat' => 
  array (
    'name' => 'id_cat',
    'fieldName' => 'id_cat',
    'regExp' => NULL,
    'required' => false,
    'requiredInConditions' => true,
    'isPK' => true,
    'isFK' => false,
    'datatype' => 'autoincrement',
    'table' => 'category',
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
  'cat_name' => 
  array (
    'name' => 'cat_name',
    'fieldName' => 'cat_name',
    'regExp' => NULL,
    'required' => true,
    'requiredInConditions' => true,
    'isPK' => false,
    'isFK' => false,
    'datatype' => 'string',
    'table' => 'category',
    'updatePattern' => '%s',
    'insertPattern' => '%s',
    'selectPattern' => '%s',
    'sequenceName' => '',
    'maxlength' => 255,
    'minlength' => NULL,
    'ofPrimaryTable' => true,
    'defaultValue' => NULL,
    'needsQuotes' => true,
  ),
  'cat_order' => 
  array (
    'name' => 'cat_order',
    'fieldName' => 'cat_order',
    'regExp' => NULL,
    'required' => true,
    'requiredInConditions' => true,
    'isPK' => false,
    'isFK' => false,
    'datatype' => 'int',
    'table' => 'category',
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
);
   public static $_pkFields = array('id_cat');
 
public function __construct($conn){
   parent::__construct($conn);
   $this->_fromClause = ' FROM `'.$this->_conn->prefixTable('category').'` AS `category`';
}
   public function getProperties() { return self::$_properties; }
   public function getPrimaryKeyNames() { return self::$_pkFields;}
 
 protected function _getPkWhereClauseForSelect($pk){
   extract($pk);
 return ' WHERE  `category`.`id_cat`'.'='.intval($id_cat).'';
}
 
protected function _getPkWhereClauseForNonSelect($pk){
   extract($pk);
   return ' where  `id_cat`'.'='.intval($id_cat).'';
}
public function insert ($record){
 if($record->id_cat > 0 ){
    $query = 'INSERT INTO `'.$this->_conn->prefixTable('category').'` (
`id_cat`,`cat_name`,`cat_order`
) VALUES (
'.intval($record->id_cat).', '.($record->cat_name === null ? 'NULL' : $this->_conn->quote($record->cat_name,false)).', '.($record->cat_order === null ? 'NULL' : intval($record->cat_order)).'
)';
}else{
    $query = 'INSERT INTO `'.$this->_conn->prefixTable('category').'` (
`cat_name`,`cat_order`
) VALUES (
'.($record->cat_name === null ? 'NULL' : $this->_conn->quote($record->cat_name,false)).', '.($record->cat_order === null ? 'NULL' : intval($record->cat_order)).'
)';
}
   $result = $this->_conn->exec ($query);
   if(!$result)
       return false;
   if($record->id_cat < 1 ) 
       $record->id_cat= $this->_conn->lastInsertId();
    return $result;
}
public function update ($record){
   $query = 'UPDATE `'.$this->_conn->prefixTable('category').'` SET 
 `cat_name`= '.($record->cat_name === null ? 'NULL' : $this->_conn->quote($record->cat_name,false)).', `cat_order`= '.($record->cat_order === null ? 'NULL' : intval($record->cat_order)).'
 where  `id_cat`'.'='.intval($record->id_cat).'
';
   $result = $this->_conn->exec ($query);
   return $result;
 }
 function findAll (){
    $__query =  $this->_selectClause.$this->_fromClause.$this->_whereClause;
$__query .=' WHERE  1=1  ORDER BY `category`.`cat_order` asc';
    $__rs = $this->_conn->query($__query);
    $__rs->setFetchMode(8,'cDaoRecord_havefnubb_Jx_category_Jx_mysql');
    return $__rs;
}
}
?>