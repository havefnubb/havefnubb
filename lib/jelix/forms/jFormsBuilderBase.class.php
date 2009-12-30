<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package     jelix
* @subpackage  forms
* @author      Laurent Jouanneau
* @contributor Loic Mathaud, Dominique Papin
* @copyright   2006-2007 Laurent Jouanneau, 2007 Dominique Papin
* @copyright   2007 Loic Mathaud
* @link        http://www.jelix.org
* @licence     http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/
abstract class jFormsBuilderBase{
	protected $_form;
	protected $_action;
	protected $_actionParams = array();
	protected $_name;
	protected $_endt = '/>';
	public function __construct($form){
		$this->_form = $form;
	}
	public function setAction( $action, $actionParams){
		$this->_action = $action;
		$this->_actionParams = $actionParams;
		$this->_name = jFormsBuilderBase::generateFormName($this->_form->getSelector());
		if($GLOBALS['gJCoord']->response!= null && $GLOBALS['gJCoord']->response->getType() == 'html'){
			$this->_endt =($GLOBALS['gJCoord']->response->isXhtml()?'/>':'>');
		}
	}
	public function getName(){ return  $this->_name;}
	abstract public function outputMetaContent($tpl);
	abstract public function outputHeader($params);
	abstract public function outputFooter();
	abstract public function outputAllControls();
	abstract public function outputControl($ctrl);
	abstract public function outputControlLabel($ctrl);
	protected static function generateFormName($sel){
		static $forms = array();
		$name = 'jforms_'.str_replace('~','_',$sel);
		if(isset($forms[$sel])){
			return $name.(++$forms[$sel]);
		} else
			$forms[$sel] = 0;
		return $name;
	}
}