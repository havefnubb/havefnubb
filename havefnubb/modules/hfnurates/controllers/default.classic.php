<?php
/**
* @package   havefnubb
* @subpackage hfnurates
* @author    FoxMaSk
* @copyright 2008 FoxMaSk
* @link      http://havefnubb.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/
/**
* Controller to manage the rates
*/
class defaultCtrl extends jController {
	/**
	 * @var plugins to manage the behavior of the controller
	 */
    public $pluginParams = array(
        '*'		=> array('auth.required'=>false),
    );
	/**
	 *Put a rate
	 */
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
	/**
	 *Put a rate (in ajax)
	 */
	function rate_ajax_it() {
		//info about the "source" from where the datas come from
		$id_source 	= (int) $this->param('id_source');
		$source		= (string) $this->param('source');

		//check if the cancel button was selected
		if ($id_source == 0 or $source == '') return;

		$rate 		= (float) $this->param('star1');
		$rates 		= jClasses::getService('hfnurates~rates');
		$rates->saveRatesBySource($id_source,$source,$rate);
		$result     = $rates->getTotalRatesBySource($id_source,$source);

		$rep = $this->getResponse('htmlfragment');
		$rep->addContent( jLocale::get('hfnurates~main.total.of.rates').':'.$result->total_rates . ' ' . jLocale::get('hfnurates~main.rate') .':'. $result->avg_level );
		return $rep;
	}
}
