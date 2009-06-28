<?php  require_once ( JELIX_LIB_PATH .'dao/jDaoRecordBase.class.php');
 require_once ( JELIX_LIB_PATH .'dao/jDaoFactoryBase.class.php');

class cDaoRecord_havefnubb_Jx_bans_Jx_mysql extends jDaoRecordBase {
 public $ban_id;
 public $ban_username;
 public $ban_ip;
 public $ban_email;
 public $ban_message;
 public $ban_expire;
   public function getProperties() { return cDao_havefnubb_Jx_bans_Jx_mysql::$_properties; }
   public function getPrimaryKeyNames() { return cDao_havefnubb_Jx_bans_Jx_mysql::$_pkFields; }
}

class cDao_havefnubb_Jx_bans_Jx_mysql extends jDaoFactoryBase {
   protected $_tables = array (
  'bans' => 
  array (
    'name' => 'bans',
    'realname' => 'bans',
    'pk' => 
    array (
      0 => 'ban_id',
    ),
    'fields' => 
    array (
      0 => 'ban_id',
      1 => 'ban_username',
      2 => 'ban_ip',
      3 => 'ban_email',
      4 => 'ban_message',
      5 => 'ban_expire',
    ),
  ),
);
   protected $_primaryTable = 'bans';
   protected $_selectClause='SELECT `bans`.`ban_id`, `bans`.`ban_username`, `bans`.`ban_ip`, `bans`.`ban_email`, `bans`.`ban_message`, `bans`.`ban_expire`';
   protected $_fromClause;
   protected $_whereClause='';
   protected $_DaoRecordClassName='cDaoRecord_havefnubb_Jx_bans_Jx_mysql';
   protected $_daoSelector = 'havefnubb~bans';
   public static $_properties = array (
  'ban_id' => 
  array (
    'name' => 'ban_id',
    'fieldName' => 'ban_id',
    'regExp' => NULL,
    'required' => false,
    'requiredInConditions' => true,
    'isPK' => true,
    'isFK' => false,
    'datatype' => 'autoincrement',
    'table' => 'bans',
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
  'ban_username' => 
  array (
    'name' => 'ban_username',
    'fieldName' => 'ban_username',
    'regExp' => NULL,
    'required' => false,
    'requiredInConditions' => false,
    'isPK' => false,
    'isFK' => false,
    'datatype' => 'string',
    'table' => 'bans',
    'updatePattern' => '%s',
    'insertPattern' => '%s',
    'selectPattern' => '%s',
    'sequenceName' => '',
    'maxlength' => 200,
    'minlength' => NULL,
    'ofPrimaryTable' => true,
    'defaultValue' => NULL,
    'needsQuotes' => true,
  ),
  'ban_ip' => 
  array (
    'name' => 'ban_ip',
    'fieldName' => 'ban_ip',
    'regExp' => NULL,
    'required' => false,
    'requiredInConditions' => false,
    'isPK' => false,
    'isFK' => false,
    'datatype' => 'string',
    'table' => 'bans',
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
  'ban_email' => 
  array (
    'name' => 'ban_email',
    'fieldName' => 'ban_email',
    'regExp' => NULL,
    'required' => false,
    'requiredInConditions' => false,
    'isPK' => false,
    'isFK' => false,
    'datatype' => 'string',
    'table' => 'bans',
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
  'ban_message' => 
  array (
    'name' => 'ban_message',
    'fieldName' => 'ban_message',
    'regExp' => NULL,
    'required' => false,
    'requiredInConditions' => false,
    'isPK' => false,
    'isFK' => false,
    'datatype' => 'string',
    'table' => 'bans',
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
  'ban_expire' => 
  array (
    'name' => 'ban_expire',
    'fieldName' => 'ban_expire',
    'regExp' => NULL,
    'required' => false,
    'requiredInConditions' => false,
    'isPK' => false,
    'isFK' => false,
    'datatype' => 'date',
    'table' => 'bans',
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
);
   public static $_pkFields = array('ban_id');
 
public function __construct($conn){
   parent::__construct($conn);
   $this->_fromClause = ' FROM `'.$this->_conn->prefixTable('bans').'` AS `bans`';
}
   public function getProperties() { return self::$_properties; }
   public function getPrimaryKeyNames() { return self::$_pkFields;}
 
 protected function _getPkWhereClauseForSelect($pk){
   extract($pk);
 return ' WHERE  `bans`.`ban_id`'.'='.intval($ban_id).'';
}
 
protected function _getPkWhereClauseForNonSelect($pk){
   extract($pk);
   return ' where  `ban_id`'.'='.intval($ban_id).'';
}
public function insert ($record){
 if($record->ban_id > 0 ){
    $query = 'INSERT INTO `'.$this->_conn->prefixTable('bans').'` (
`ban_id`,`ban_username`,`ban_ip`,`ban_email`,`ban_message`,`ban_expire`
) VALUES (
'.intval($record->ban_id).', '.($record->ban_username === null ? 'NULL' : $this->_conn->quote($record->ban_username,false)).', '.($record->ban_ip === null ? 'NULL' : $this->_conn->quote($record->ban_ip,false)).', '.($record->ban_email === null ? 'NULL' : $this->_conn->quote($record->ban_email,false)).', '.($record->ban_message === null ? 'NULL' : $this->_conn->quote($record->ban_message,false)).', '.($record->ban_expire === null ? 'NULL' : $this->_conn->quote($record->ban_expire,false)).'
)';
}else{
    $query = 'INSERT INTO `'.$this->_conn->prefixTable('bans').'` (
`ban_username`,`ban_ip`,`ban_email`,`ban_message`,`ban_expire`
) VALUES (
'.($record->ban_username === null ? 'NULL' : $this->_conn->quote($record->ban_username,false)).', '.($record->ban_ip === null ? 'NULL' : $this->_conn->quote($record->ban_ip,false)).', '.($record->ban_email === null ? 'NULL' : $this->_conn->quote($record->ban_email,false)).', '.($record->ban_message === null ? 'NULL' : $this->_conn->quote($record->ban_message,false)).', '.($record->ban_expire === null ? 'NULL' : $this->_conn->quote($record->ban_expire,false)).'
)';
}
   $result = $this->_conn->exec ($query);
   if(!$result)
       return false;
   if($record->ban_id < 1 ) 
       $record->ban_id= $this->_conn->lastInsertId();
    return $result;
}
public function update ($record){
   $query = 'UPDATE `'.$this->_conn->prefixTable('bans').'` SET 
 `ban_username`= '.($record->ban_username === null ? 'NULL' : $this->_conn->quote($record->ban_username,false)).', `ban_ip`= '.($record->ban_ip === null ? 'NULL' : $this->_conn->quote($record->ban_ip,false)).', `ban_email`= '.($record->ban_email === null ? 'NULL' : $this->_conn->quote($record->ban_email,false)).', `ban_message`= '.($record->ban_message === null ? 'NULL' : $this->_conn->quote($record->ban_message,false)).', `ban_expire`= '.($record->ban_expire === null ? 'NULL' : $this->_conn->quote($record->ban_expire,false)).'
 where  `ban_id`'.'='.intval($record->ban_id).'
';
   $result = $this->_conn->exec ($query);
   return $result;
 }
 function deleteExpiry ($expiry){
    $__query = 'DELETE FROM `'.$this->_conn->prefixTable('bans').'` ';
$__query .=' WHERE  `ban_expire` '.'<'.$this->_conn->quote($expiry).' AND `ban_expire` <> \'0\'';
    return $this->_conn->exec ($__query);
}
 function findAllDomains (){
    $__query =  $this->_selectClause.$this->_fromClause.$this->_whereClause;
$__query .=' WHERE  `bans`.`ban_email` <> \'\'';
    $__rs = $this->_conn->query($__query);
    $__rs->setFetchMode(8,'cDaoRecord_havefnubb_Jx_bans_Jx_mysql');
    return $__rs;
}
}
?>