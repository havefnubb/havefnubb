<?php  require_once ( JELIX_LIB_PATH .'dao/jDaoRecordBase.class.php');
 require_once ( JELIX_LIB_PATH .'dao/jDaoFactoryBase.class.php');

class cDaoRecord_havefnubb_Jx_newest_posts_Jx_mysql extends jDaoRecordBase {
 public $id_post;
 public $id_forum;
 public $date_created;
 public $date_modified;
 public $member_last_post;
   public function getProperties() { return cDao_havefnubb_Jx_newest_posts_Jx_mysql::$_properties; }
   public function getPrimaryKeyNames() { return cDao_havefnubb_Jx_newest_posts_Jx_mysql::$_pkFields; }
}

class cDao_havefnubb_Jx_newest_posts_Jx_mysql extends jDaoFactoryBase {
   protected $_tables = array (
  'posts' => 
  array (
    'name' => 'posts',
    'realname' => 'posts',
    'pk' => 
    array (
      0 => 'id_post',
    ),
    'fields' => 
    array (
      0 => 'id_post',
      1 => 'id_forum',
      2 => 'date_created',
      3 => 'date_modified',
    ),
  ),
  'usr' => 
  array (
    'name' => 'usr',
    'realname' => 'member',
    'pk' => 
    array (
      0 => 'id_user',
    ),
    'fk' => 
    array (
      0 => 'id_user',
    ),
    'fields' => 
    array (
      0 => 'member_last_post',
    ),
  ),
);
   protected $_primaryTable = 'posts';
   protected $_selectClause='SELECT `posts`.`id_post`, `posts`.`id_forum`, `posts`.`date_created`, `posts`.`date_modified`, `usr`.`member_last_post`';
   protected $_fromClause;
   protected $_whereClause=' WHERE  `posts`.`id_user`=`usr`.`id_user`';
   protected $_DaoRecordClassName='cDaoRecord_havefnubb_Jx_newest_posts_Jx_mysql';
   protected $_daoSelector = 'havefnubb~newest_posts';
   public static $_properties = array (
  'id_post' => 
  array (
    'name' => 'id_post',
    'fieldName' => 'id_post',
    'regExp' => NULL,
    'required' => false,
    'requiredInConditions' => true,
    'isPK' => true,
    'isFK' => false,
    'datatype' => 'autoincrement',
    'table' => 'posts',
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
  'id_forum' => 
  array (
    'name' => 'id_forum',
    'fieldName' => 'id_forum',
    'regExp' => NULL,
    'required' => true,
    'requiredInConditions' => true,
    'isPK' => false,
    'isFK' => false,
    'datatype' => 'int',
    'table' => 'posts',
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
  'date_created' => 
  array (
    'name' => 'date_created',
    'fieldName' => 'date_created',
    'regExp' => NULL,
    'required' => true,
    'requiredInConditions' => true,
    'isPK' => false,
    'isFK' => false,
    'datatype' => 'int',
    'table' => 'posts',
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
  'date_modified' => 
  array (
    'name' => 'date_modified',
    'fieldName' => 'date_modified',
    'regExp' => NULL,
    'required' => true,
    'requiredInConditions' => true,
    'isPK' => false,
    'isFK' => false,
    'datatype' => 'int',
    'table' => 'posts',
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
  'member_last_post' => 
  array (
    'name' => 'member_last_post',
    'fieldName' => 'member_last_post',
    'regExp' => NULL,
    'required' => false,
    'requiredInConditions' => false,
    'isPK' => false,
    'isFK' => false,
    'datatype' => 'integer',
    'table' => 'usr',
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
   public static $_pkFields = array('id_post');
 
public function __construct($conn){
   parent::__construct($conn);
   $this->_fromClause = ' FROM `'.$this->_conn->prefixTable('posts').'` AS `posts`, `'.$this->_conn->prefixTable('member').'` AS `usr`';
}
   public function getProperties() { return self::$_properties; }
   public function getPrimaryKeyNames() { return self::$_pkFields;}
 
 protected function _getPkWhereClauseForSelect($pk){
   extract($pk);
 return ' AND  `posts`.`id_post`'.'='.intval($id_post).'';
}
 
protected function _getPkWhereClauseForNonSelect($pk){
   extract($pk);
   return ' where  `id_post`'.'='.intval($id_post).'';
}
public function insert ($record){
 if($record->id_post > 0 ){
    $query = 'INSERT INTO `'.$this->_conn->prefixTable('posts').'` (
`id_post`,`id_forum`,`date_created`,`date_modified`
) VALUES (
'.intval($record->id_post).', '.($record->id_forum === null ? 'NULL' : intval($record->id_forum)).', '.($record->date_created === null ? 'NULL' : intval($record->date_created)).', '.($record->date_modified === null ? 'NULL' : intval($record->date_modified)).'
)';
}else{
    $query = 'INSERT INTO `'.$this->_conn->prefixTable('posts').'` (
`id_forum`,`date_created`,`date_modified`
) VALUES (
'.($record->id_forum === null ? 'NULL' : intval($record->id_forum)).', '.($record->date_created === null ? 'NULL' : intval($record->date_created)).', '.($record->date_modified === null ? 'NULL' : intval($record->date_modified)).'
)';
}
   $result = $this->_conn->exec ($query);
   if(!$result)
       return false;
   if($record->id_post < 1 ) 
       $record->id_post= $this->_conn->lastInsertId();
    return $result;
}
public function update ($record){
   $query = 'UPDATE `'.$this->_conn->prefixTable('posts').'` SET 
 `id_forum`= '.($record->id_forum === null ? 'NULL' : intval($record->id_forum)).', `date_created`= '.($record->date_created === null ? 'NULL' : intval($record->date_created)).', `date_modified`= '.($record->date_modified === null ? 'NULL' : intval($record->date_modified)).'
 where  `id_post`'.'='.intval($record->id_post).'
';
   $result = $this->_conn->exec ($query);
   return $result;
 }
 function getLastPostByIdForum ($id_forum){
    $__query =  $this->_selectClause.$this->_fromClause.$this->_whereClause;
$__query .=' AND  `posts`.`id_forum` '.'='.intval($id_forum).' AND `posts`.`date_created` > member_last_post ORDER BY `posts`.`date_modified` desc';
    $__rs = $this->_conn->limitQuery($__query,0,1);
    $__rs->setFetchMode(8,'cDaoRecord_havefnubb_Jx_newest_posts_Jx_mysql');
    return $__rs->fetch();
}
 function getPostStatus ($id_post){
    $__query =  $this->_selectClause.$this->_fromClause.$this->_whereClause;
$__query .=' AND  `posts`.`id_post` '.'='.intval($id_post).' AND `posts`.`date_created` > member_last_post ORDER BY `posts`.`date_modified` desc';
    $__rs = $this->_conn->limitQuery($__query,0,1);
    $__rs->setFetchMode(8,'cDaoRecord_havefnubb_Jx_newest_posts_Jx_mysql');
    return $__rs->fetch();
}
}
?>