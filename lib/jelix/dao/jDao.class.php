<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package    jelix
* @subpackage dao
* @author     Laurent Jouanneau
* @copyright   2005-2006 Laurent Jouanneau
* @link        http://www.jelix.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/
require_once(JELIX_LIB_PATH.'db/jDb.class.php');
require_once(JELIX_LIB_PATH.'dao/jDaoRecordBase.class.php');
require_once(JELIX_LIB_PATH.'dao/jDaoFactoryBase.class.php');
class jDao{
	public static function create($DaoId,$profile=''){
		if(is_string($DaoId))
			$DaoId=new jSelectorDao($DaoId,$profile);
		$c=$DaoId->getDaoClass();
		if(!class_exists($c,false)){
			jIncluder::inc($DaoId);
		}
		$conn=jDb::getConnection($profile);
		$obj=new $c($conn);
		return $obj;
	}
	static protected $_daoSingleton=array();
	public static function get($DaoId,$profile=''){
		$sel=new jSelectorDao($DaoId,$profile);
		$DaoId=$sel->toString();
		if(! isset(self::$_daoSingleton[$DaoId])){
			self::$_daoSingleton[$DaoId]=self::create($sel,$profile);
		}
		return self::$_daoSingleton[$DaoId];
	}
	public static function releaseAll(){
		self::$_daoSingleton=array();
	}
	public static function createRecord($DaoId,$profile=''){
		$sel=new jSelectorDao($DaoId,$profile);
		$c=$sel->getDaoClass();
		if(!class_exists($c,false)){
			jIncluder::inc($sel);
		}
		$c=$sel->getDaoRecordClass();
		$obj=new $c();
		return $obj;
	}
	public static function createConditions($glueOp='AND'){
		$obj=new jDaoConditions($glueOp);
		return $obj;
	}
}
