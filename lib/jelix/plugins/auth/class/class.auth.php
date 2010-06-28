<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package    jelix
* @subpackage auth_driver
* @author      Laurent Jouanneau
* @contributor Yannick Le Guédart (adaptation de jAuthDriverDb pour une classe quelconque)
* @copyright   2006-2007 Laurent Jouanneau, 2006 Yannick Le Guédart
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/
interface jIAuthDriverClass{
	public function insert($user);
	public function deleteByLogin($login);
	public function update($user);
	public function getByLogin($login);
	public function createUserObject();
	public function findAll();
	public function findByLoginPattern($pattern);
	public function updatePassword($login,$cryptedpassword);
	public function getByLoginPassword($login,$cryptedpassword);
}
class classAuthDriver implements jIAuthDriver{
	protected $_params;
	function __construct($params){
		$this->_params=$params;
	}
	public function saveNewUser($user){
		$class=jClasses::create($this->_params['class']);
		$class->insert($user);
		return true;
	}
	public function removeUser($login){
		$class=jClasses::create($this->_params['class']);
		$class->deleteByLogin($login);
		return true;
	}
	public function updateUser($user){
		$class=jClasses::create($this->_params['class']);
		$class->update($user);
		return true;
	}
	public function getUser($login){
		$class=jClasses::create($this->_params['class']);
		return $class->getByLogin($login);
	}
	public function createUserObject($login,$password){
		$class=jClasses::create($this->_params['class']);
		$user=$class->createUserObject();
		$user->login=$login;
		$user->password=$this->cryptPassword($password);
		return $user;
	}
	public function getUserList($pattern){
		$class=jClasses::create($this->_params['class']);
		if($pattern=='%'||$pattern==''){
			return $class->findAll();
		}else{
			return $class->findByLoginPattern($pattern);
		}
	}
	public function changePassword($login,$newpassword){
		$class=jClasses::create($this->_params['class']);
		return $class->updatePassword($login,$this->cryptPassword($newpassword));
	}
	public function verifyPassword($login,$password){
		if(trim($password)=='')
			return false;
		$classuser=jClasses::create($this->_params['class']);
		$user=$classuser->getByLoginPassword($login,$this->cryptPassword($password));
		return($user?$user:false);
	}
	protected function cryptPassword($password){
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
