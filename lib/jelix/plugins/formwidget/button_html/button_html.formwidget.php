<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package     jelix
* @subpackage  formwidgets
* @author      Claudio Bernardes
* @contributor Laurent Jouanneau, Julien Issler, Dominique Papin
* @copyright   2012 Claudio Bernardes
* @copyright   2006-2012 Laurent Jouanneau, 2008-2011 Julien Issler, 2008 Dominique Papin
* @link        http://www.jelix.org
* @licence     http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/
class button_htmlFormWidget extends \jelix\forms\HtmlWidget\WidgetBase{
	function outputControl(){
		$attr=$this->getControlAttributes();
		unset($attr['readonly']);
		unset($attr['class']);
		$attr['value']=$this->getValue($this->ctrl);
		echo '<button ';
		$this->_outputAttr($attr);
		echo '>',htmlspecialchars($this->ctrl->label),'</button>';
	}
}
