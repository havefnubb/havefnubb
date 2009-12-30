<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package    jelix
* @subpackage auth_driver
* @author     Nicolas JEUDY
* @contributor Laurent Jouanneau
* @copyright  2006 Nicolas JEUDY
* @copyright  2007 Laurent Jouanneau
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/
class jAuthUserLDS extends jAuthUser{
}
class ldsAuthDriver implements jIAuthDriver{
	protected $_params;
	function __construct($params){
		$this->_params = $params;
	}
	public function saveNewUser($user){
		$login = $user->login;
		$pass = $user->password;
		$firstname = "Jelix User";
		$name = "Jelix User";
		$homedir = "/tmp";
		$param = array($login, $pass, $firstname, $name, $homedir);
		$ret = $this->xmlCall("base.createUser",$param);
		return true;
	}
	public function removeUser($login){
		$fichier=0;
		$param=array($login,$fichier);
		$this->xmlCall("base.delUserFromAllGroups", $login);
		$this->xmlCall("base.delUser",$param);
		return true;
	}
	public function updateUser($user){
		return true;
	}
	public function getUser($login){
		$login = '*'.$login.'*';
		$paramsArr = $this->xmlCall('base.getUsersLdap',$login);
		$user = new jAuthUserLDS();
		$user->login = $paramsArr['uid'][0];
		$user->password = $paramsArr['userPassword'][0];
		return $user;
	}
	public function createUserObject($login,$password){
		$user = new jAuthUserLDS();
		$user->login = $login;
		$user->password = $password;
		return $user;
	}
	public function getUserList($pattern){
		$users = $this->xmlCall('base.getUsersLdap',$pattern . '*');
		$userslist = array();
		foreach($users as $userldap){
			$user = new jAuthUserLDS();
			$user->login = $userldap['uid'];
			$userslist[] = $user;
		}
		return $userslist;
	}
	public function changePassword($login, $newpassword){
		$param[]=$login;
		$param[]=$newpassword;
		return $this->xmlCall("base.changeUserPasswd",$param);
	}
	public function verifyPassword($login, $password){
		if(trim($password) == '')
			return false;
		$param[]=$login;
		$param[]=$password;
		$ret= $this->xmlCall("base.ldapAuth",$param);
		if( $ret == '1'){
			$user = new jAuthUserLDS();
			$user->login = $login;
			$user->password = $password;
		}
		return($user?$user:false);
	}
	protected function decodeEntities($text){
		$text = html_entity_decode($text,ENT_QUOTES,"ISO-8859-1");
		$text= preg_replace('/&#(\d+);/me',"chr(\\1)",$text);
		$text= preg_replace('/&#x([a-f0-9]+);/mei',"chr(0x\\1)",$text);
		return $text;
	}
	protected function xmlCall($method,$params){
		$output_options = array( "output_type" => "xml", "verbosity" => "pretty", "escaping" => array("markup", "non-ascii", "non-print"), "version" => "xmlrpc", "encoding" => "UTF-8");
		if($params==null){
			$request = xmlrpc_encode_request($method,null,$output_options);
		}else{
			$request = xmlrpc_encode_request($method,$params,$output_options);
			$request = $this->decodeEntities($request,ENT_QUOTES,"UTF-8");
		}
		$host= $this->_params['host'].":".$this->_params['port'];
		$url = "/";
		$httpQuery = "POST ". $url ." HTTP/1.0\r\n";
		$httpQuery .= "User-Agent: xmlrpc\r\n";
		$httpQuery .= "Host: ". $host ."\r\n";
		$httpQuery .= "Content-Type: text/xml\r\n";
		$httpQuery .= "Content-Length: ". strlen($request) ."\r\n";
		$httpQuery .= "Authorization: Basic ".base64_encode($this->_params['login']).":".base64_encode($this->_params['password'])."\r\n\r\n";
		$httpQuery .= $request;
		$sock=null;
		if($this->_params['scheme']=="https"){
			$prot="ssl://";
		}
		$sock = @fsockopen($prot.$this->_params['host'],$this->_params['port'], $errNo, $errString);
		if( !$sock){
			jLog::log('Erreur de connexion XMLRPC');
			jLog::dump($prot.$this->_params['host']);
			jLog::dump($this->_params['port']);
			jLOG::dump($httpQuery);
			jLOG::dump(strlen($httpQuery));
			jLOG::dump($errNo);
			jLOG::dump($errString);
			throw new jException('jelix~auth.error.lds.unreachable.server');
		}
		if( !fwrite($sock, $httpQuery, strlen($httpQuery))){
			throw new jException('jelix~auth.error.lds.request.not.send');
		}
		fflush($sock);
		while( !feof($sock)){
			$xmlResponse .= fgets($sock);
		}
		fclose($sock);
		$xmlResponse = substr($xmlResponse, strpos($xmlResponse, "\r\n\r\n") +4);
		$booleanFalse = "<?xml version='1.0'?>\n<methodResponse>\n<params>\n<param>\n<value><boolean>0</boolean></value>\n</param>\n</params>\n</methodResponse>\n";
		if($xmlResponse == $booleanFalse)
			$xmlResponse = "0";
		else{
			$xmlResponseTmp = xmlrpc_decode($xmlResponse,"UTF-8");
			if(!$xmlResponseTmp){
					$xmlResponse = iconv("ISO-8859-1","UTF-8",$xmlResponse);
					$xmlResponse = xmlrpc_decode($xmlResponse,"UTF-8");
			} else{
					$xmlResponse=$xmlResponseTmp;
			}
		}
		return $xmlResponse;
	}
}