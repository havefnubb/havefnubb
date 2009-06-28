<?php  require_once ( JELIX_LIB_PATH .'dao/jDaoRecordBase.class.php');
 require_once ( JELIX_LIB_PATH .'dao/jDaoFactoryBase.class.php');

class cDaoRecord_havefnubb_Jx_forum_cat_Jx_mysql extends jDaoRecordBase {
 public $id_cat;
 public $cat_name;
 public $cat_order;
 public $id_forum;
 public $parent_id;
   public function getProperties() { return cDao_havefnubb_Jx_forum_cat_Jx_mysql::$_properties; }
   public function getPrimaryKeyNames() { return cDao_havefnubb_Jx_forum_cat_Jx_mysql::$_pkFields; }
}

class cDao_havefnubb_Jx_forum_cat_Jx_mysql extends jDaoFactoryBase {
   protected $_tables = array (
  'forum' => 
  array (
    'name' => 'forum',
    'realname' => 'forum',
    'pk' => 
    array (
      0 => 'id_forum',
    ),
    'fields' => 
    array (
      0 => 'id_forum',
      1 => 'parent_id',
    ),
  ),
  'category' => 
  array (
    'name' => 'category',
    'realname' => 'category',
    'pk' => 
    array (
      0 => 'id_cat',
    ),
    'fk' => 
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
   protected $_primaryTable = 'forum';
   protected $_selectClause='SELECT `category`.`id_cat`, `category`.`cat_name`, `category`.`cat_order`, `forum`.`id_forum`, `forum`.`parent_id`';
   protected $_fromClause;
   protected $_whereClause=' WHERE  `forum`.`id_cat`=`category`.`id_cat`';
   protected $_DaoRecordClassName='cDaoRecord_havefnubb_Jx_forum_cat_Jx_mysql';
   protected $_daoSelector = 'havefnubb~forum_cat';
   public static $_properties = array (
  'id_cat' => 
  array (
    'name' => 'id_cat',
    'fieldName' => 'id_cat',
    'regExp' => NULL,
    'required' => false,
    'requiredInConditions' => false,
    'isPK' => true,
    'isFK' => false,
    'datatype' => 'autoincrement',
    'table' => 'category',
    'updatePattern' => '',
    'insertPattern' => '',
    'selectPattern' => '%s',
    'sequenceName' => '',
    'maxlength' => NULL,
    'minlength' => NULL,
    'ofPrimaryTable' => false,
    'defaultValue' => NULL,
    'needsQuotes' => false,
  ),
  'cat_name' => 
  array (
    'name' => 'cat_name',
    'fieldName' => 'cat_name',
    'regExp' => NULL,
    'required' => false,
    'requiredInConditions' => false,
    'isPK' => false,
    'isFK' => false,
    'datatype' => 'string',
    'table' => 'category',
    'updatePattern' => '',
    'insertPattern' => '',
    'selectPattern' => '%s',
    'sequenceName' => '',
    'maxlength' => 255,
    'minlength' => NULL,
    'ofPrimaryTable' => false,
    'defaultValue' => NULL,
    'needsQuotes' => true,
  ),
  'cat_order' => 
  array (
    'name' => 'cat_order',
    'fieldName' => 'cat_order',
    'regExp' => NULL,
    'required' => false,
    'requiredInConditions' => false,
    'isPK' => false,
    'isFK' => false,
    'datatype' => 'int',
    'table' => 'category',
    'updatePattern' => '',
    'insertPattern' => '',
    'selectPattern' => '%s',
    'sequenceName' => '',
    'maxlength' => NULL,
    'minlength' => NULL,
    'ofPrimaryTable' => false,
    'defaultValue' => NULL,
    'needsQuotes' => false,
  ),
  'id_forum' => 
  array (
    'name' => 'id_forum',
    'fieldName' => 'id_forum',
    'regExp' => NULL,
    'required' => true,
    'requiredInConditions' => true,
    'isPK' => true,
    'isFK' => false,
    'datatype' => 'int',
    'table' => 'forum',
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
  'parent_id' => 
  array (
    'name' => 'parent_id',
    'fieldName' => 'parent_id',
    'regExp' => NULL,
    'required' => false,
    'requiredInConditions' => false,
    'isPK' => false,
    'isFK' => false,
    'datatype' => 'int',
    'table' => 'forum',
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
   public static $_pkFields = array('id_forum');
 
public function __construct($conn){
   parent::__construct($conn);
   $this->_fromClause = ' FROM `'.$this->_conn->prefixTable('forum').'` AS `forum`, `'.$this->_conn->prefixTable('category').'` AS `category`';
}
   public function getProperties() { return self::$_properties; }
   public function getPrimaryKeyNames() { return self::$_pkFields;}
 
 protected function _getPkWhereClauseForSelect($pk){
   extract($pk);
 return ' AND  `forum`.`id_forum`'.'='.intval($id_forum).'';
}
 
protected function _getPkWhereClauseForNonSelect($pk){
   extract($pk);
   return ' where  `id_forum`'.'='.intval($id_forum).'';
}
public function insert ($record){
 if($record->id_cat > 0 ){
    $query = 'INSERT INTO `'.$this->_conn->prefixTable('forum').'` (
`id_forum`,`parent_id`
) VALUES (
'.($record->id_forum === null ? 'NULL' : intval($record->id_forum)).', '.($record->parent_id === null ? 'NULL' : intval($record->parent_id)).'
)';
}else{
    $query = 'INSERT INTO `'.$this->_conn->prefixTable('forum').'` (
`id_forum`,`parent_id`
) VALUES (
'.($record->id_forum === null ? 'NULL' : intval($record->id_forum)).', '.($record->parent_id === null ? 'NULL' : intval($record->parent_id)).'
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
   $query = 'UPDATE `'.$this->_conn->prefixTable('forum').'` SET 
 `parent_id`= '.($record->parent_id === null ? 'NULL' : intval($record->parent_id)).'
 where  `id_forum`'.'='.intval($record->id_forum).'
';
   $result = $this->_conn->exec ($query);
   return $result;
 }
 function findAll (){
    $__query =  $this->_selectClause.$this->_fromClause.$this->_whereClause;
$__query .=' AND  1=1  ORDER BY `category`.`cat_order` asc';
    $__rs = $this->_conn->query($__query);
    $__rs->setFetchMode(8,'cDaoRecord_havefnubb_Jx_forum_cat_Jx_mysql');
    return $__rs;
}
 function findAllCatWithFathers (){
    $__query =  $this->_selectClause.$this->_fromClause.$this->_whereClause;
$__query .=' AND  `forum`.`parent_id` = 0 GROUP BY `category`.`id_cat`';
    $__rs = $this->_conn->query($__query);
    $__rs->setFetchMode(8,'cDaoRecord_havefnubb_Jx_forum_cat_Jx_mysql');
    return $__rs;
}
 function countByIdCat ($id_cat){
    $__query = 'SELECT COUNT(*) as c '.$this->_fromClause.$this->_whereClause;
$__query .=' AND  `category`.`id_cat` '.'='.intval($id_cat).'';
    $__rs = $this->_conn->query($__query);
    $__res = $__rs->fetch();
    return intval($__res->c);
}
}
?>