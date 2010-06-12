<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package     jelix
* @subpackage  acl
* @author      Laurent Jouanneau
* @copyright   2006-2009 Laurent Jouanneau
* @link        http://www.jelix.org
* @licence     http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
* @since 1.0a3
*/
interface jIAclDriver{
	public function getRight($subject,$resource=null);
	public function clearCache();
}
class jAcl{
	private function __construct(){}
	protected static function _getDriver(){
		static $driver=null;
		if($driver==null){
			global $gJConfig;
			$db=strtolower($gJConfig->acl['driver']);
			if($db==''||!isset($gJConfig->_pluginsPathList_acl)
				||!isset($gJConfig->_pluginsPathList_acl[$db])
				||!file_exists($gJConfig->_pluginsPathList_acl[$db])){
				throw new jException('jelix~errors.acl.driver.notfound',$db);
			}
			require_once($gJConfig->_pluginsPathList_acl[$db].$db.'.acl.php');
			$dname=$gJConfig->acl['driver'].'AclDriver';
			$driver=new $dname($gJConfig->acl);
		}
		return $driver;
	}
	public static function check($subject,$value=true,$resource=null){
		$val=self::getRight($subject,$resource);
		return in_array($value,$val);
	}
	public static function getRight($subject,$resource=null){
		$dr=self::_getDriver();
		return $dr->getRight($subject,$resource);
	}
	public static function clearCache(){
		$dr=self::_getDriver();
		$dr->clearCache();
	}
}
