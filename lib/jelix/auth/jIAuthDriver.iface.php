<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package    jelix
* @subpackage auth
* @author     Laurent Jouanneau
* @contributor Frédéric Guillot, Antoine Detante, Julien Issler
* @copyright   2005-2008 Laurent Jouanneau, 2007 Frédéric Guillot, 2007 Antoine Detante
* @copyright   2007 Julien Issler
*
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
