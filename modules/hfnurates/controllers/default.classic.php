<?php
/**
* @package   havefnubb
* @subpackage hfnurates
* @author    FoxMaSk
* @copyright 2008 FoxMaSk
* @link      http://forge.jelix.org/projects/havefnubb
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/
class defaultCtrl extends jController {

    public $pluginParams = array(
        '*'		=> array('auth.required'=>false,
						 ),
    );
	function rate_it() {
		//info about the "source" from where the datas come from
		$id_source 	= (int) $this->param('id_source');
		$source		= (string) $this->param('source');		
		// the star
		$rate 		= (float) $this->param('star1');
		$rates 		= jClasses::getService('hfnurates~rates');
		$result 	= $rates->saveRatesBySource($id_source,$source,$rate);
		$rep 		= $this->getResponse('redirect');
		$rep->url   = (string) $this->param('redirect');
		return $rep;
	}
	function rate_ajax_it() {
		//info about the "source" from where the datas come from
		$id_source 	= (int) $this->param('id_source');
		$source		= (string) $this->param('source');		
		// the star
		$rate 		= (float) $this->param('star1');
		$rates 		= jClasses::getService('hfnurates~rates');
		$result 	= $rates->saveRatesBySource($id_source,$source,$rate);
		$rep = $this->getResponse('html');
		return $rep;
	}	
}
