<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package      jelix
* @subpackage   core
* @author       Christophe Thiriot
* @copyright    2008 Christophe Thiriot
* @link         http://www.jelix.org
* @licence      GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
*/
class jCmdlineCoordinator extends jCoordinator{
	public function process($request){
		parent::process($request);
		exit($this->response->getExitCode());
	}
}
