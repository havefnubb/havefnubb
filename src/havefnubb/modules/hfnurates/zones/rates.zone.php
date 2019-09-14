<?php
/**
* @package   havefnubb
* @subpackage hfnurates
* @author    FoxMaSk
* @contributor Laurent Jouanneau
* @copyright 2008-2011 FoxMaSk, 2019 Laurent Jouanneau
* @link      https://havefnubb.jelix.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/
/**
 * Zone to Handle form of the rates
 */
class ratesZone extends jZone {
	/**
	 *@var string $_tplname the template name used by the zone
	 */
	protected $_tplname='zone.rates';
	/**
	 * function to manage data before assigning to the template of its zone
	 */
	protected function _prepareTpl(){

		// define the number of stars used in the template
		// this will determine which star is selected
		// when the rate is done.
		define ('SCALE',5);

		$id_source = (int) $this->param('id_source');
		if ( $id_source == 0) return;

		$source = (string) $this->param('source');
		if (! $source ) return;

		$return_url = (string) $this->param('return_url');
		if (! $return_url ) return;

		$return_url_params = $this->param('return_url_params');
		if (! $return_url_params ) return;

		$rates 	= jClasses::getService('hfnurates~rates');
		$result =  $rates->getTotalRatesBySource($id_source,$source);
		$resultText = '';
		if ($result) {
			$resultText = jLocale::get('hfnurates~main.total.of.rates') . ':'.$result->total_rates . ' ' . jLocale::get('hfnurates~main.rate') .':'. $result->avg_level;
			if ($result->avg_level > 0)
				$checked = round(100 * $result->avg_level / SCALE);
			else $checked = 0;
		}
		else
			$checked = 0;

		$this->_tpl->assign('checked',$checked);
		$this->_tpl->assign('id_source',$id_source);
		$this->_tpl->assign('source',$source);
		$this->_tpl->assign('result',$resultText);
		$this->_tpl->assign('return_url',$return_url);
		$this->_tpl->assign('return_url_params',$return_url_params);
	}
}
