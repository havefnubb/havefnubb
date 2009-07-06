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
		$rep->action= (string) $this->param('return_url');
        $rep->params= (array) $this->param('return_url_params');
		return $rep;
	}
	function rate_ajax_it() {
		//info about the "source" from where the datas come from
		$id_source 	= (int) $this->param('id_source');
		$source		= (string) $this->param('source');		
		// the star
		$rate 		= (float) $this->param('star1');
		$rates 		= jClasses::getService('hfnurates~rates');
		$rates->saveRatesBySource($id_source,$source,$rate);
        $result     = $rates->getTotalRatesBySource($id_source,$source);
        
		$rep = $this->getResponse('htmlfragment');
        $rep->addContent( jLocale::get('hfnurates~main.total.of.rates').':'.$result[0]->total_rates . ' ' . jLocale::get('hfnurates~main.rate') .':'. $result[1]->avg_level );
		return $rep;
	}	
}
