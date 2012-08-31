<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package     jelix
* @subpackage  acl
* @author      Laurent Jouanneau
* @copyright   2006-2010 Laurent Jouanneau
* @link        http://www.jelix.org
* @licence     http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
* @since 1.1
*/
interface jIAcl2Driver{
	public function getRight($subject,$resource=null);
	public function clearCache();
}
class jAcl2{
	private function __construct(){}
	protected static function _getDriver(){
		static $driver=null;
		if($driver==null){
			global $gJConfig;
			$db=strtolower($gJConfig->acl2['driver']);
			if($db=='')
				throw new jException('jelix~errors.acl.driver.notfound',$db);
			$driver=jApp::loadPlugin($db,'acl2','.acl2.php',$gJConfig->acl2['driver'].'Acl2Driver',$gJConfig->acl2);
			if(is_null($driver)){
				throw new jException('jelix~errors.acl.driver.notfound',$db);
			}
		}
		return $driver;
	}
	public static function check($subject,$resource=null){
		$dr=self::_getDriver();
		return $dr->getRight($subject,$resource);
	}
	public static function clearCache(){
		$dr=self::_getDriver();
		$dr->clearCache();
	}
}
