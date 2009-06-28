<?php  require_once ( JELIX_LIB_PATH .'dao/jDaoRecordBase.class.php');
 require_once ( JELIX_LIB_PATH .'dao/jDaoFactoryBase.class.php');

class cDaoRecord_havefnubb_Jx_forum_Jx_mysql extends jDaoRecordBase {
 public $id_forum;
 public $forum_name;
 public $id_cat;
 public $forum_desc;
 public $forum_order;
 public $parent_id;
 public $child_level;
 public $forum_type;
 public $forum_url;
 public $cat_name;
 public $cat_order;
   public function getProperties() { return cDao_havefnubb_Jx_forum_Jx_mysql::$_properties; }
   public function getPrimaryKeyNames() { return cDao_havefnubb_Jx_forum_Jx_mysql::$_pkFields; }
}

class cDao_havefnubb_Jx_forum_Jx_mysql extends jDaoFactoryBase {
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
      1 => 'forum_name',
      2 => 'id_cat',
      3 => 'forum_desc',
      4 => 'forum_order',
      5 => 'parent_id',
      6 => 'child_level',
      7 => 'forum_type',
      8 => 'forum_url',
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
      0 => 'cat_name',
      1 => 'cat_order',
    ),
  ),
);
   protected $_primaryTable = 'forum';
   protected $_selectClause='SELECT `forum`.`id_forum`, `forum`.`forum_name`, `forum`.`id_cat`, `forum`.`forum_desc`, `forum`.`forum_order`, `forum`.`parent_id`, `forum`.`child_level`, `forum`.`forum_type`, `forum`.`forum_url`, `category`.`cat_name`, `category`.`cat_order`';
   protected $_fromClause;
   protected $_whereClause=' WHERE  `forum`.`id_cat`=`category`.`id_cat`';
   protected $_DaoRecordClassName='cDaoRecord_havefnubb_Jx_forum_Jx_mysql';
   protected $_daoSelector = 'havefnubb~forum';
   public static $_properties = array (
  'id_forum' => 
  array (
    'name' => 'id_forum',
    'fieldName' => 'id_forum',
    'regExp' => NULL,
    'required' => false,
    'requiredInConditions' => true,
    'isPK' => true,
    'isFK' => false,
    'datatype' => 'autoincrement',
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
  'forum_name' => 
  array (
    'name' => 'forum_name',
    'fieldName' => 'forum_name',
    'regExp' => NULL,
    'required' => true,
    'requiredInConditions' => true,
    'isPK' => false,
    'isFK' => false,
    'datatype' => 'string',
    'table' => 'forum',
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
  'id_cat' => 
  array (
    'name' => 'id_cat',
    'fieldName' => 'id_cat',
    'regExp' => NULL,
    'required' => true,
    'requiredInConditions' => true,
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
  'forum_desc' => 
  array (
    'name' => 'forum_desc',
    'fieldName' => 'forum_desc',
    'regExp' => NULL,
    'required' => true,
    'requiredInConditions' => true,
    'isPK' => false,
    'isFK' => false,
    'datatype' => 'string',
    'table' => 'forum',
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
  'forum_order' => 
  array (
    'name' => 'forum_order',
    'fieldName' => 'forum_order',
    'regExp' => NULL,
    'required' => true,
    'requiredInConditions' => true,
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
  'parent_id' => 
  array (
    'name' => 'parent_id',
    'fieldName' => 'parent_id',
    'regExp' => NULL,
    'required' => true,
    'requiredInConditions' => true,
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
  'child_level' => 
  array (
    'name' => 'child_level',
    'fieldName' => 'child_level',
    'regExp' => NULL,
    'required' => true,
    'requiredInConditions' => true,
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
  'forum_type' => 
  array (
    'name' => 'forum_type',
    'fieldName' => 'forum_type',
    'regExp' => NULL,
    'required' => true,
    'requiredInConditions' => true,
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
  'forum_url' => 
  array (
    'name' => 'forum_url',
    'fieldName' => 'forum_url',
    'regExp' => NULL,
    'required' => false,
    'requiredInConditions' => false,
    'isPK' => false,
    'isFK' => false,
    'datatype' => 'string',
    'table' => 'forum',
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
 if($record->id_forum > 0 ){
    $query = 'INSERT INTO `'.$this->_conn->prefixTable('forum').'` (
`id_forum`,`forum_name`,`id_cat`,`forum_desc`,`forum_order`,`parent_id`,`child_level`,`forum_type`,`forum_url`
) VALUES (
'.intval($record->id_forum).', '.($record->forum_name === null ? 'NULL' : $this->_conn->quote($record->forum_name,false)).', '.($record->id_cat === null ? 'NULL' : intval($record->id_cat)).', '.($record->forum_desc === null ? 'NULL' : $this->_conn->quote($record->forum_desc,false)).', '.($record->forum_order === null ? 'NULL' : intval($record->forum_order)).', '.($record->parent_id === null ? 'NULL' : intval($record->parent_id)).', '.($record->child_level === null ? 'NULL' : intval($record->child_level)).', '.($record->forum_type === null ? 'NULL' : intval($record->forum_type)).', '.($record->forum_url === null ? 'NULL' : $this->_conn->quote($record->forum_url,false)).'
)';
}else{
    $query = 'INSERT INTO `'.$this->_conn->prefixTable('forum').'` (
`forum_name`,`id_cat`,`forum_desc`,`forum_order`,`parent_id`,`child_level`,`forum_type`,`forum_url`
) VALUES (
'.($record->forum_name === null ? 'NULL' : $this->_conn->quote($record->forum_name,false)).', '.($record->id_cat === null ? 'NULL' : intval($record->id_cat)).', '.($record->forum_desc === null ? 'NULL' : $this->_conn->quote($record->forum_desc,false)).', '.($record->forum_order === null ? 'NULL' : intval($record->forum_order)).', '.($record->parent_id === null ? 'NULL' : intval($record->parent_id)).', '.($record->child_level === null ? 'NULL' : intval($record->child_level)).', '.($record->forum_type === null ? 'NULL' : intval($record->forum_type)).', '.($record->forum_url === null ? 'NULL' : $this->_conn->quote($record->forum_url,false)).'
)';
}
   $result = $this->_conn->exec ($query);
   if(!$result)
       return false;
   if($record->id_forum < 1 ) 
       $record->id_forum= $this->_conn->lastInsertId();
    return $result;
}
public function update ($record){
   $query = 'UPDATE `'.$this->_conn->prefixTable('forum').'` SET 
 `forum_name`= '.($record->forum_name === null ? 'NULL' : $this->_conn->quote($record->forum_name,false)).', `id_cat`= '.($record->id_cat === null ? 'NULL' : intval($record->id_cat)).', `forum_desc`= '.($record->forum_desc === null ? 'NULL' : $this->_conn->quote($record->forum_desc,false)).', `forum_order`= '.($record->forum_order === null ? 'NULL' : intval($record->forum_order)).', `parent_id`= '.($record->parent_id === null ? 'NULL' : intval($record->parent_id)).', `child_level`= '.($record->child_level === null ? 'NULL' : intval($record->child_level)).', `forum_type`= '.($record->forum_type === null ? 'NULL' : intval($record->forum_type)).', `forum_url`= '.($record->forum_url === null ? 'NULL' : $this->_conn->quote($record->forum_url,false)).'
 where  `id_forum`'.'='.intval($record->id_forum).'
';
   $result = $this->_conn->exec ($query);
   return $result;
 }
 function findAll (){
    $__query =  $this->_selectClause.$this->_fromClause.$this->_whereClause;
$__query .=' AND  1=1  ORDER BY `category`.`cat_order` asc, `forum`.`forum_order` asc';
    $__rs = $this->_conn->query($__query);
    $__rs->setFetchMode(8,'cDaoRecord_havefnubb_Jx_forum_Jx_mysql');
    return $__rs;
}
 function findAllExceptOneToSplitAndLink ($id_forum){
    $__query =  $this->_selectClause.$this->_fromClause.$this->_whereClause;
$__query .=' AND  `forum`.`id_forum` '.'<>'.intval($id_forum).' AND `forum`.`forum_type` = 0 ORDER BY `category`.`cat_order` asc, `forum`.`forum_order` asc';
    $__rs = $this->_conn->query($__query);
    $__rs->setFetchMode(8,'cDaoRecord_havefnubb_Jx_forum_Jx_mysql');
    return $__rs;
}
 function findChild ($id_forum, $lvl){
    $__query =  $this->_selectClause.$this->_fromClause.$this->_whereClause;
$__query .=' AND  `forum`.`parent_id` '.'='.intval($id_forum).' AND `forum`.`child_level` '.'='.intval($lvl).' ORDER BY `category`.`cat_order` asc, `forum`.`forum_order` asc';
    $__rs = $this->_conn->query($__query);
    $__rs->setFetchMode(8,'cDaoRecord_havefnubb_Jx_forum_Jx_mysql');
    return $__rs;
}
 function findByCatId ($id_cat){
    $__query =  $this->_selectClause.$this->_fromClause.$this->_whereClause;
$__query .=' AND  `forum`.`id_cat` '.'='.intval($id_cat).'';
    $__rs = $this->_conn->query($__query);
    $__rs->setFetchMode(8,'cDaoRecord_havefnubb_Jx_forum_Jx_mysql');
    return $__rs;
}
 function findParentByCatId ($id_cat){
    $__query =  $this->_selectClause.$this->_fromClause.$this->_whereClause;
$__query .=' AND  `forum`.`id_cat` '.'='.intval($id_cat).' AND `forum`.`parent_id` = 0 ORDER BY `forum`.`id_forum` asc';
    $__rs = $this->_conn->query($__query);
    $__rs->setFetchMode(8,'cDaoRecord_havefnubb_Jx_forum_Jx_mysql');
    return $__rs;
}
 function findIt ($id_forum){
    $__query =  $this->_selectClause.$this->_fromClause.$this->_whereClause;
$__query .=' AND  `forum`.`id_forum` '.'='.intval($id_forum).'';
    $__rs = $this->_conn->query($__query);
    $__rs->setFetchMode(8,'cDaoRecord_havefnubb_Jx_forum_Jx_mysql');
    return $__rs;
}
 function findAllExceptOne ($id_forum){
    $__query =  $this->_selectClause.$this->_fromClause.$this->_whereClause;
$__query .=' AND  `forum`.`id_forum` '.'<>'.intval($id_forum).' ORDER BY `forum`.`parent_id` asc';
    $__rs = $this->_conn->query($__query);
    $__rs->setFetchMode(8,'cDaoRecord_havefnubb_Jx_forum_Jx_mysql');
    return $__rs;
}
 function findAllCatWithFathers (){
    $__query =  $this->_selectClause.$this->_fromClause.$this->_whereClause;
$__query .=' AND  1=1  GROUP BY `forum`.`id_cat`';
    $__rs = $this->_conn->query($__query);
    $__rs->setFetchMode(8,'cDaoRecord_havefnubb_Jx_forum_Jx_mysql');
    return $__rs;
}
}
?>