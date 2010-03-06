<?php
/**
* @package    havefnubb
* @subpackage coord_plugin
* @author     foxmask
* @contributor
* @copyright  2008 foxmask
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/
/**
 * Class that manages the timeout of the connection of the user
 */
class timeoutCoordPlugin implements jICoordPlugin {
	public $config;

	function __construct($conf){
		$this->config = $conf;
	}

	public function beforeAction ($params){
		$selector = null;
		$now = time();
		// mainly used in install process which does not requier to check that behavior
		if(isset($params['hfnu.timeout.do.not.check']))
			return;

		$daoConnected       =  $this->config['dao_connected'];
		$daoMember          =  $this->config['dao_member'];
		$timeoutVisit       = ($this->config['timeout_visit'] > 0 )     ? $this->config['timeout_visit']     : 600;
		$timeoutConnected   = ($this->config['timeout_connected'] > 0 ) ? $this->config['timeout_connected'] : 600;

		jClasses::inc('havefnubb~timeout');
		timeout::check($daoConnected,$daoMember,$timeoutConnected,$timeoutVisit);

		return $selector;
	}

	public function beforeOutput(){}

	public function afterProcess (){}

}
