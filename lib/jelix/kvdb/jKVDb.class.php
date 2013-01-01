<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
 * @package     jelix
 * @subpackage  kvdb
 * @author      Yannick Le Guédart
 * @contributor  Laurent Jouanneau
 * @copyright   2009 Yannick Le Guédart, 2010-2011 Laurent Jouanneau
 *
 * @link     http://www.jelix.org
 * @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
 */
class jKVDb{
	protected function __construct(){}
	public static function getConnection($name=null){
		return jProfiles::getOrStoreInPool('jkvdb',$name,array('jKVDb','_createConnector'));
	}
	public static function getProfile($name=null){
		return jProfiles::get('jkvdb',$name);
	}
	public static function _createConnector($profile){
		if(! isset($profile['driver'])){
			throw new jException(
				'jelix~kvstore.error.driver.notset',$profile['_name']);
		}
		$connector=jApp::loadPlugin($profile['driver'],'kvdb','.kvdriver.php',$profile['driver'] . 'KVDriver',$profile);
		return $connector;
	}
}
