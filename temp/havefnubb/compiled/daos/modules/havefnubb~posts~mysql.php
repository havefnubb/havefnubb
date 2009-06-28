<?php  require_once ( JELIX_LIB_PATH .'dao/jDaoRecordBase.class.php');
 require_once ( JELIX_LIB_PATH .'dao/jDaoFactoryBase.class.php');

class cDaoRecord_havefnubb_Jx_posts_Jx_mysql extends jDaoRecordBase {
 public $id_post;
 public $id_user;
 public $id_forum;
 public $parent_id;
 public $status;
 public $subject;
 public $message;
 public $date_created;
 public $date_modified;
 public $viewed;
 public $poster_ip;
 public $id;
 public $email;
 public $login;
 public $member_comment;
 public $member_town;
 public $member_avatar;
 public $member_website;
 public $nb_msg;
 public $member_last_post;
 public $forum_parent_id;
 public $forum_name;
   public function getProperties() { return cDao_havefnubb_Jx_posts_Jx_mysql::$_properties; }
   public function getPrimaryKeyNames() { return cDao_havefnubb_Jx_posts_Jx_mysql::$_pkFields; }
}

class cDao_havefnubb_Jx_posts_Jx_mysql extends jDaoFactoryBase {
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
      1 => 'id_user',
      2 => 'id_forum',
      3 => 'parent_id',
      4 => 'status',
      5 => 'subject',
      6 => 'message',
      7 => 'date_created',
      8 => 'date_modified',
      9 => 'viewed',
      10 => 'poster_ip',
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
      0 => 'id',
      1 => 'email',
      2 => 'login',
      3 => 'member_comment',
      4 => 'member_town',
      5 => 'member_avatar',
      6 => 'member_website',
      7 => 'nb_msg',
      8 => 'member_last_post',
    ),
  ),
  'forum' => 
  array (
    'name' => 'forum',
    'realname' => 'forum',
    'pk' => 
    array (
      0 => 'id_forum',
    ),
    'fk' => 
    array (
      0 => 'id_forum',
    ),
    'fields' => 
    array (
      0 => 'forum_parent_id',
      1 => 'forum_name',
    ),
  ),
);
   protected $_primaryTable = 'posts';
   protected $_selectClause='SELECT `posts`.`id_post`, `posts`.`id_user`, `posts`.`id_forum`, `posts`.`parent_id`, `posts`.`status`, `posts`.`subject`, `posts`.`message`, `posts`.`date_created`, `posts`.`date_modified`, `posts`.`viewed`, `posts`.`poster_ip`, `usr`.`id_user` as `id`, `usr`.`member_email` as `email`, `usr`.`member_login` as `login`, `usr`.`member_comment`, `usr`.`member_town`, `usr`.`member_avatar`, `usr`.`member_website`, `usr`.`member_nb_msg` as `nb_msg`, `usr`.`member_last_post`, `forum`.`parent_id` as `forum_parent_id`, `forum`.`forum_name`';
   protected $_fromClause;
   protected $_whereClause=' WHERE  `posts`.`id_user`=`usr`.`id_user` AND `posts`.`id_forum`=`forum`.`id_forum`';
   protected $_DaoRecordClassName='cDaoRecord_havefnubb_Jx_posts_Jx_mysql';
   protected $_daoSelector = 'havefnubb~posts';
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
  'id_user' => 
  array (
    'name' => 'id_user',
    'fieldName' => 'id_user',
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
  'status' => 
  array (
    'name' => 'status',
    'fieldName' => 'status',
    'regExp' => NULL,
    'required' => true,
    'requiredInConditions' => true,
    'isPK' => false,
    'isFK' => false,
    'datatype' => 'string',
    'table' => 'posts',
    'updatePattern' => '%s',
    'insertPattern' => '%s',
    'selectPattern' => '%s',
    'sequenceName' => '',
    'maxlength' => NULL,
    'minlength' => NULL,
    'ofPrimaryTable' => true,
    'defaultValue' => NULL,
    'needsQuotes' => true,
  ),
  'subject' => 
  array (
    'name' => 'subject',
    'fieldName' => 'subject',
    'regExp' => NULL,
    'required' => true,
    'requiredInConditions' => true,
    'isPK' => false,
    'isFK' => false,
    'datatype' => 'string',
    'table' => 'posts',
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
  'message' => 
  array (
    'name' => 'message',
    'fieldName' => 'message',
    'regExp' => NULL,
    'required' => true,
    'requiredInConditions' => true,
    'isPK' => false,
    'isFK' => false,
    'datatype' => 'text',
    'table' => 'posts',
    'updatePattern' => '%s',
    'insertPattern' => '%s',
    'selectPattern' => '%s',
    'sequenceName' => '',
    'maxlength' => NULL,
    'minlength' => NULL,
    'ofPrimaryTable' => true,
    'defaultValue' => NULL,
    'needsQuotes' => true,
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
  'viewed' => 
  array (
    'name' => 'viewed',
    'fieldName' => 'viewed',
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
  'poster_ip' => 
  array (
    'name' => 'poster_ip',
    'fieldName' => 'poster_ip',
    'regExp' => NULL,
    'required' => true,
    'requiredInConditions' => true,
    'isPK' => false,
    'isFK' => false,
    'datatype' => 'string',
    'table' => 'posts',
    'updatePattern' => '%s',
    'insertPattern' => '%s',
    'selectPattern' => '%s',
    'sequenceName' => '',
    'maxlength' => NULL,
    'minlength' => NULL,
    'ofPrimaryTable' => true,
    'defaultValue' => NULL,
    'needsQuotes' => true,
  ),
  'id' => 
  array (
    'name' => 'id',
    'fieldName' => 'id_user',
    'regExp' => NULL,
    'required' => false,
    'requiredInConditions' => false,
    'isPK' => true,
    'isFK' => false,
    'datatype' => 'int',
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
  'email' => 
  array (
    'name' => 'email',
    'fieldName' => 'member_email',
    'regExp' => NULL,
    'required' => false,
    'requiredInConditions' => false,
    'isPK' => false,
    'isFK' => false,
    'datatype' => 'string',
    'table' => 'usr',
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
  'login' => 
  array (
    'name' => 'login',
    'fieldName' => 'member_login',
    'regExp' => NULL,
    'required' => false,
    'requiredInConditions' => false,
    'isPK' => false,
    'isFK' => false,
    'datatype' => 'string',
    'table' => 'usr',
    'updatePattern' => '',
    'insertPattern' => '',
    'selectPattern' => '%s',
    'sequenceName' => '',
    'maxlength' => 50,
    'minlength' => NULL,
    'ofPrimaryTable' => false,
    'defaultValue' => NULL,
    'needsQuotes' => true,
  ),
  'member_comment' => 
  array (
    'name' => 'member_comment',
    'fieldName' => 'member_comment',
    'regExp' => NULL,
    'required' => false,
    'requiredInConditions' => false,
    'isPK' => false,
    'isFK' => false,
    'datatype' => 'string',
    'table' => 'usr',
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
  'member_town' => 
  array (
    'name' => 'member_town',
    'fieldName' => 'member_town',
    'regExp' => NULL,
    'required' => false,
    'requiredInConditions' => false,
    'isPK' => false,
    'isFK' => false,
    'datatype' => 'string',
    'table' => 'usr',
    'updatePattern' => '',
    'insertPattern' => '',
    'selectPattern' => '%s',
    'sequenceName' => '',
    'maxlength' => 100,
    'minlength' => NULL,
    'ofPrimaryTable' => false,
    'defaultValue' => NULL,
    'needsQuotes' => true,
  ),
  'member_avatar' => 
  array (
    'name' => 'member_avatar',
    'fieldName' => 'member_avatar',
    'regExp' => NULL,
    'required' => false,
    'requiredInConditions' => false,
    'isPK' => false,
    'isFK' => false,
    'datatype' => 'string',
    'table' => 'usr',
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
  'member_website' => 
  array (
    'name' => 'member_website',
    'fieldName' => 'member_website',
    'regExp' => NULL,
    'required' => false,
    'requiredInConditions' => false,
    'isPK' => false,
    'isFK' => false,
    'datatype' => 'string',
    'table' => 'usr',
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
  'nb_msg' => 
  array (
    'name' => 'nb_msg',
    'fieldName' => 'member_nb_msg',
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
  'forum_parent_id' => 
  array (
    'name' => 'forum_parent_id',
    'fieldName' => 'parent_id',
    'regExp' => NULL,
    'required' => false,
    'requiredInConditions' => false,
    'isPK' => false,
    'isFK' => false,
    'datatype' => 'int',
    'table' => 'forum',
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
  'forum_name' => 
  array (
    'name' => 'forum_name',
    'fieldName' => 'forum_name',
    'regExp' => NULL,
    'required' => false,
    'requiredInConditions' => false,
    'isPK' => false,
    'isFK' => false,
    'datatype' => 'int',
    'table' => 'forum',
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
   $this->_fromClause = ' FROM `'.$this->_conn->prefixTable('posts').'` AS `posts`, `'.$this->_conn->prefixTable('member').'` AS `usr`, `'.$this->_conn->prefixTable('forum').'` AS `forum`';
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
`id_post`,`id_user`,`id_forum`,`parent_id`,`status`,`subject`,`message`,`date_created`,`date_modified`,`viewed`,`poster_ip`
) VALUES (
'.intval($record->id_post).', '.($record->id_user === null ? 'NULL' : intval($record->id_user)).', '.($record->id_forum === null ? 'NULL' : intval($record->id_forum)).', '.($record->parent_id === null ? 'NULL' : intval($record->parent_id)).', '.($record->status === null ? 'NULL' : $this->_conn->quote($record->status,false)).', '.($record->subject === null ? 'NULL' : $this->_conn->quote($record->subject,false)).', '.($record->message === null ? 'NULL' : $this->_conn->quote($record->message,false)).', '.($record->date_created === null ? 'NULL' : intval($record->date_created)).', '.($record->date_modified === null ? 'NULL' : intval($record->date_modified)).', '.($record->viewed === null ? 'NULL' : intval($record->viewed)).', '.($record->poster_ip === null ? 'NULL' : $this->_conn->quote($record->poster_ip,false)).'
)';
}else{
    $query = 'INSERT INTO `'.$this->_conn->prefixTable('posts').'` (
`id_user`,`id_forum`,`parent_id`,`status`,`subject`,`message`,`date_created`,`date_modified`,`viewed`,`poster_ip`
) VALUES (
'.($record->id_user === null ? 'NULL' : intval($record->id_user)).', '.($record->id_forum === null ? 'NULL' : intval($record->id_forum)).', '.($record->parent_id === null ? 'NULL' : intval($record->parent_id)).', '.($record->status === null ? 'NULL' : $this->_conn->quote($record->status,false)).', '.($record->subject === null ? 'NULL' : $this->_conn->quote($record->subject,false)).', '.($record->message === null ? 'NULL' : $this->_conn->quote($record->message,false)).', '.($record->date_created === null ? 'NULL' : intval($record->date_created)).', '.($record->date_modified === null ? 'NULL' : intval($record->date_modified)).', '.($record->viewed === null ? 'NULL' : intval($record->viewed)).', '.($record->poster_ip === null ? 'NULL' : $this->_conn->quote($record->poster_ip,false)).'
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
 `id_user`= '.($record->id_user === null ? 'NULL' : intval($record->id_user)).', `id_forum`= '.($record->id_forum === null ? 'NULL' : intval($record->id_forum)).', `parent_id`= '.($record->parent_id === null ? 'NULL' : intval($record->parent_id)).', `status`= '.($record->status === null ? 'NULL' : $this->_conn->quote($record->status,false)).', `subject`= '.($record->subject === null ? 'NULL' : $this->_conn->quote($record->subject,false)).', `message`= '.($record->message === null ? 'NULL' : $this->_conn->quote($record->message,false)).', `date_created`= '.($record->date_created === null ? 'NULL' : intval($record->date_created)).', `date_modified`= '.($record->date_modified === null ? 'NULL' : intval($record->date_modified)).', `viewed`= '.($record->viewed === null ? 'NULL' : intval($record->viewed)).', `poster_ip`= '.($record->poster_ip === null ? 'NULL' : $this->_conn->quote($record->poster_ip,false)).'
 where  `id_post`'.'='.intval($record->id_post).'
';
   $result = $this->_conn->exec ($query);
   return $result;
 }
 function findAllExceptOneToSplitAndLink ($parent_id, $id_forum){
    $__query =  $this->_selectClause.$this->_fromClause.$this->_whereClause;
$__query .=' AND  `posts`.`parent_id` '.'<>'.intval($parent_id).' AND `posts`.`id_forum` '.'='.intval($id_forum).' ORDER BY `posts`.`date_modified` desc';
    $__rs = $this->_conn->query($__query);
    $__rs->setFetchMode(8,'cDaoRecord_havefnubb_Jx_posts_Jx_mysql');
    return $__rs;
}
 function findByIdForum ( $id_forum, $offset='0', $count='200'){
    $__query =  $this->_selectClause.$this->_fromClause.$this->_whereClause;
$__query .=' AND  `posts`.`id_forum` '.'='.intval($id_forum).' AND `posts`.`id_post` = posts.parent_id AND `posts`.`status` <> \'pined\' AND `posts`.`status` <> \'pinedclosed\' ORDER BY `posts`.`date_modified` desc';
    $__rs = $this->_conn->limitQuery($__query, $offset, $count);
    $__rs->setFetchMode(8,'cDaoRecord_havefnubb_Jx_posts_Jx_mysql');
    return $__rs;
}
 function findPinedPostByIdForum ($id_forum){
    $__query =  $this->_selectClause.$this->_fromClause.$this->_whereClause;
$__query .=' AND  `posts`.`id_forum` '.'='.intval($id_forum).' AND `posts`.`id_post` = posts.parent_id AND ( `posts`.`status` = \'pined\' OR `posts`.`status` = \'pinedclosed\') ORDER BY `posts`.`date_modified` desc';
    $__rs = $this->_conn->query($__query);
    $__rs->setFetchMode(8,'cDaoRecord_havefnubb_Jx_posts_Jx_mysql');
    return $__rs;
}
 function findAllPinedPost (){
    $__query =  $this->_selectClause.$this->_fromClause.$this->_whereClause;
$__query .=' AND  `posts`.`id_post` = posts.parent_id AND ( `posts`.`status` = \'pined\' OR `posts`.`status` = \'pinedclosed\') ORDER BY `posts`.`date_modified` desc';
    $__rs = $this->_conn->query($__query);
    $__rs->setFetchMode(8,'cDaoRecord_havefnubb_Jx_posts_Jx_mysql');
    return $__rs;
}
 function findByIdParent ( $parent_id, $offset='0', $count='200'){
    $__query =  $this->_selectClause.$this->_fromClause.$this->_whereClause;
$__query .=' AND  `posts`.`parent_id` '.'='.intval($parent_id).' ORDER BY `posts`.`parent_id` desc, `posts`.`id_post` asc';
    $__rs = $this->_conn->limitQuery($__query, $offset, $count);
    $__rs->setFetchMode(8,'cDaoRecord_havefnubb_Jx_posts_Jx_mysql');
    return $__rs;
}
 function getUserLastCommentOnPosts ($id_post){
    $__query =  $this->_selectClause.$this->_fromClause.$this->_whereClause;
$__query .=' AND  `posts`.`parent_id` '.'='.intval($id_post).' ORDER BY `posts`.`date_modified` desc';
    $__rs = $this->_conn->limitQuery($__query,0,1);
    $__rs->setFetchMode(8,'cDaoRecord_havefnubb_Jx_posts_Jx_mysql');
    return $__rs->fetch();
}
 function getUserLastCommentOnForums ($id_forum){
    $__query =  $this->_selectClause.$this->_fromClause.$this->_whereClause;
$__query .=' AND  `posts`.`id_forum` '.'='.intval($id_forum).' ORDER BY `posts`.`date_modified` desc';
    $__rs = $this->_conn->limitQuery($__query,0,1);
    $__rs->setFetchMode(8,'cDaoRecord_havefnubb_Jx_posts_Jx_mysql');
    return $__rs->fetch();
}
 function countMessages ($id_forum){
    $__query = 'SELECT COUNT(*) as c '.$this->_fromClause.$this->_whereClause;
$__query .=' AND  `posts`.`id_forum` '.'='.intval($id_forum).'';
    $__rs = $this->_conn->query($__query);
    $__res = $__rs->fetch();
    return intval($__res->c);
}
 function countResponse ($id_post){
    $__query = 'SELECT COUNT(*) as c '.$this->_fromClause.$this->_whereClause;
$__query .=' AND  `posts`.`parent_id` '.'='.intval($id_post).'';
    $__rs = $this->_conn->query($__query);
    $__res = $__rs->fetch();
    return intval($__res->c);
}
 function countReplies ($id_post, $parent_id){
    $__query = 'SELECT COUNT(*) as c '.$this->_fromClause.$this->_whereClause;
$__query .=' AND  `posts`.`parent_id` '.'='.intval($parent_id).' AND `posts`.`id_post` '.'<='.intval($id_post).'';
    $__rs = $this->_conn->query($__query);
    $__res = $__rs->fetch();
    return intval($__res->c);
}
 function getNbOfViewed ($id_post){
    $__query =  $this->_selectClause.$this->_fromClause.$this->_whereClause;
$__query .=' AND  `posts`.`id_post` '.'='.intval($id_post).'';
    $__rs = $this->_conn->limitQuery($__query,0,1);
    $__rs->setFetchMode(8,'cDaoRecord_havefnubb_Jx_posts_Jx_mysql');
    return $__rs->fetch();
}
 function countPostsByForumId ($id_forum){
    $__query = 'SELECT COUNT(*) as c '.$this->_fromClause.$this->_whereClause;
$__query .=' AND  `posts`.`id_forum` '.'='.intval($id_forum).' AND `posts`.`id_post` = posts.parent_id';
    $__rs = $this->_conn->query($__query);
    $__res = $__rs->fetch();
    return intval($__res->c);
}
 function countThreads ($id_forum){
    $__query = 'SELECT COUNT(*) as c '.$this->_fromClause.$this->_whereClause;
$__query .=' AND  `posts`.`id_forum` '.'='.intval($id_forum).' AND `posts`.`id_post` = posts.parent_id';
    $__rs = $this->_conn->query($__query);
    $__res = $__rs->fetch();
    return intval($__res->c);
}
 function countMsgsByIdUser ($id_user){
    $__query = 'SELECT COUNT(*) as c '.$this->_fromClause.$this->_whereClause;
$__query .=' AND  `posts`.`id_user` '.'='.intval($id_user).'';
    $__rs = $this->_conn->query($__query);
    $__res = $__rs->fetch();
    return intval($__res->c);
}
 function countAllPosts (){
    $__query = 'SELECT COUNT(*) as c '.$this->_fromClause.$this->_whereClause;
    $__rs = $this->_conn->query($__query);
    $__res = $__rs->fetch();
    return intval($__res->c);
}
 function countAllThreads (){
    $__query = 'SELECT COUNT(*) as c '.$this->_fromClause.$this->_whereClause;
$__query .=' AND  `posts`.`id_post` = posts.parent_id';
    $__rs = $this->_conn->query($__query);
    $__res = $__rs->fetch();
    return intval($__res->c);
}
 function getLastPost (){
    $__query =  $this->_selectClause.$this->_fromClause.$this->_whereClause;
$__query .=' AND  1=1  ORDER BY `posts`.`date_modified` desc';
    $__rs = $this->_conn->limitQuery($__query,0,1);
    $__rs->setFetchMode(8,'cDaoRecord_havefnubb_Jx_posts_Jx_mysql');
    return $__rs->fetch();
}
 function findLastPosts ( $count='5'){
    $__query =  $this->_selectClause.$this->_fromClause.$this->_whereClause;
$__query .=' AND  `posts`.`id_post` = posts.parent_id ORDER BY `posts`.`date_modified` desc';
    $__rs = $this->_conn->limitQuery($__query, 0, $count);
    $__rs->setFetchMode(8,'cDaoRecord_havefnubb_Jx_posts_Jx_mysql');
    return $__rs;
}
 function getMyLastEditedPost ($id_user){
    $__query =  $this->_selectClause.$this->_fromClause.$this->_whereClause;
$__query .=' AND  `posts`.`id_user` '.'='.intval($id_user).' ORDER BY `posts`.`date_modified` desc';
    $__rs = $this->_conn->limitQuery($__query,0,1);
    $__rs->setFetchMode(8,'cDaoRecord_havefnubb_Jx_posts_Jx_mysql');
    return $__rs->fetch();
}
 function findByAuthor ( $login, $count='25'){
    $__query =  $this->_selectClause.$this->_fromClause.$this->_whereClause;
$__query .=' AND  `usr`.`member_login` '.($login === null ? 'IS NULL' : '='.$this->_conn->quote($login,false)).' ORDER BY `posts`.`date_modified` desc';
    $__rs = $this->_conn->limitQuery($__query, 0, $count);
    $__rs->setFetchMode(8,'cDaoRecord_havefnubb_Jx_posts_Jx_mysql');
    return $__rs;
}
 function updateStatusByIdParent ($parent_id, $status){
    $__query = 'UPDATE `'.$this->_conn->prefixTable('posts').'` SET 
 `status`= '.($status === null ? 'NULL' : $this->_conn->quote($status,false)).'';
$__query .=' WHERE  `parent_id` '.'='.intval($parent_id).'';
    return $this->_conn->exec ($__query);
}
 function moveToForum ($id_post, $id_forum){
    $__query = 'UPDATE `'.$this->_conn->prefixTable('posts').'` SET 
 `id_forum`= '.($id_forum === null ? 'NULL' : intval($id_forum)).'';
$__query .=' WHERE  `parent_id` '.'='.intval($id_post).'';
    return $this->_conn->exec ($__query);
}
 function getAllFromCurrentIdPostWithParentId ($parent_id, $id_post){
    $__query =  $this->_selectClause.$this->_fromClause.$this->_whereClause;
$__query .=' AND  `posts`.`id_post` '.'>='.intval($id_post).' AND `posts`.`parent_id` '.'='.intval($parent_id).' ORDER BY `posts`.`id_post` asc';
    $__rs = $this->_conn->query($__query);
    $__rs->setFetchMode(8,'cDaoRecord_havefnubb_Jx_posts_Jx_mysql');
    return $__rs;
}
 function deleteAllFromCurrentIdPostWithParentId ($parent_id, $id_post){
    $__query = 'DELETE FROM `'.$this->_conn->prefixTable('posts').'` ';
$__query .=' WHERE  `id_post` '.'>='.intval($id_post).' AND `parent_id` '.'='.intval($parent_id).' ORDER BY `id_post` asc';
    return $this->_conn->exec ($__query);
}
}
?>