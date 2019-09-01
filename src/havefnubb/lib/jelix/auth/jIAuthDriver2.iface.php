<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
 * @package    jelix
 * @subpackage auth
 * @author     Laurent Jouanneau
 * @copyright   2019 Laurent Jouanneau
 */
interface jIAuthDriver2 extends jIAuthDriver
{
	public function canChangePassword($login);
}
