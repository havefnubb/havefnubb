<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package     jelix
* @subpackage  installer
* @author      Laurent Jouanneau
* @contributor 
* @copyright   2009 Laurent Jouanneau
* @link        http://www.jelix.org
* @licence     GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
*/
class jInstallerException extends Exception{
	protected $localeKey='';
	protected $localeParams=null;
	public function __construct($localekey,$localeParams=null){
		$this->localeKey=$localekey;
		$this->localeParams=$localeParams;
		parent::__construct($localekey,0);
	}
	public function getLocaleParameters(){
		return $this->localeParams;
	}
	public function getLocaleKey(){
		return $this->localeKey;
	}
}
