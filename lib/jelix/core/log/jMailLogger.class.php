<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package    jelix
* @subpackage core
* @author     Laurent Jouanneau
* @copyright  2006-2012 Laurent Jouanneau
* @link       http://www.jelix.org
* @licence    GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
*/
class jMailLogger implements jILogger{
	protected $messages=array();
	function logMessage($message){
		$this->messages[]=$message;
	}
	function output($response){
		if(!jApp::coord()->request)
			return;
		$email=jApp::config()->mailLogger['email'];
		$headers=str_replace(array('\\r','\\n'),array("\r","\n"),jApp::config()->mailLogger['emailHeaders']);
		$message='';
		foreach($this->messages as $msg){
			$message.="\n\n".$msg->getFormatedMessage();
		}
		error_log(wordwrap($message,70),1,$email,$headers);
	}
}
