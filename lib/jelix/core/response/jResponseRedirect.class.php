<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package     jelix
* @subpackage  core_response
* @author      Laurent Jouanneau
* @contributor Aubanel Monnier (patch for anchor)
* @contributor Loic Mathaud (fix bug)
* @contributor Afroxav (bug fix)
* @copyright   2005-2009 Laurent Jouanneau,  2007 Loic Mathaud
* @link        http://www.jelix.org
* @licence     GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
*/
final class jResponseRedirect extends jResponse{
	protected $_type='redirect';
	public $action='';
	public $anchor='';
	public $params=array();
	public $temporary=true;
	public function output(){
		if($this->hasErrors())return false;
		if($this->temporary)
			$this->setHttpStatus(303,'See Other');
		else
			$this->setHttpStatus(301,'Moved Permanently');
		$this->sendHttpHeaders();
		header('Location: '.jUrl::get($this->action,$this->params).($this->anchor!='' ? '#'.$this->anchor:''));
		return true;
	}
	public function outputErrors(){
		include_once(JELIX_LIB_CORE_PATH.'response/jResponseHtml.class.php');
		$resp=new jResponseHtml();
		$resp->outputErrors();
	}
}
