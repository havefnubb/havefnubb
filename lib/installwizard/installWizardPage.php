<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/

/**
* Installation wizard
*
* @package     InstallWizard
* @author      Laurent Jouanneau
* @copyright   2010 Laurent Jouanneau
* @link        http://jelix.org
* @licence     GNU General Public Licence see LICENCE file or http://www.gnu.org/licenses/gpl.html
*/
class installWizardPage{
	public $title='title';
	public $config;
	protected $locales=array();
	protected $errors=array();
	function __construct($confParameters,$locales){
		$this->config=$confParameters;
		$this->locales=$locales;
	}
	function show($tpl){
		return true;
	}
	function process(){
		return 0;
	}
	function getErrors(){
		return $this->errors;
	}
	function getLocale($key){
		if(isset($this->locales[$key]))
			return $this->locales[$key];
		else return '';
	}
}
