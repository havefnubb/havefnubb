<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/

/**
* page for Installation wizard
*
* @package     InstallWizard
* @subpackage  pages
* @author      Laurent Jouanneau
* @copyright   2010 Laurent Jouanneau
* @link        http://jelix.org
* @licence     GNU General Public Licence see LICENCE file or http://www.gnu.org/licenses/gpl.html
*/
class endWizPage extends installWizardPage{
	function show($tpl){
		return false;
	}
	function process(){
		return 0;
	}
}
