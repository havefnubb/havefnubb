<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package     jelix
* @subpackage  utils
* @author      Laurent Jouanneau
* @contributor Yannick Le Guédart, Julien Issler
* @copyright   2011 Laurent Jouanneau, 2007 Yannick Le Guédart, 2011 Julien Issler
* @link        http://jelix.org
* @licence     GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
*/
class jProfiles{
	protected static $_profiles=null;
	protected static $_objectPool=array();
	protected static function loadProfiles(){
		$file=jApp::configPath('profiles.ini.php');
		self::$_profiles=parse_ini_file($file,true);
	}
	public static function get($category,$name='',$noDefault=false){
		if(self::$_profiles===null){
			self::loadProfiles();
		}
		if($name=='')
			$name='default';
		$section=$category.':'.$name;
		$targetName=$section;
		if(isset(self::$_profiles[$category.':__common__'])){
			$common=self::$_profiles[$category.':__common__'];
		}
		else
			$common=null;
		if(isset(self::$_profiles[$section])){
			self::$_profiles[$section]['_name']=$name;
			if($common)
				return array_merge($common,self::$_profiles[$section]);
			return self::$_profiles[$section];
		}
		else if(isset(self::$_profiles[$category][$name])){
			$name=self::$_profiles[$category][$name];
			$targetName=$category.':'.$name;
		}
		elseif(!$noDefault){
			if(isset(self::$_profiles[$category.':default'])){
				self::$_profiles[$category.':default']['_name']='default';
				if($common)
					return array_merge($common,self::$_profiles[$category.':default']);
				return self::$_profiles[$category.':default'];
			}
			elseif(isset(self::$_profiles[$category]['default'])){
				$name=self::$_profiles[$category]['default'];
				$targetName=$category.':'.$name;
			}
		}
		else{
			if($name=='default')
				throw new jException('jelix~errors.profile.default.unknown',$category);
			else
				throw new jException('jelix~errors.profile.unknown',array($name,$category));
		}
		if(isset(self::$_profiles[$targetName])&&is_array(self::$_profiles[$targetName])){
			self::$_profiles[$targetName]['_name']=$name;
			if($common)
				return array_merge($common,self::$_profiles[$targetName]);
			return self::$_profiles[$targetName];
		}
		else{
			throw new jException('jelix~errors.profile.unknown',array($name,$category));
		}
	}
	public static function storeInPool($category,$name,$object){
		self::$_objectPool[$category][$name]=$object;
	}
	public static function getFromPool($category,$name){
		if(isset(self::$_objectPool[$category][$name]))
			return self::$_objectPool[$category][$name];
		return null;
	}
	public static function getOrStoreInPool($category,$name,$function,$nodefault=false){
		$profile=self::get($category,$name,$nodefault);
		if(isset(self::$_objectPool[$category][$profile['_name']]))
			return self::$_objectPool[$category][$profile['_name']];
		$obj=call_user_func($function,$profile);
		if($obj)
			self::$_objectPool[$category][$profile['_name']]=$obj;
		return $obj;
	}
	public static function createVirtualProfile($category,$name,$params){
		global $gJConfig;
		if($name==''){
			throw new jException('jelix~errors.profile.virtual.no.name',$category);
		}
		if(self::$_profiles===null){
			self::loadProfiles();
		}
		if(is_string($params)){
			self::$_profiles[$category][$name]=$params;
		}
		else{
			$params['_name']=$name;
			self::$_profiles[$category.':'.$name]=$params;
		}
		unset(self::$_objectPool[$category][$name]);
	}
	public static function clear(){
		self::$_profiles=null;
		self::$_objectPool=array();
	}
}
