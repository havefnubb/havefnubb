<?php  require_once ( JELIX_LIB_PATH .'dao/jDaoRecordBase.class.php');
 require_once ( JELIX_LIB_PATH .'dao/jDaoFactoryBase.class.php');

class cDaoRecord_jelix_Jx_jacl2rights_Jx_mysql extends jDaoRecordBase {
 public $id_aclsbj;
 public $id_aclgrp;
 public $id_aclres;
   public function getProperties() { return cDao_jelix_Jx_jacl2rights_Jx_mysql::$_properties; }
   public function getPrimaryKeyNames() { return cDao_jelix_Jx_jacl2rights_Jx_mysql::$_pkFields; }
}

class cDao_jelix_Jx_jacl2rights_Jx_mysql extends jDaoFactoryBase {
   protected $_tables = array (
  'r' => 
  array (
    'name' => 'r',
    'realname' => 'jacl2_rights',
    'pk' => 
    array (
      0 => 'id_aclsbj',
      1 => 'id_aclgrp',
      2 => 'id_aclres',
    ),
    'fields' => 
    array (
      0 => 'id_aclsbj',
      1 => 'id_aclgrp',
      2 => 'id_aclres',
    ),
  ),
);
   protected $_primaryTable = 'r';
   protected $_selectClause='SELECT `r`.`id_aclsbj`, `r`.`id_aclgrp`, `r`.`id_aclres`';
   protected $_fromClause;
   protected $_whereClause='';
   protected $_DaoRecordClassName='cDaoRecord_jelix_Jx_jacl2rights_Jx_mysql';
   protected $_daoSelector = 'jelix~jacl2rights';
   public static $_properties = array (
  'id_aclsbj' => 
  array (
    'name' => 'id_aclsbj',
    'fieldName' => 'id_aclsbj',
    'regExp' => NULL,
    'required' => true,
    'requiredInConditions' => true,
    'isPK' => true,
    'isFK' => false,
    'datatype' => 'string',
    'table' => 'r',
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
  'id_aclgrp' => 
  array (
    'name' => 'id_aclgrp',
    'fieldName' => 'id_aclgrp',
    'regExp' => NULL,
    'required' => true,
    'requiredInConditions' => true,
    'isPK' => true,
    'isFK' => false,
    'datatype' => 'int',
    'table' => 'r',
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
  'id_aclres' => 
  array (
    'name' => 'id_aclres',
    'fieldName' => 'id_aclres',
    'regExp' => NULL,
    'required' => true,
    'requiredInConditions' => true,
    'isPK' => true,
    'isFK' => false,
    'datatype' => 'string',
    'table' => 'r',
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
   public static $_pkFields = array('id_aclsbj','id_aclgrp','id_aclres');
 
public function __construct($conn){
   parent::__construct($conn);
   $this->_fromClause = ' FROM `'.$this->_conn->prefixTable('jacl2_rights').'` AS `r`';
}
   public function getProperties() { return self::$_properties; }
   public function getPrimaryKeyNames() { return self::$_pkFields;}
 
 protected function _getPkWhereClauseForSelect($pk){
   extract($pk);
 return ' WHERE  `r`.`id_aclsbj`'.'='.$this->_conn->quote($id_aclsbj).' AND `r`.`id_aclgrp`'.'='.intval($id_aclgrp).' AND `r`.`id_aclres`'.'='.$this->_conn->quote($id_aclres).'';
}
 
protected function _getPkWhereClauseForNonSelect($pk){
   extract($pk);
   return ' where  `id_aclsbj`'.'='.$this->_conn->quote($id_aclsbj).' AND `id_aclgrp`'.'='.intval($id_aclgrp).' AND `id_aclres`'.'='.$this->_conn->quote($id_aclres).'';
}
public function insert ($record){
    $query = 'INSERT INTO `'.$this->_conn->prefixTable('jacl2_rights').'` (
`id_aclsbj`,`id_aclgrp`,`id_aclres`
) VALUES (
'.($record->id_aclsbj === null ? 'NULL' : $this->_conn->quote($record->id_aclsbj,false)).', '.($record->id_aclgrp === null ? 'NULL' : intval($record->id_aclgrp)).', '.($record->id_aclres === null ? 'NULL' : $this->_conn->quote($record->id_aclres,false)).'
)';
   $result = $this->_conn->exec ($query);
    return $result;
}
public function update ($record){
     throw new jException('jelix~dao.error.update.impossible',array('jelix~jacl2rights','D:\wamp\www\havefnubb\var\overloads/jelix/daos/jacl2rights.dao.xml'));
 }
 function getRight ($subject, $groups){
    $__query =  $this->_selectClause.$this->_fromClause.$this->_whereClause;
$__query .=' WHERE  `r`.`id_aclsbj` '.'='.$this->_conn->quote($subject).' AND `r`.`id_aclres` = \'\' AND `r`.`id_aclgrp` IN ('.implode(',', array_map( create_function('$__e','return intval($__e);'), $groups)).')';
    $__rs = $this->_conn->limitQuery($__query,0,1);
    $__rs->setFetchMode(8,'cDaoRecord_jelix_Jx_jacl2rights_Jx_mysql');
    return $__rs->fetch();
}
 function getRightsByGroups ($groups){
    $__query =  $this->_selectClause.$this->_fromClause.$this->_whereClause;
$__query .=' WHERE  `r`.`id_aclres` = \'\' AND `r`.`id_aclgrp` IN ('.implode(',', array_map( create_function('$__e','return intval($__e);'), $groups)).')';
    $__rs = $this->_conn->query($__query);
    $__rs->setFetchMode(8,'cDaoRecord_jelix_Jx_jacl2rights_Jx_mysql');
    return $__rs;
}
 function getHfnuRightsByGroups ($groups, $resources){
    $__query =  $this->_selectClause.$this->_fromClause.$this->_whereClause;
$__query .=' WHERE  `r`.`id_aclres` '.'='.$this->_conn->quote($resources).' AND `r`.`id_aclgrp` IN ('.implode(',', array_map( create_function('$__e','return intval($__e);'), $groups)).') AND ( `r`.`id_aclsbj` = \'hfnu.forum.list\' OR `r`.`id_aclsbj` = \'hfnu.forum.view\' OR `r`.`id_aclsbj` = \'hfnu.posts.create\' OR `r`.`id_aclsbj` = \'hfnu.posts.delete\' OR `r`.`id_aclsbj` = \'hfnu.posts.edit\' OR `r`.`id_aclsbj` = \'hfnu.posts.view\' OR `r`.`id_aclsbj` = \'hfnu.posts.list\' OR `r`.`id_aclsbj` = \'hfnu.posts.reply\' OR `r`.`id_aclsbj` = \'hfnu.posts.quote\' OR `r`.`id_aclsbj` = \'hfnu.posts.notify\' OR `r`.`id_aclsbj` = \'hfnu.posts.edit.own\' OR `r`.`id_aclsbj` = \'hfnu.posts.rss\')';
    $__rs = $this->_conn->query($__query);
    $__rs->setFetchMode(8,'cDaoRecord_jelix_Jx_jacl2rights_Jx_mysql');
    return $__rs;
}
 function getRightsByGroup ($group){
    $__query =  $this->_selectClause.$this->_fromClause.$this->_whereClause;
$__query .=' WHERE  `r`.`id_aclres` = \'\' AND `r`.`id_aclgrp` '.'='.intval($group).'';
    $__rs = $this->_conn->query($__query);
    $__rs->setFetchMode(8,'cDaoRecord_jelix_Jx_jacl2rights_Jx_mysql');
    return $__rs;
}
 function getRightWithRes ($subject, $groups, $res){
    $__query =  $this->_selectClause.$this->_fromClause.$this->_whereClause;
$__query .=' WHERE  `r`.`id_aclsbj` '.'='.$this->_conn->quote($subject).' AND `r`.`id_aclres` '.'='.$this->_conn->quote($res).' AND `r`.`id_aclgrp` IN ('.implode(',', array_map( create_function('$__e','return intval($__e);'), $groups)).')';
    $__rs = $this->_conn->limitQuery($__query,0,1);
    $__rs->setFetchMode(8,'cDaoRecord_jelix_Jx_jacl2rights_Jx_mysql');
    return $__rs->fetch();
}
 function getRightsHavingRes ($group){
    $__query =  $this->_selectClause.$this->_fromClause.$this->_whereClause;
$__query .=' WHERE  `r`.`id_aclres` <> \'\' AND `r`.`id_aclgrp` '.'='.intval($group).' ORDER BY `r`.`id_aclsbj` asc';
    $__rs = $this->_conn->query($__query);
    $__rs->setFetchMode(8,'cDaoRecord_jelix_Jx_jacl2rights_Jx_mysql');
    return $__rs;
}
 function getAnonymousRight ($subject){
    $__query =  $this->_selectClause.$this->_fromClause.$this->_whereClause;
$__query .=' WHERE  `r`.`id_aclsbj` '.'='.$this->_conn->quote($subject).' AND `r`.`id_aclres` = \'\' AND `r`.`id_aclgrp` = 0';
    $__rs = $this->_conn->limitQuery($__query,0,1);
    $__rs->setFetchMode(8,'cDaoRecord_jelix_Jx_jacl2rights_Jx_mysql');
    return $__rs->fetch();
}
 function getAnonymousRightWithRes ($subject, $res){
    $__query =  $this->_selectClause.$this->_fromClause.$this->_whereClause;
$__query .=' WHERE  `r`.`id_aclsbj` '.'='.$this->_conn->quote($subject).' AND `r`.`id_aclres` '.'='.$this->_conn->quote($res).' AND `r`.`id_aclgrp` = 0';
    $__rs = $this->_conn->limitQuery($__query,0,1);
    $__rs->setFetchMode(8,'cDaoRecord_jelix_Jx_jacl2rights_Jx_mysql');
    return $__rs->fetch();
}
 function getAllAnonymousRights (){
    $__query =  $this->_selectClause.$this->_fromClause.$this->_whereClause;
$__query .=' WHERE  `r`.`id_aclres` = \'\' AND `r`.`id_aclgrp` = 0';
    $__rs = $this->_conn->query($__query);
    $__rs->setFetchMode(8,'cDaoRecord_jelix_Jx_jacl2rights_Jx_mysql');
    return $__rs;
}
 function deleteByGroup ($group){
    $__query = 'DELETE FROM `'.$this->_conn->prefixTable('jacl2_rights').'` ';
$__query .=' WHERE  `id_aclgrp` '.'='.intval($group).'';
    return $this->_conn->exec ($__query);
}
 function deleteBySubjRes ($subject, $res){
    $__query = 'DELETE FROM `'.$this->_conn->prefixTable('jacl2_rights').'` ';
$__query .=' WHERE  `id_aclsbj` '.'='.$this->_conn->quote($subject).' AND `id_aclres` '.'='.$this->_conn->quote($res).'';
    return $this->_conn->exec ($__query);
}
 function deleteBySubject ($subject){
    $__query = 'DELETE FROM `'.$this->_conn->prefixTable('jacl2_rights').'` ';
$__query .=' WHERE  `id_aclsbj` '.'='.$this->_conn->quote($subject).'';
    return $this->_conn->exec ($__query);
}
 function deleteByGroupAndSubjects ($group, $subjects){
    $__query = 'DELETE FROM `'.$this->_conn->prefixTable('jacl2_rights').'` ';
$__query .=' WHERE  `id_aclgrp` '.'='.intval($group).' AND `id_aclsbj` IN ('.implode(',', array_map( create_function('$__e','return \'\\\'\'.str_replace(\'\\\'\',\'\\\\\\\'\',$__e).\'\\\'\';'), $subjects)).') AND `id_aclres` = \'\'';
    return $this->_conn->exec ($__query);
}
 function deleteRightsOnResource ($group, $subjects){
    $__query = 'DELETE FROM `'.$this->_conn->prefixTable('jacl2_rights').'` ';
$__query .=' WHERE  `id_aclgrp` '.'='.intval($group).' AND `id_aclsbj` IN ('.implode(',', array_map( create_function('$__e','return \'\\\'\'.str_replace(\'\\\'\',\'\\\\\\\'\',$__e).\'\\\'\';'), $subjects)).') AND `id_aclres` <> \'\'';
    return $this->_conn->exec ($__query);
}
 function deleteHfnuByGroup ($group, $res){
    $__query = 'DELETE FROM `'.$this->_conn->prefixTable('jacl2_rights').'` ';
$__query .=' WHERE  `id_aclgrp` '.'='.intval($group).' AND `id_aclres` '.'='.$this->_conn->quote($res).'';
    return $this->_conn->exec ($__query);
}
}
?>