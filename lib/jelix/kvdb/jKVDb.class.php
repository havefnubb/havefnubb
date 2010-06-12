<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
 * @package     jelix
 * @subpackage  kvdb
 * @author      Yannick Le Guédart
 * @copyright   2009 Yannick Le Guédart
 *
 * @link     http://www.jelix.org
 * @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
 */
define('KVDB_PROFILE_FILE',JELIX_APP_CONFIG_PATH . 'kvprofiles.ini.php');
class jKVDb{
	static private $_profiles=null;
	static private $_cnxPool=array();
	protected function __construct(){}
	public static function getConnection($name=null){
		$profile=self::getProfile($name);
		if(is_null($name)){
			$name=$profile['name'];
		}
		if(! isset(self::$_cnxPool[$name])){
			self::$_cnxPool[$name]=self::_createConnector($profile);
		}
		return self::$_cnxPool[$name];
	}
	public static function getProfile($name=null){
		if(is_null(self::$_profiles)){
			self::$_profiles=parse_ini_file(KVDB_PROFILE_FILE,true);
		}
		if(is_null($name)){
			if(isset(self::$_profiles['default'])){
				$name=self::$_profiles['default'];
			}
			else{
				throw new jException(
					'jelix~kvstore.error.default.profile.unknown');
			}
		}
		if(! isset(self::$_profiles[$name])
				or ! is_array(self::$_profiles[$name]))
		{
			throw new jException('jelix~kvstore.error.profile.unknown',$name);
		}
		self::$_profiles[$name]['name']=$name;
		return self::$_profiles[$name];
	}
	private static function _createConnector($profile){
		global $gJConfig;
		if(! isset($profile['driver'])){
			throw new jException(
				'jelix~kvstore.error.driver.notset',$profile['name']);
		}
		$pluginPath=
			$gJConfig->_pluginsPathList_kvdb[$profile['driver']] .
				$profile['driver'];
		require_once($pluginPath . '.kvdriver.php');
		$class=$profile['driver'] . 'KVDriver';
		$connector=new $class($profile);
		return $connector;
	}
}
