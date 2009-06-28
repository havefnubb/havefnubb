<?php  require_once ( JELIX_LIB_PATH .'dao/jDaoRecordBase.class.php');
 require_once ( JELIX_LIB_PATH .'dao/jDaoFactoryBase.class.php');

class cDaoRecord_havefnubb_Jx_member_Jx_mysql extends jDaoRecordBase {
 public $id;
 public $login;
 public $password;
 public $status;
 public $email;
 public $nickname;
 public $keyactivate;
 public $request_date;
 public $member_website;
 public $member_firstname;
 public $member_birth='1980-01-01';
 public $member_country;
 public $member_town;
 public $member_comment;
 public $member_avatar;
 public $member_xfire;
 public $member_icq;
 public $member_hotmail;
 public $member_yim;
 public $member_aol;
 public $member_gtalk;
 public $member_jabber;
 public $member_proc;
 public $member_mb;
 public $member_card;
 public $member_ram;
 public $member_display;
 public $member_screen;
 public $member_mouse;
 public $member_keyb;
 public $member_os;
 public $member_connection;
 public $member_last_connect;
 public $member_show_email;
 public $member_language;
 public $nb_msg;
 public $member_last_post;
 public $member_created;
 public $member_ip;
 public $connected;
 public $idle;
   public function getProperties() { return cDao_havefnubb_Jx_member_Jx_mysql::$_properties; }
   public function getPrimaryKeyNames() { return cDao_havefnubb_Jx_member_Jx_mysql::$_pkFields; }
}

class cDao_havefnubb_Jx_member_Jx_mysql extends jDaoFactoryBase {
   protected $_tables = array (
  'usr' => 
  array (
    'name' => 'usr',
    'realname' => 'member',
    'pk' => 
    array (
      0 => 'member_login',
    ),
    'fields' => 
    array (
      0 => 'id',
      1 => 'login',
      2 => 'password',
      3 => 'status',
      4 => 'email',
      5 => 'nickname',
      6 => 'keyactivate',
      7 => 'request_date',
      8 => 'member_website',
      9 => 'member_firstname',
      10 => 'member_birth',
      11 => 'member_country',
      12 => 'member_town',
      13 => 'member_comment',
      14 => 'member_avatar',
      15 => 'member_xfire',
      16 => 'member_icq',
      17 => 'member_hotmail',
      18 => 'member_yim',
      19 => 'member_aol',
      20 => 'member_gtalk',
      21 => 'member_jabber',
      22 => 'member_proc',
      23 => 'member_mb',
      24 => 'member_card',
      25 => 'member_ram',
      26 => 'member_display',
      27 => 'member_screen',
      28 => 'member_mouse',
      29 => 'member_keyb',
      30 => 'member_os',
      31 => 'member_connection',
      32 => 'member_last_connect',
      33 => 'member_show_email',
      34 => 'member_language',
      35 => 'nb_msg',
      36 => 'member_last_post',
      37 => 'member_created',
    ),
  ),
  'connected' => 
  array (
    'name' => 'connected',
    'realname' => 'connected',
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
      0 => 'member_ip',
      1 => 'connected',
      2 => 'idle',
    ),
  ),
);
   protected $_primaryTable = 'usr';
   protected $_selectClause='SELECT `usr`.`id_user` as `id`, `usr`.`member_login` as `login`, `usr`.`member_password` as `password`, `usr`.`member_status` as `status`, `usr`.`member_email` as `email`, `usr`.`member_nickname` as `nickname`, `usr`.`member_keyactivate` as `keyactivate`, `usr`.`member_request_date` as `request_date`, `usr`.`member_website`, `usr`.`member_firstname`, `usr`.`member_birth`, `usr`.`member_country`, `usr`.`member_town`, `usr`.`member_comment`, `usr`.`member_avatar`, `usr`.`member_xfire`, `usr`.`member_icq`, `usr`.`member_hotmail`, `usr`.`member_yim`, `usr`.`member_aol`, `usr`.`member_gtalk`, `usr`.`member_jabber`, `usr`.`member_proc`, `usr`.`member_mb`, `usr`.`member_card`, `usr`.`member_ram`, `usr`.`member_display`, `usr`.`member_screen`, `usr`.`member_mouse`, `usr`.`member_keyb`, `usr`.`member_os`, `usr`.`member_connection`, `usr`.`member_last_connect`, `usr`.`member_show_email`, `usr`.`member_language`, `usr`.`member_nb_msg` as `nb_msg`, `usr`.`member_last_post`, `usr`.`member_created`, `connected`.`member_ip`, `connected`.`connected`, `connected`.`idle`';
   protected $_fromClause;
   protected $_whereClause='';
   protected $_DaoRecordClassName='cDaoRecord_havefnubb_Jx_member_Jx_mysql';
   protected $_daoSelector = 'havefnubb~member';
   public static $_properties = array (
  'id' => 
  array (
    'name' => 'id',
    'fieldName' => 'id_user',
    'regExp' => NULL,
    'required' => false,
    'requiredInConditions' => true,
    'isPK' => false,
    'isFK' => false,
    'datatype' => 'autoincrement',
    'table' => 'usr',
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
  'login' => 
  array (
    'name' => 'login',
    'fieldName' => 'member_login',
    'regExp' => NULL,
    'required' => true,
    'requiredInConditions' => true,
    'isPK' => true,
    'isFK' => false,
    'datatype' => 'string',
    'table' => 'usr',
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
  'password' => 
  array (
    'name' => 'password',
    'fieldName' => 'member_password',
    'regExp' => NULL,
    'required' => false,
    'requiredInConditions' => false,
    'isPK' => false,
    'isFK' => false,
    'datatype' => 'string',
    'table' => 'usr',
    'updatePattern' => '',
    'insertPattern' => '%s',
    'selectPattern' => '%s',
    'sequenceName' => '',
    'maxlength' => 50,
    'minlength' => NULL,
    'ofPrimaryTable' => true,
    'defaultValue' => NULL,
    'needsQuotes' => true,
  ),
  'status' => 
  array (
    'name' => 'status',
    'fieldName' => 'member_status',
    'regExp' => NULL,
    'required' => false,
    'requiredInConditions' => false,
    'isPK' => false,
    'isFK' => false,
    'datatype' => 'integer',
    'table' => 'usr',
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
  'email' => 
  array (
    'name' => 'email',
    'fieldName' => 'member_email',
    'regExp' => NULL,
    'required' => true,
    'requiredInConditions' => true,
    'isPK' => false,
    'isFK' => false,
    'datatype' => 'string',
    'table' => 'usr',
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
  'nickname' => 
  array (
    'name' => 'nickname',
    'fieldName' => 'member_nickname',
    'regExp' => NULL,
    'required' => true,
    'requiredInConditions' => true,
    'isPK' => false,
    'isFK' => false,
    'datatype' => 'string',
    'table' => 'usr',
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
  'keyactivate' => 
  array (
    'name' => 'keyactivate',
    'fieldName' => 'member_keyactivate',
    'regExp' => NULL,
    'required' => true,
    'requiredInConditions' => true,
    'isPK' => false,
    'isFK' => false,
    'datatype' => 'string',
    'table' => 'usr',
    'updatePattern' => '%s',
    'insertPattern' => '%s',
    'selectPattern' => '%s',
    'sequenceName' => '',
    'maxlength' => 10,
    'minlength' => NULL,
    'ofPrimaryTable' => true,
    'defaultValue' => NULL,
    'needsQuotes' => true,
  ),
  'request_date' => 
  array (
    'name' => 'request_date',
    'fieldName' => 'member_request_date',
    'regExp' => NULL,
    'required' => false,
    'requiredInConditions' => false,
    'isPK' => false,
    'isFK' => false,
    'datatype' => 'datetime',
    'table' => 'usr',
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
  'member_firstname' => 
  array (
    'name' => 'member_firstname',
    'fieldName' => 'member_firstname',
    'regExp' => NULL,
    'required' => false,
    'requiredInConditions' => false,
    'isPK' => false,
    'isFK' => false,
    'datatype' => 'string',
    'table' => 'usr',
    'updatePattern' => '%s',
    'insertPattern' => '%s',
    'selectPattern' => '%s',
    'sequenceName' => '',
    'maxlength' => 40,
    'minlength' => NULL,
    'ofPrimaryTable' => true,
    'defaultValue' => NULL,
    'needsQuotes' => true,
  ),
  'member_birth' => 
  array (
    'name' => 'member_birth',
    'fieldName' => 'member_birth',
    'regExp' => NULL,
    'required' => true,
    'requiredInConditions' => true,
    'isPK' => false,
    'isFK' => false,
    'datatype' => 'date',
    'table' => 'usr',
    'updatePattern' => '%s',
    'insertPattern' => '%s',
    'selectPattern' => '%s',
    'sequenceName' => '',
    'maxlength' => NULL,
    'minlength' => NULL,
    'ofPrimaryTable' => true,
    'defaultValue' => '1980-01-01',
    'needsQuotes' => true,
  ),
  'member_country' => 
  array (
    'name' => 'member_country',
    'fieldName' => 'member_country',
    'regExp' => NULL,
    'required' => true,
    'requiredInConditions' => true,
    'isPK' => false,
    'isFK' => false,
    'datatype' => 'string',
    'table' => 'usr',
    'updatePattern' => '%s',
    'insertPattern' => '%s',
    'selectPattern' => '%s',
    'sequenceName' => '',
    'maxlength' => 100,
    'minlength' => NULL,
    'ofPrimaryTable' => true,
    'defaultValue' => NULL,
    'needsQuotes' => true,
  ),
  'member_town' => 
  array (
    'name' => 'member_town',
    'fieldName' => 'member_town',
    'regExp' => NULL,
    'required' => true,
    'requiredInConditions' => true,
    'isPK' => false,
    'isFK' => false,
    'datatype' => 'string',
    'table' => 'usr',
    'updatePattern' => '%s',
    'insertPattern' => '%s',
    'selectPattern' => '%s',
    'sequenceName' => '',
    'maxlength' => 100,
    'minlength' => NULL,
    'ofPrimaryTable' => true,
    'defaultValue' => NULL,
    'needsQuotes' => true,
  ),
  'member_comment' => 
  array (
    'name' => 'member_comment',
    'fieldName' => 'member_comment',
    'regExp' => NULL,
    'required' => true,
    'requiredInConditions' => true,
    'isPK' => false,
    'isFK' => false,
    'datatype' => 'string',
    'table' => 'usr',
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
  'member_xfire' => 
  array (
    'name' => 'member_xfire',
    'fieldName' => 'member_xfire',
    'regExp' => NULL,
    'required' => false,
    'requiredInConditions' => false,
    'isPK' => false,
    'isFK' => false,
    'datatype' => 'string',
    'table' => 'usr',
    'updatePattern' => '%s',
    'insertPattern' => '%s',
    'selectPattern' => '%s',
    'sequenceName' => '',
    'maxlength' => 80,
    'minlength' => NULL,
    'ofPrimaryTable' => true,
    'defaultValue' => NULL,
    'needsQuotes' => true,
  ),
  'member_icq' => 
  array (
    'name' => 'member_icq',
    'fieldName' => 'member_icq',
    'regExp' => NULL,
    'required' => false,
    'requiredInConditions' => false,
    'isPK' => false,
    'isFK' => false,
    'datatype' => 'string',
    'table' => 'usr',
    'updatePattern' => '%s',
    'insertPattern' => '%s',
    'selectPattern' => '%s',
    'sequenceName' => '',
    'maxlength' => 80,
    'minlength' => NULL,
    'ofPrimaryTable' => true,
    'defaultValue' => NULL,
    'needsQuotes' => true,
  ),
  'member_hotmail' => 
  array (
    'name' => 'member_hotmail',
    'fieldName' => 'member_hotmail',
    'regExp' => NULL,
    'required' => false,
    'requiredInConditions' => false,
    'isPK' => false,
    'isFK' => false,
    'datatype' => 'string',
    'table' => 'usr',
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
  'member_yim' => 
  array (
    'name' => 'member_yim',
    'fieldName' => 'member_yim',
    'regExp' => NULL,
    'required' => false,
    'requiredInConditions' => false,
    'isPK' => false,
    'isFK' => false,
    'datatype' => 'string',
    'table' => 'usr',
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
  'member_aol' => 
  array (
    'name' => 'member_aol',
    'fieldName' => 'member_aol',
    'regExp' => NULL,
    'required' => false,
    'requiredInConditions' => false,
    'isPK' => false,
    'isFK' => false,
    'datatype' => 'string',
    'table' => 'usr',
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
  'member_gtalk' => 
  array (
    'name' => 'member_gtalk',
    'fieldName' => 'member_gtalk',
    'regExp' => NULL,
    'required' => false,
    'requiredInConditions' => false,
    'isPK' => false,
    'isFK' => false,
    'datatype' => 'string',
    'table' => 'usr',
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
  'member_jabber' => 
  array (
    'name' => 'member_jabber',
    'fieldName' => 'member_jabber',
    'regExp' => NULL,
    'required' => false,
    'requiredInConditions' => false,
    'isPK' => false,
    'isFK' => false,
    'datatype' => 'string',
    'table' => 'usr',
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
  'member_proc' => 
  array (
    'name' => 'member_proc',
    'fieldName' => 'member_proc',
    'regExp' => NULL,
    'required' => false,
    'requiredInConditions' => false,
    'isPK' => false,
    'isFK' => false,
    'datatype' => 'string',
    'table' => 'usr',
    'updatePattern' => '%s',
    'insertPattern' => '%s',
    'selectPattern' => '%s',
    'sequenceName' => '',
    'maxlength' => 40,
    'minlength' => NULL,
    'ofPrimaryTable' => true,
    'defaultValue' => NULL,
    'needsQuotes' => true,
  ),
  'member_mb' => 
  array (
    'name' => 'member_mb',
    'fieldName' => 'member_mb',
    'regExp' => NULL,
    'required' => false,
    'requiredInConditions' => false,
    'isPK' => false,
    'isFK' => false,
    'datatype' => 'string',
    'table' => 'usr',
    'updatePattern' => '%s',
    'insertPattern' => '%s',
    'selectPattern' => '%s',
    'sequenceName' => '',
    'maxlength' => 40,
    'minlength' => NULL,
    'ofPrimaryTable' => true,
    'defaultValue' => NULL,
    'needsQuotes' => true,
  ),
  'member_card' => 
  array (
    'name' => 'member_card',
    'fieldName' => 'member_card',
    'regExp' => NULL,
    'required' => false,
    'requiredInConditions' => false,
    'isPK' => false,
    'isFK' => false,
    'datatype' => 'string',
    'table' => 'usr',
    'updatePattern' => '%s',
    'insertPattern' => '%s',
    'selectPattern' => '%s',
    'sequenceName' => '',
    'maxlength' => 40,
    'minlength' => NULL,
    'ofPrimaryTable' => true,
    'defaultValue' => NULL,
    'needsQuotes' => true,
  ),
  'member_ram' => 
  array (
    'name' => 'member_ram',
    'fieldName' => 'member_ram',
    'regExp' => NULL,
    'required' => false,
    'requiredInConditions' => false,
    'isPK' => false,
    'isFK' => false,
    'datatype' => 'string',
    'table' => 'usr',
    'updatePattern' => '%s',
    'insertPattern' => '%s',
    'selectPattern' => '%s',
    'sequenceName' => '',
    'maxlength' => 40,
    'minlength' => NULL,
    'ofPrimaryTable' => true,
    'defaultValue' => NULL,
    'needsQuotes' => true,
  ),
  'member_display' => 
  array (
    'name' => 'member_display',
    'fieldName' => 'member_display',
    'regExp' => NULL,
    'required' => false,
    'requiredInConditions' => false,
    'isPK' => false,
    'isFK' => false,
    'datatype' => 'string',
    'table' => 'usr',
    'updatePattern' => '%s',
    'insertPattern' => '%s',
    'selectPattern' => '%s',
    'sequenceName' => '',
    'maxlength' => 40,
    'minlength' => NULL,
    'ofPrimaryTable' => true,
    'defaultValue' => NULL,
    'needsQuotes' => true,
  ),
  'member_screen' => 
  array (
    'name' => 'member_screen',
    'fieldName' => 'member_screen',
    'regExp' => NULL,
    'required' => false,
    'requiredInConditions' => false,
    'isPK' => false,
    'isFK' => false,
    'datatype' => 'string',
    'table' => 'usr',
    'updatePattern' => '%s',
    'insertPattern' => '%s',
    'selectPattern' => '%s',
    'sequenceName' => '',
    'maxlength' => 40,
    'minlength' => NULL,
    'ofPrimaryTable' => true,
    'defaultValue' => NULL,
    'needsQuotes' => true,
  ),
  'member_mouse' => 
  array (
    'name' => 'member_mouse',
    'fieldName' => 'member_mouse',
    'regExp' => NULL,
    'required' => false,
    'requiredInConditions' => false,
    'isPK' => false,
    'isFK' => false,
    'datatype' => 'string',
    'table' => 'usr',
    'updatePattern' => '%s',
    'insertPattern' => '%s',
    'selectPattern' => '%s',
    'sequenceName' => '',
    'maxlength' => 40,
    'minlength' => NULL,
    'ofPrimaryTable' => true,
    'defaultValue' => NULL,
    'needsQuotes' => true,
  ),
  'member_keyb' => 
  array (
    'name' => 'member_keyb',
    'fieldName' => 'member_keyb',
    'regExp' => NULL,
    'required' => false,
    'requiredInConditions' => false,
    'isPK' => false,
    'isFK' => false,
    'datatype' => 'string',
    'table' => 'usr',
    'updatePattern' => '%s',
    'insertPattern' => '%s',
    'selectPattern' => '%s',
    'sequenceName' => '',
    'maxlength' => 40,
    'minlength' => NULL,
    'ofPrimaryTable' => true,
    'defaultValue' => NULL,
    'needsQuotes' => true,
  ),
  'member_os' => 
  array (
    'name' => 'member_os',
    'fieldName' => 'member_os',
    'regExp' => NULL,
    'required' => false,
    'requiredInConditions' => false,
    'isPK' => false,
    'isFK' => false,
    'datatype' => 'string',
    'table' => 'usr',
    'updatePattern' => '%s',
    'insertPattern' => '%s',
    'selectPattern' => '%s',
    'sequenceName' => '',
    'maxlength' => 40,
    'minlength' => NULL,
    'ofPrimaryTable' => true,
    'defaultValue' => NULL,
    'needsQuotes' => true,
  ),
  'member_connection' => 
  array (
    'name' => 'member_connection',
    'fieldName' => 'member_connection',
    'regExp' => NULL,
    'required' => false,
    'requiredInConditions' => false,
    'isPK' => false,
    'isFK' => false,
    'datatype' => 'string',
    'table' => 'usr',
    'updatePattern' => '%s',
    'insertPattern' => '%s',
    'selectPattern' => '%s',
    'sequenceName' => '',
    'maxlength' => 40,
    'minlength' => NULL,
    'ofPrimaryTable' => true,
    'defaultValue' => NULL,
    'needsQuotes' => true,
  ),
  'member_last_connect' => 
  array (
    'name' => 'member_last_connect',
    'fieldName' => 'member_last_connect',
    'regExp' => NULL,
    'required' => false,
    'requiredInConditions' => false,
    'isPK' => false,
    'isFK' => false,
    'datatype' => 'integer',
    'table' => 'usr',
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
  'member_show_email' => 
  array (
    'name' => 'member_show_email',
    'fieldName' => 'member_show_email',
    'regExp' => NULL,
    'required' => false,
    'requiredInConditions' => false,
    'isPK' => false,
    'isFK' => false,
    'datatype' => 'string',
    'table' => 'usr',
    'updatePattern' => '%s',
    'insertPattern' => '%s',
    'selectPattern' => '%s',
    'sequenceName' => '',
    'maxlength' => 1,
    'minlength' => NULL,
    'ofPrimaryTable' => true,
    'defaultValue' => NULL,
    'needsQuotes' => true,
  ),
  'member_language' => 
  array (
    'name' => 'member_language',
    'fieldName' => 'member_language',
    'regExp' => NULL,
    'required' => false,
    'requiredInConditions' => false,
    'isPK' => false,
    'isFK' => false,
    'datatype' => 'string',
    'table' => 'usr',
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
  'member_created' => 
  array (
    'name' => 'member_created',
    'fieldName' => 'member_created',
    'regExp' => NULL,
    'required' => false,
    'requiredInConditions' => false,
    'isPK' => false,
    'isFK' => false,
    'datatype' => 'datetime',
    'table' => 'usr',
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
    'updatePattern' => '',
    'insertPattern' => '',
    'selectPattern' => '%s',
    'sequenceName' => '',
    'maxlength' => NULL,
    'minlength' => NULL,
    'ofPrimaryTable' => false,
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
   public static $_pkFields = array('login');
 
public function __construct($conn){
   parent::__construct($conn);
   $this->_fromClause = ' FROM `'.$this->_conn->prefixTable('member').'` AS `usr` LEFT JOIN `'.$this->_conn->prefixTable('connected').'` AS `connected` ON ( `usr`.`id_user`=`connected`.`id_user`)';
}
   public function getProperties() { return self::$_properties; }
   public function getPrimaryKeyNames() { return self::$_pkFields;}
 
 protected function _getPkWhereClauseForSelect($pk){
   extract($pk);
 return ' WHERE  `usr`.`member_login`'.'='.$this->_conn->quote($login).'';
}
 
protected function _getPkWhereClauseForNonSelect($pk){
   extract($pk);
   return ' where  `member_login`'.'='.$this->_conn->quote($login).'';
}
public function insert ($record){
    $query = 'INSERT INTO `'.$this->_conn->prefixTable('member').'` (
`id_user`,`member_login`,`member_password`,`member_status`,`member_email`,`member_nickname`,`member_keyactivate`,`member_request_date`,`member_website`,`member_firstname`,`member_birth`,`member_country`,`member_town`,`member_comment`,`member_avatar`,`member_xfire`,`member_icq`,`member_hotmail`,`member_yim`,`member_aol`,`member_gtalk`,`member_jabber`,`member_proc`,`member_mb`,`member_card`,`member_ram`,`member_display`,`member_screen`,`member_mouse`,`member_keyb`,`member_os`,`member_connection`,`member_last_connect`,`member_show_email`,`member_language`,`member_nb_msg`,`member_last_post`,`member_created`
) VALUES (
'.intval($record->id).', '.($record->login === null ? 'NULL' : $this->_conn->quote($record->login,false)).', '.($record->password === null ? 'NULL' : $this->_conn->quote($record->password,false)).', '.($record->status === null ? 'NULL' : intval($record->status)).', '.($record->email === null ? 'NULL' : $this->_conn->quote($record->email,false)).', '.($record->nickname === null ? 'NULL' : $this->_conn->quote($record->nickname,false)).', '.($record->keyactivate === null ? 'NULL' : $this->_conn->quote($record->keyactivate,false)).', '.($record->request_date === null ? 'NULL' : $this->_conn->quote($record->request_date,false)).', '.($record->member_website === null ? 'NULL' : $this->_conn->quote($record->member_website,false)).', '.($record->member_firstname === null ? 'NULL' : $this->_conn->quote($record->member_firstname,false)).', '.($record->member_birth === null ? 'NULL' : $this->_conn->quote($record->member_birth,false)).', '.($record->member_country === null ? 'NULL' : $this->_conn->quote($record->member_country,false)).', '.($record->member_town === null ? 'NULL' : $this->_conn->quote($record->member_town,false)).', '.($record->member_comment === null ? 'NULL' : $this->_conn->quote($record->member_comment,false)).', '.($record->member_avatar === null ? 'NULL' : $this->_conn->quote($record->member_avatar,false)).', '.($record->member_xfire === null ? 'NULL' : $this->_conn->quote($record->member_xfire,false)).', '.($record->member_icq === null ? 'NULL' : $this->_conn->quote($record->member_icq,false)).', '.($record->member_hotmail === null ? 'NULL' : $this->_conn->quote($record->member_hotmail,false)).', '.($record->member_yim === null ? 'NULL' : $this->_conn->quote($record->member_yim,false)).', '.($record->member_aol === null ? 'NULL' : $this->_conn->quote($record->member_aol,false)).', '.($record->member_gtalk === null ? 'NULL' : $this->_conn->quote($record->member_gtalk,false)).', '.($record->member_jabber === null ? 'NULL' : $this->_conn->quote($record->member_jabber,false)).', '.($record->member_proc === null ? 'NULL' : $this->_conn->quote($record->member_proc,false)).', '.($record->member_mb === null ? 'NULL' : $this->_conn->quote($record->member_mb,false)).', '.($record->member_card === null ? 'NULL' : $this->_conn->quote($record->member_card,false)).', '.($record->member_ram === null ? 'NULL' : $this->_conn->quote($record->member_ram,false)).', '.($record->member_display === null ? 'NULL' : $this->_conn->quote($record->member_display,false)).', '.($record->member_screen === null ? 'NULL' : $this->_conn->quote($record->member_screen,false)).', '.($record->member_mouse === null ? 'NULL' : $this->_conn->quote($record->member_mouse,false)).', '.($record->member_keyb === null ? 'NULL' : $this->_conn->quote($record->member_keyb,false)).', '.($record->member_os === null ? 'NULL' : $this->_conn->quote($record->member_os,false)).', '.($record->member_connection === null ? 'NULL' : $this->_conn->quote($record->member_connection,false)).', '.($record->member_last_connect === null ? 'NULL' : intval($record->member_last_connect)).', '.($record->member_show_email === null ? 'NULL' : $this->_conn->quote($record->member_show_email,false)).', '.($record->member_language === null ? 'NULL' : $this->_conn->quote($record->member_language,false)).', '.($record->nb_msg === null ? 'NULL' : intval($record->nb_msg)).', '.($record->member_last_post === null ? 'NULL' : intval($record->member_last_post)).', '.($record->member_created === null ? 'NULL' : $this->_conn->quote($record->member_created,false)).'
)';
   $result = $this->_conn->exec ($query);
  $query ='SELECT `id_user` as `id` FROM `'.$this->_conn->prefixTable('member').'` WHERE  `member_login`'.'='.$this->_conn->quote($record->login).'';
  $rs  =  $this->_conn->query ($query);
  $newrecord =  $rs->fetch ();
  $record->id = $newrecord->id;
    return $result;
}
public function update ($record){
   $query = 'UPDATE `'.$this->_conn->prefixTable('member').'` SET 
 `id_user`= '.intval($record->id).', `member_status`= '.($record->status === null ? 'NULL' : intval($record->status)).', `member_email`= '.($record->email === null ? 'NULL' : $this->_conn->quote($record->email,false)).', `member_nickname`= '.($record->nickname === null ? 'NULL' : $this->_conn->quote($record->nickname,false)).', `member_keyactivate`= '.($record->keyactivate === null ? 'NULL' : $this->_conn->quote($record->keyactivate,false)).', `member_request_date`= '.($record->request_date === null ? 'NULL' : $this->_conn->quote($record->request_date,false)).', `member_website`= '.($record->member_website === null ? 'NULL' : $this->_conn->quote($record->member_website,false)).', `member_firstname`= '.($record->member_firstname === null ? 'NULL' : $this->_conn->quote($record->member_firstname,false)).', `member_birth`= '.($record->member_birth === null ? 'NULL' : $this->_conn->quote($record->member_birth,false)).', `member_country`= '.($record->member_country === null ? 'NULL' : $this->_conn->quote($record->member_country,false)).', `member_town`= '.($record->member_town === null ? 'NULL' : $this->_conn->quote($record->member_town,false)).', `member_comment`= '.($record->member_comment === null ? 'NULL' : $this->_conn->quote($record->member_comment,false)).', `member_avatar`= '.($record->member_avatar === null ? 'NULL' : $this->_conn->quote($record->member_avatar,false)).', `member_xfire`= '.($record->member_xfire === null ? 'NULL' : $this->_conn->quote($record->member_xfire,false)).', `member_icq`= '.($record->member_icq === null ? 'NULL' : $this->_conn->quote($record->member_icq,false)).', `member_hotmail`= '.($record->member_hotmail === null ? 'NULL' : $this->_conn->quote($record->member_hotmail,false)).', `member_yim`= '.($record->member_yim === null ? 'NULL' : $this->_conn->quote($record->member_yim,false)).', `member_aol`= '.($record->member_aol === null ? 'NULL' : $this->_conn->quote($record->member_aol,false)).', `member_gtalk`= '.($record->member_gtalk === null ? 'NULL' : $this->_conn->quote($record->member_gtalk,false)).', `member_jabber`= '.($record->member_jabber === null ? 'NULL' : $this->_conn->quote($record->member_jabber,false)).', `member_proc`= '.($record->member_proc === null ? 'NULL' : $this->_conn->quote($record->member_proc,false)).', `member_mb`= '.($record->member_mb === null ? 'NULL' : $this->_conn->quote($record->member_mb,false)).', `member_card`= '.($record->member_card === null ? 'NULL' : $this->_conn->quote($record->member_card,false)).', `member_ram`= '.($record->member_ram === null ? 'NULL' : $this->_conn->quote($record->member_ram,false)).', `member_display`= '.($record->member_display === null ? 'NULL' : $this->_conn->quote($record->member_display,false)).', `member_screen`= '.($record->member_screen === null ? 'NULL' : $this->_conn->quote($record->member_screen,false)).', `member_mouse`= '.($record->member_mouse === null ? 'NULL' : $this->_conn->quote($record->member_mouse,false)).', `member_keyb`= '.($record->member_keyb === null ? 'NULL' : $this->_conn->quote($record->member_keyb,false)).', `member_os`= '.($record->member_os === null ? 'NULL' : $this->_conn->quote($record->member_os,false)).', `member_connection`= '.($record->member_connection === null ? 'NULL' : $this->_conn->quote($record->member_connection,false)).', `member_last_connect`= '.($record->member_last_connect === null ? 'NULL' : intval($record->member_last_connect)).', `member_show_email`= '.($record->member_show_email === null ? 'NULL' : $this->_conn->quote($record->member_show_email,false)).', `member_language`= '.($record->member_language === null ? 'NULL' : $this->_conn->quote($record->member_language,false)).', `member_nb_msg`= '.($record->nb_msg === null ? 'NULL' : intval($record->nb_msg)).', `member_last_post`= '.($record->member_last_post === null ? 'NULL' : intval($record->member_last_post)).', `member_created`= '.($record->member_created === null ? 'NULL' : $this->_conn->quote($record->member_created,false)).'
 where  `member_login`'.'='.$this->_conn->quote($record->login).'
';
   $result = $this->_conn->exec ($query);
  $query ='SELECT `id_user` as `id`, `member_password` as `password` FROM `'.$this->_conn->prefixTable('member').'` WHERE  `member_login`'.'='.$this->_conn->quote($record->login).'';
  $rs  =  $this->_conn->query ($query, jDbConnection::FETCH_INTO, $record);
  $record =  $rs->fetch ();
   return $result;
 }
 function getByLoginPassword ($login, $password){
    $__query =  $this->_selectClause.$this->_fromClause.$this->_whereClause;
$__query .=' WHERE  `usr`.`member_login` '.'='.$this->_conn->quote($login).' AND `usr`.`member_password` '.($password === null ? 'IS NULL' : '='.$this->_conn->quote($password,false)).'';
    $__rs = $this->_conn->limitQuery($__query,0,1);
    $__rs->setFetchMode(8,'cDaoRecord_havefnubb_Jx_member_Jx_mysql');
    return $__rs->fetch();
}
 function getByLogin ($login){
    $__query =  $this->_selectClause.$this->_fromClause.$this->_whereClause;
$__query .=' WHERE  `usr`.`member_login` '.'='.$this->_conn->quote($login).'';
    $__rs = $this->_conn->limitQuery($__query,0,1);
    $__rs->setFetchMode(8,'cDaoRecord_havefnubb_Jx_member_Jx_mysql');
    return $__rs->fetch();
}
 function verifyNickname ($login, $nickname){
    $__query =  $this->_selectClause.$this->_fromClause.$this->_whereClause;
$__query .=' WHERE  `usr`.`member_login` '.'<>'.$this->_conn->quote($login).' AND `usr`.`member_nickname` '.'='.$this->_conn->quote($nickname).'';
    $__rs = $this->_conn->limitQuery($__query,0,1);
    $__rs->setFetchMode(8,'cDaoRecord_havefnubb_Jx_member_Jx_mysql');
    return $__rs->fetch();
}
 function updatePassword ($login, $password){
    $__query = 'UPDATE `'.$this->_conn->prefixTable('member').'` SET 
 `member_password`= '.($password === null ? 'NULL' : $this->_conn->quote($password,false)).'';
$__query .=' WHERE  `member_login` '.'='.$this->_conn->quote($login).'';
    return $this->_conn->exec ($__query);
}
 function deleteByLogin ($login){
    $__query = 'DELETE FROM `'.$this->_conn->prefixTable('member').'` ';
$__query .=' WHERE  `member_login` '.'='.$this->_conn->quote($login).'';
    return $this->_conn->exec ($__query);
}
 function findByLogin ($pattern){
    $__query =  $this->_selectClause.$this->_fromClause.$this->_whereClause;
$__query .=' WHERE  `usr`.`member_login` '.'LIKE'.$this->_conn->quote($pattern).' ORDER BY `usr`.`member_login` asc';
    $__rs = $this->_conn->query($__query);
    $__rs->setFetchMode(8,'cDaoRecord_havefnubb_Jx_member_Jx_mysql');
    return $__rs;
}
 function findAll (){
    $__query =  $this->_selectClause.$this->_fromClause.$this->_whereClause;
$__query .=' WHERE  1=1  ORDER BY `usr`.`member_login` asc';
    $__rs = $this->_conn->query($__query);
    $__rs->setFetchMode(8,'cDaoRecord_havefnubb_Jx_member_Jx_mysql');
    return $__rs;
}
 function findAllActivatedMember ( $offset='0', $count='200'){
    $__query =  $this->_selectClause.$this->_fromClause.$this->_whereClause;
$__query .=' WHERE  `usr`.`member_status` = 1 ORDER BY `usr`.`member_login` asc';
    $__rs = $this->_conn->limitQuery($__query, $offset, $count);
    $__rs->setFetchMode(8,'cDaoRecord_havefnubb_Jx_member_Jx_mysql');
    return $__rs;
}
 function countAllActivatedMember (){
    $__query = 'SELECT COUNT(*) as c '.$this->_fromClause.$this->_whereClause;
$__query .=' WHERE  `usr`.`member_status` = 1';
    $__rs = $this->_conn->query($__query);
    $__res = $__rs->fetch();
    return intval($__res->c);
}
 function getById ($id){
    $__query =  $this->_selectClause.$this->_fromClause.$this->_whereClause;
$__query .=' WHERE  `usr`.`id_user` '.'='.intval($id).'';
    $__rs = $this->_conn->limitQuery($__query,0,1);
    $__rs->setFetchMode(8,'cDaoRecord_havefnubb_Jx_member_Jx_mysql');
    return $__rs->fetch();
}
 function findLastActiveMember (){
    $__query =  $this->_selectClause.$this->_fromClause.$this->_whereClause;
$__query .=' WHERE  `usr`.`member_status` = 1 ORDER BY `usr`.`member_created` desc';
    $__rs = $this->_conn->limitQuery($__query,0,1);
    $__rs->setFetchMode(8,'cDaoRecord_havefnubb_Jx_member_Jx_mysql');
    return $__rs->fetch();
}
 function updateNbMsg ($id){
    $__query = 'UPDATE `'.$this->_conn->prefixTable('member').'` SET 
 `member_nb_msg`= member_nb_msg +1';
$__query .=' WHERE  `id_user` '.'='.intval($id).'';
    return $this->_conn->exec ($__query);
}
 function updateLastPostedMsg ($id, $time){
    $__query = 'UPDATE `'.$this->_conn->prefixTable('member').'` SET 
 `member_last_post`= '.($time === null ? 'NULL' : intval($time)).'';
$__query .=' WHERE  `id_user` '.'='.intval($id).'';
    return $this->_conn->exec ($__query);
}
 function updateNbMsgAfterCreatingAccount ( $id, $request_date, $nbPost='0'){
    $__query = 'UPDATE `'.$this->_conn->prefixTable('member').'` SET 
 `member_nb_msg`= '.($nbPost === null ? 'NULL' : intval($nbPost)).', `member_request_date`= '.($request_date === null ? 'NULL' : $this->_conn->quote($request_date,false)).'';
$__query .=' WHERE  `id_user` '.'='.intval($id).'';
    return $this->_conn->exec ($__query);
}
 function findOnlineToday ($today){
    $__query =  $this->_selectClause.$this->_fromClause.$this->_whereClause;
$__query .=' WHERE  `usr`.`member_last_connect` '.'>='.intval($today).'';
    $__rs = $this->_conn->query($__query);
    $__rs->setFetchMode(8,'cDaoRecord_havefnubb_Jx_member_Jx_mysql');
    return $__rs;
}
 function findAllConnected ($time){
    $__query =  $this->_selectClause.$this->_fromClause.$this->_whereClause;
$__query .=' WHERE  `connected`.`connected` '.'<'.intval($time).'';
    $__rs = $this->_conn->query($__query);
    $__rs->setFetchMode(8,'cDaoRecord_havefnubb_Jx_member_Jx_mysql');
    return $__rs;
}
}
?>