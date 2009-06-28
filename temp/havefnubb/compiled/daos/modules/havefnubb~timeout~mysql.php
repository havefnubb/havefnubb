<?php  require_once ( JELIX_LIB_PATH .'dao/jDaoRecordBase.class.php');
 require_once ( JELIX_LIB_PATH .'dao/jDaoFactoryBase.class.php');

class cDaoRecord_havefnubb_Jx_timeout_Jx_mysql extends jDaoRecordBase {
 public $id;
 public $member_ip;
 public $connected;
 public $idle;
   public function getProperties() { return cDao_havefnubb_Jx_timeout_Jx_mysql::$_properties; }
   public function getPrimaryKeyNames() { return cDao_havefnubb_Jx_timeout_Jx_mysql::$_pkFields; }
}

class cDao_havefnubb_Jx_timeout_Jx_mysql extends jDaoFactoryBase {
   protected $_tables = array (
  'connected' => 
  array (
    'name' => 'connected',
    'realname' => 'connected',
    'pk' => 
    array (
      0 => 'id_user',
    ),
    'fields' => 
    array (
      0 => 'id',
      1 => 'member_ip',
      2 => 'connected',
      3 => 'idle',
    ),
  ),
);
   protected $_primaryTable = 'connected';
   protected $_selectClause='SELECT `connected`.`id_user` as `id`, `connected`.`member_ip`, `connected`.`connected`, `connected`.`idle`';
   protected $_fromClause;
   protected $_whereClause='';
   protected $_DaoRecordClassName='cDaoRecord_havefnubb_Jx_timeout_Jx_mysql';
   protected $_daoSelector = 'havefnubb~timeout';
   public static $_properties = array (
  'id' => 
  array (
    'name' => 'id',
    'fieldName' => 'id_user',
    'regExp' => NULL,
    'required' => false,
    'requiredInConditions' => true,
    'isPK' => true,
    'isFK' => false,
    'datatype' => 'autoincrement',
    'table' => 'connected',
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
  'member_ip' => 
  array (
    'name' => 'member_ip',
    'fieldName' => 'member_ip',
    'regExp' => NULL,
    'required' => false,
    'requiredInConditions' => false,
    'isPK' => false,
    'isFK' => false,
    'datatype' => 'string',
    'table' => 'connected',
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
  'connected' => 
  array (
    'name' => 'connected',
    'fieldName' => 'connected',
    'regExp' => NULL,
    'required' => false,
    'requiredInConditions' => false,
    'isPK' => false,
    'isFK' => false,
    'datatype' => 'integer',
    'table' => 'connected',
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
  'idle' => 
  array (
    'name' => 'idle',
    'fieldName' => 'idle',
    'regExp' => NULL,
    'required' => false,
    'requiredInConditions' => false,
    'isPK' => false,
    'isFK' => false,
    'datatype' => 'integer',
    'table' => 'connected',
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
   public static $_pkFields = array('id');
 
public function __construct($conn){
   parent::__construct($conn);
   $this->_fromClause = ' FROM `'.$this->_conn->prefixTable('connected').'` AS `connected`';
}
   public function getProperties() { return self::$_properties; }
   public function getPrimaryKeyNames() { return self::$_pkFields;}
 
 protected function _getPkWhereClauseForSelect($pk){
   extract($pk);
 return ' WHERE  `connected`.`id_user`'.'='.intval($id).'';
}
 
protected function _getPkWhereClauseForNonSelect($pk){
   extract($pk);
   return ' where  `id_user`'.'='.intval($id).'';
}
public function insert ($record){
 if($record->id > 0 ){
    $query = 'INSERT INTO `'.$this->_conn->prefixTable('connected').'` (
`id_user`,`member_ip`,`connected`,`idle`
) VALUES (
'.intval($record->id).', '.($record->member_ip === null ? 'NULL' : $this->_conn->quote($record->member_ip,false)).', '.($record->connected === null ? 'NULL' : intval($record->connected)).', '.($record->idle === null ? 'NULL' : intval($record->idle)).'
)';
}else{
    $query = 'INSERT INTO `'.$this->_conn->prefixTable('connected').'` (
`member_ip`,`connected`,`idle`
) VALUES (
'.($record->member_ip === null ? 'NULL' : $this->_conn->quote($record->member_ip,false)).', '.($record->connected === null ? 'NULL' : intval($record->connected)).', '.($record->idle === null ? 'NULL' : intval($record->idle)).'
)';
}
   $result = $this->_conn->exec ($query);
   if(!$result)
       return false;
   if($record->id < 1 ) 
       $record->id= $this->_conn->lastInsertId();
    return $result;
}
public function update ($record){
   $query = 'UPDATE `'.$this->_conn->prefixTable('connected').'` SET 
 `member_ip`= '.($record->member_ip === null ? 'NULL' : $this->_conn->quote($record->member_ip,false)).', `connected`= '.($record->connected === null ? 'NULL' : intval($record->connected)).', `idle`= '.($record->idle === null ? 'NULL' : intval($record->idle)).'
 where  `id_user`'.'='.intval($record->id).'
';
   $result = $this->_conn->exec ($query);
   return $result;
 }
 function findAllConnected ($time){
    $__query =  $this->_selectClause.$this->_fromClause.$this->_whereClause;
$__query .=' WHERE  `connected`.`connected` '.'<'.intval($time).'';
    $__rs = $this->_conn->query($__query);
    $__rs->setFetchMode(8,'cDaoRecord_havefnubb_Jx_timeout_Jx_mysql');
    return $__rs;
}
 function getConnectedByIdUser ($time, $id_user){
    $__query =  $this->_selectClause.$this->_fromClause.$this->_whereClause;
$__query .=' WHERE  `connected`.`connected` '.'<'.intval($time).' AND `connected`.`id_user` '.'='.intval($id_user).'';
    $__rs = $this->_conn->limitQuery($__query,0,1);
    $__rs->setFetchMode(8,'cDaoRecord_havefnubb_Jx_timeout_Jx_mysql');
    return $__rs->fetch();
}
}
?>