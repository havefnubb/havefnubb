<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package    jelix
* @subpackage auth
* @author     Laurent Jouanneau
* @contributor Frédéric Guillot, Antoine Detante, Julien Issler, Dominique Papin, Tahina Ramaroson, Sylvain de Vathaire, Vincent Viaud
* @copyright  2001-2005 CopixTeam, 2005-2012 Laurent Jouanneau, 2007 Frédéric Guillot, 2007 Antoine Detante
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
	protected $passwordHashMethod;
	protected $passwordHashOptions;
	function __construct($params){
		$this->_params=$params;
		$this->passwordHashOptions=$params['password_hash_options'];
		$this->passwordHashMethod=$params['password_hash_method'];
	}
	public function cryptPassword($password,$forceOldHash=false){
		if(!$forceOldHash&&$this->passwordHashMethod){
			return password_hash($password,$this->passwordHashMethod,$this->passwordHashOptions);
		}
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
	public function checkPassword($givenPassword,$currentPasswordHash){
		if($currentPasswordHash[0]=='$'&&$this->passwordHashMethod){
			if(!password_verify($givenPassword,$currentPasswordHash)){
				return false;
			}
			if(password_needs_rehash($currentPasswordHash,$this->passwordHashMethod,$this->passwordHashOptions)){
				return password_hash($givenPassword,$this->passwordHashMethod,$this->passwordHashOptions);
			}
		}
		else{
			if($currentPasswordHash!=$this->cryptPassword($givenPassword,true)){
				return false;
			}
			if($this->passwordHashMethod){
				return password_hash($givenPassword,$this->passwordHashMethod,$this->passwordHashOptions);
			}
		}
		return true;
	}
}
function sha1WithSalt($salt,$password){
	return sha1($salt.':'.$password);
}
function bcrypt($salt,$password,$iteration_count=12){
	if(CRYPT_BLOWFISH!=1)
		throw new jException('jelix~auth.error.bcrypt.inexistant');
	if(empty($salt)||!ctype_alnum($salt)||strlen($salt)!=22)
		throw new jException('jelix~auth.error.bcrypt.bad.salt');
	$hash=crypt($password,'$2a$'.$iteration_count.'$'.$salt.'$');
	return substr($hash,strrpos($hash,'$')+strlen($salt));
}
class jAuth{
	protected static function _getConfig(){
		return self::loadConfig();
	}
	protected static $config=null;
	protected static $driver=null;
	public static function loadConfig($newconfig=null){
		if(self::$config===null||$newconfig){
			if(!$newconfig){
				$plugin=jApp::coord()->getPlugin('auth');
				if($plugin===null)
					throw new jException('jelix~auth.error.plugin.missing');
				$config=& $plugin->config;
			}
			else{
				$config=$newconfig;
			}
			if(!isset($config['session_name'])
				||$config['session_name']=='')
				$config['session_name']='JELIX_USER';
			if(!isset($config['persistant_cookie_path'])
				||$config['persistant_cookie_path']==''){
				if(jApp::config())
					$config['persistant_cookie_path']=jApp::config()->urlengine['basePath'];
				else
					$config['persistant_cookie_path']='/';
			}
			$password_hash_method=(isset($config['password_hash_method'])? $config['password_hash_method']:0);
			if($password_hash_method===''||(! is_numeric($password_hash_method))){
				$password_hash_method=0;
			}
			else{
				$password_hash_method=intval($password_hash_method);
			}
			if($password_hash_method > 0){
				require_once(__DIR__.'/password.php');
				if(!can_use_password_API()){
					$password_hash_method=0;
				}
			}
			$password_hash_options=(isset($config['password_hash_options'])?$config['password_hash_options']:'');
			if($password_hash_options!=''){
				$list='{"'.str_replace(array('=',';'),array('":"','","'),$config['password_hash_options']).'"}';
				$json=new jJson(SERVICES_JSON_LOOSE_TYPE);
				$password_hash_options=@$json->decode($list);
				if(!$password_hash_options)
					$password_hash_options=array();
			}
			else{
				$password_hash_options=array();
			}
			$config['password_hash_method']=$password_hash_method;
			$config['password_hash_options']=$password_hash_options;
			$config[$config['driver']]['password_hash_method']=$password_hash_method;
			$config[$config['driver']]['password_hash_options']=$password_hash_options;
			self::$config=$config;
		}
		return self::$config;
	}
	protected static function _getDriver(){
		return self::getDriver();
	}
	public static function getDriver(){
		if(self::$driver===null){
			$config=self::loadConfig();
			$db=strtolower($config['driver']);
			$driver=jApp::loadPlugin($db,'auth','.auth.php',$config['driver'].'AuthDriver',$config[$config['driver']]);
			if(is_null($driver))
				throw new jException('jelix~auth.error.driver.notfound',$db);
			self::$driver=$driver;
		}
		return self::$driver;
	}
	public static function getDriverParam($paramName){
		$config=self::loadConfig();
		$config=$config[$config['driver']];
		if(isset($config[$paramName]))
			return $config[$paramName];
		else
			return null;
	}
	public static function getUser($login){
		$dr=self::getDriver();
		return $dr->getUser($login);
	}
	public static function createUserObject($login,$password){
		$dr=self::getDriver();
		return $dr->createUserObject($login,$password);
	}
	public static function saveNewUser($user){
		$dr=self::getDriver();
		if($dr->saveNewUser($user))
			jEvent::notify('AuthNewUser',array('user'=>$user));
		return $user;
	}
	public static function updateUser($user){
		$dr=self::getDriver();
		if($dr->updateUser($user)===false)
			return false;
		if(self::isConnected()&&self::getUserSession()->login===$user->login){
			$config=self::loadConfig();
			$_SESSION[$config['session_name']]=$user;
		}
		jEvent::notify('AuthUpdateUser',array('user'=>$user));
		return true;
	}
	public static function removeUser($login){
		$dr=self::getDriver();
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
		$dr=self::getDriver();
		return $dr->getUserlist($pattern);
	}
	public static function changePassword($login,$newpassword){
		$dr=self::getDriver();
		if($dr->changePassword($login,$newpassword)===false)
			return false;
		if(self::isConnected()&&self::getUserSession()->login===$login){
			$config=self::loadConfig();
			$_SESSION[$config['session_name']]=self::getUser($login);
		}
		return true;
	}
	public static function verifyPassword($login,$password){
		$dr=self::getDriver();
		return $dr->verifyPassword($login,$password);
	}
	public static function login($login,$password,$persistant=false){
		$dr=self::getDriver();
		$config=self::loadConfig();
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
			if($persistant){
				$persistence=self::generateCookieToken($login,$password);
			}
			else{
				$persistence=0;
			}
			jEvent::notify('AuthLogin',array('login'=>$login,'persistence'=>$persistence));
			return true;
		}else{
			jEvent::notify('AuthErrorLogin',array('login'=>$login));
			return false;
		}
	}
	public static function isPersistant(){
		$config=self::loadConfig();
		if(!isset($config['persistant_enable']))
			return false;
		else
			return $config['persistant_enable'];
	}
	public static function logout(){
		$config=self::loadConfig();
		jEvent::notify('AuthLogout',array('login'=>$_SESSION[$config['session_name']]->login));
		$_SESSION[$config['session_name']]=new jAuthDummyUser();
		if(isset($config['session_destroy'])&&$config['session_destroy']){
			$params=session_get_cookie_params();
			setcookie(session_name(),'',time()- 42000,$params["path"],$params["domain"],$params["secure"],$params["httponly"]);
			session_destroy();
		}
		if(isset($config['persistant_enable'])&&$config['persistant_enable']){
			if(!isset($config['persistant_cookie_name']))
				throw new jException('jelix~auth.error.persistant.incorrectconfig','persistant_cookie_name, persistant_crypt_key');
			setcookie($config['persistant_cookie_name'].'[auth]','',time()- 3600,$config['persistant_cookie_path']);
		}
	}
	public static function isConnected(){
		$config=self::loadConfig();
		return(isset($_SESSION[$config['session_name']])&&$_SESSION[$config['session_name']]->login!='');
	}
	public static function getUserSession(){
		$config=self::loadConfig();
		if(! isset($_SESSION[$config['session_name']]))
			$_SESSION[$config['session_name']]=new jAuthDummyUser();
		return $_SESSION[$config['session_name']];
	}
	public static function getRandomPassword($length=10,$withoutSpecialChars=false){
		if($length < 10)
			$length=10;
		$nbNumber=floor($length/4);
		if($nbNumber < 2)
			$nbNumber=2;
		if($withoutSpecialChars)
			$nbSpec=0;
		else{
			$nbSpec=floor($length/5);
			if($nbSpec < 1)
				$nbSpec=1;
		}
		$nbLower=floor(($length-$nbNumber-$nbSpec)/2);
		$nbUpper=$length-$nbNumber-$nbLower-$nbSpec;
		$pass='';
		$letter="1234567890";
		for($i=0;$i<$nbNumber;$i++)
			$pass.=$letter[rand(0,9)];
		$letter='!@#$%^&*?_,~';
		for($i=0;$i<$nbSpec;$i++)
			$pass.=$letter[rand(0,11)];
		$letter="abcdefghijklmnopqrstuvwxyz";
		for($i=0;$i<$nbLower;$i++)
			$pass.=$letter[rand(0,25)];
		$letter="ABCDEFGHIJKLMNOPQRSTUVWXYZ";
		for($i=0;$i<$nbUpper;$i++)
			$pass.=$letter[rand(0,25)];
		return str_shuffle($pass);
	}
	public static function checkCookieToken(){
		$config=self::loadConfig();
		if(isset($config['persistant_enable'])&&$config['persistant_enable']&&!self::isConnected()){
			if(isset($config['persistant_cookie_name'])&&isset($config['persistant_crypt_key'])){
				$cookieName=$config['persistant_cookie_name'];
				if(isset($_COOKIE[$cookieName]['auth'])&&strlen($_COOKIE[$cookieName]['auth'])>0){
					$decrypted=jCrypt::decrypt($_COOKIE[$cookieName]['auth'],$config['persistant_crypt_key']);
					$decrypted=@unserialize($decrypted);
					if($decrypted&&is_array($decrypted)){
						list($login,$password)=$decrypted;
						self::login($login,$password,true);
					}
				}
				if(isset($_COOKIE[$cookieName]['login'])){
					setcookie($cookieName.'[login]','',time()- 3600,$config['persistant_cookie_path']);
					setcookie($cookieName.'[passwd]','',time()- 3600,$config['persistant_cookie_path']);
				}
			}
			else{
				throw new jException('jelix~auth.error.persistant.incorrectconfig','persistant_cookie_name, persistant_crypt_key');
			}
		}
	}
	public static function generateCookieToken($login,$password){
		$persistence=0;
		$config=self::loadConfig();
		if(isset($config['persistant_enable'])&&$config['persistant_enable']){
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
		return $persistence;
	}
}
