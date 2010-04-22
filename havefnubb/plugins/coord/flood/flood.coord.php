<?php
/**
* @package    havefnubb
* @subpackage coord_plugin
* @author     foxmask
* @contributor Laurent Jouanneau
* @copyright  2008 foxmask, 2010 Laurent Jouanneau
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/
/**
 * Class that checkes if a member is currently flooding
 */
class floodCoordPlugin implements jICoordPlugin {
	public $config;

	function __construct($conf){
		$this->config = $conf;
	}

	/**
	 * @param  array  $params   plugin parameters for the current action
	 * @return null or jSelectorAct  if action should change
	 */
	public function beforeAction ($params){
		$selector = null;
		$floodok = true;

        if (isset($params['check.flood'])
            && $params['check.flood']
            && $this->config['time_interval']) {
    		jClasses::inc('havefnubb~flood');

            $hasflood = flood::check($this->config['time_interval'], $this->config['only_same_ip']);
            if ($hasflood) {
    			if($this->config['on_error'] == 1
    				|| !$GLOBALS['gJCoord']->request->isAllowedResponse('jResponseRedirect')){
                    throw new jException("havefnubb~flood.elapsed_time_between_two_post");
                }
                else {
                    $selector = new jSelectorAct($this->config['on_error_action']);
                }
            }
        }
		return $selector;
	}

	public function beforeOutput(){}

	public function afterProcess (){}

}
