<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package    jelix
* @subpackage auth
* @author     Laurent Jouanneau
* @contributor Frédéric Guillot, Antoine Detante, Julien Issler, Dominique Papin, Tahina Ramaroson, Sylvain de Vathaire, Vincent Viaud
* @copyright  2001-2005 CopixTeam, 2005-2008 Laurent Jouanneau, 2007 Frédéric Guillot, 2007 Antoine Detante
* @copyright  2007-2008 Julien Issler, 2008 Dominique Papin, 2010 NEOV, 2010 BP2I
*
* This classes were get originally from an experimental branch of the Copix project (Copix 2.3dev, http://www.copix.org)
* Few lines of code are still copyrighted 2001-2005 CopixTeam (LGPL licence).
* Initial author of this Copix classes is Laurent Jouanneau, and this classes were adapted for Jelix by him
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/
interface jIAuthDriver{
	function __construct($params);
	public function createUserObject($login,$password);
	public function saveNewUser($user);
	public function removeUser($login);
	public function updateUser($user);
	public function getUser($login);
	public function getUserList($pattern);
	public function changePassword($login,$newpassword);
	public function verifyPassword($login,$password);
}
class jAuthDriverBase{
	protected $_params;
	function __construct($params){
		$this->_params=$params;
	}
	public function cryptPassword($password){
		if(isset($this->_params['password_crypt_function'])){
			$f=$this->_params['password_crypt_function'];
			if($f!=''){
				if($f[1]==':'){
					$t=$f[0];
					$f=substr($f,2);
					if($t=='1'){
						return $f((isset($this->_params['password_salt'])?$this->_params['password_salt']:''),$password);
					}
					else if($t=='2'){
						return $f($this->_params,$password);
					}
				}
				return $f($password);
			}
		}
		return $password;
	}
}
function sha1WithSalt($salt,$password){
	return sha1($salt.':'.$password);
}
class jAuth{
	protected static function  _getConfig(){
		static $config=null;
		if($config==null){
			global $gJCoord;
			$plugin=$gJCoord->getPlugin('auth');
			if($plugin===null)
				throw new jException('jelix~auth.error.plugin.missing');
			$config=& $plugin->config;
			if(!isset($config['session_name'])
				||$config['session_name']=='')
				$config['session_name']='JELIX_USER';
			if(!isset($config['persistant_cookie_path'])
				||$config['persistant_cookie_path']=='')
				$config['persistant_cookie_path']=$GLOBALS['gJConfig']->urlengine['basePath'];
		}
		return $config;
	}
	protected static function _getDriver(){
		static $driver=null;
		if($driver==null){
			$config=self::_getConfig();
			global $gJConfig;
			$db=strtolower($config['driver']);
			if(!isset($gJConfig->_pluginsPathList_auth)
				||!isset($gJConfig->_pluginsPathList_auth[$db])
				||!file_exists($gJConfig->_pluginsPathList_auth[$db]))
				throw new jException('jelix~auth.error.driver.notfound',$db);
			require_once($gJConfig->_pluginsPathList_auth[$db].$db.'.auth.php');
			$dname=$config['driver'].'AuthDriver';
			$driver=new $dname($config[$config['driver']]);
		}
		return $driver;
	}
	public static function getDriverParam($paramName){
		$config=self::_getConfig();
		$config=$config[$config['driver']];
		if(isset($config[$paramName]))
			return $config[$paramName];
		else
			return null;
	}
	public static function getUser($login){
		$dr=self::_getDriver();
		return $dr->getUser($login);
	}
	public static function createUserObject($login,$password){
		$dr=self::_getDriver();
		return $dr->createUserObject($login,$password);
	}
	public static function saveNewUser($user){
		$dr=self::_getDriver();
		if($dr->saveNewUser($user))
			jEvent::notify('AuthNewUser',array('user'=>$user));
		return $user;
	}
	public static function updateUser($user){
		$dr=self::_getDriver();
		if($dr->updateUser($user)===false)
			return false;
		if(self::isConnected()&&self::getUserSession()->login===$user->login){
			$config=self::_getConfig();
			$_SESSION[$config['session_name']]=$user;
		}
		jEvent::notify('AuthUpdateUser',array('user'=>$user));
		return true;
	}
	public static function removeUser($login){
		$dr=self::_getDriver();
		$eventresp=jEvent::notify('AuthCanRemoveUser',array('login'=>$login));
		foreach($eventresp->getResponse()as $rep){
			if(!isset($rep['canremove'])||$rep['canremove']===false)
				return false;
		}
		$user=$dr->getUser($login);
		if($dr->removeUser($login)===false)
			return false;
		jEvent::notify('AuthRemoveUser',array('login'=>$login,'user'=>$user));
		if(self::isConnected()&&self::getUserSession()->login===$login)
			self::logout();
		return true;
	}
	public static function getUserList($pattern='%'){
		$dr=self::_getDriver();
		return $dr->getUserlist($pattern);
	}
	public static function changePassword($login,$newpassword){
		$dr=self::_getDriver();
		if($dr->changePassword($login,$newpassword)===false)
			return false;
		if(self::isConnected()&&self::getUserSession()->login===$login){
			$config=self::_getConfig();
			$_SESSION[$config['session_name']]=self::getUser($login);
		}
		return true;
	}
	public static function verifyPassword($login,$password){
		$dr=self::_getDriver();
		return $dr->verifyPassword($login,$password);
	}
	public static function login($login,$password,$persistant=false){
		$dr=self::_getDriver();
		$config=self::_getConfig();
		$eventresp=jEvent::notify('AuthBeforeLogin',array('login'=>$login));
		foreach($eventresp->getResponse()as $rep){
			if(isset($rep['processlogin'])&&$rep['processlogin']===false)
				return false;
		}
		if($user=$dr->verifyPassword($login,$password)){
			$eventresp=jEvent::notify('AuthCanLogin',array('login'=>$login,'user'=>$user));
			foreach($eventresp->getResponse()as $rep){
				if(!isset($rep['canlogin'])||$rep['canlogin']===false)
					return false;
			}
			$_SESSION[$config['session_name']]=$user;
			$persistence=0;
			if($persistant&&isset($config['persistant_enable'])&&$config['persistant_enable']){
				if(!isset($config['persistant_crypt_key'])||!isset($config['persistant_cookie_name'])){
					throw new jException('jelix~auth.error.persistant.incorrectconfig','persistant_cookie_name, persistant_crypt_key');
				}
				if(isset($config['persistant_duration']))
					$persistence=$config['persistant_duration']*86400;
				else
					$persistence=86400;
				$persistence+=time();
				$encrypted=jCrypt::encrypt(serialize(array($login,$password)),$config['persistant_crypt_key']);
				setcookie($config['persistant_cookie_name'].'[auth]',$encrypted,$persistence,$config['persistant_cookie_path']);
			}
			jEvent::notify('AuthLogin',array('login'=>$login,'persistence'=>$persistence));
			return true;
		}else{
			jEvent::notify('AuthErrorLogin',array('login'=>$login));
			return false;
		}
	}
	public static function isPersistant(){
		$config=self::_getConfig();
		if(!isset($config['persistant_enable']))
			return false;
		else
			return $config['persistant_enable'];
	}
	public static function logout(){
		$config=self::_getConfig();
		jEvent::notify('AuthLogout',array('login'=>$_SESSION[$config['session_name']]->login));
		$_SESSION[$config['session_name']]=new jAuthDummyUser();
		if(isset($config['persistant_enable'])&&$config['persistant_enable']){
			if(!isset($config['persistant_cookie_name']))
				throw new jException('jelix~auth.error.persistant.incorrectconfig','persistant_cookie_name, persistant_crypt_key');
			setcookie($config['persistant_cookie_name'].'[auth]','',time()- 3600,$config['persistant_cookie_path']);
		}
	}
	public static function isConnected(){
		$config=self::_getConfig();
		return(isset($_SESSION[$config['session_name']])&&$_SESSION[$config['session_name']]->login!='');
	}
	public static function getUserSession(){
		$config=self::_getConfig();
		if(! isset($_SESSION[$config['session_name']]))
			$_SESSION[$config['session_name']]=new jAuthDummyUser();
		return $_SESSION[$config['session_name']];
	}
	public static function getRandomPassword($length=10){
		$letter="1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
		$pass='';
		for($i=0;$i<$length;$i++)
			$pass.=$letter[rand(0,61)];
		return $pass;
	}
}
