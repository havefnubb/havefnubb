<?php
/**
* @package    havefnubb
* @subpackage coord_plugin
* @author     foxmask
* @contributor Laurent Jouanneau
* @copyright  2008-2011 FoxMaSk, 2012-2026 Laurent Jouanneau
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

use Havefnubb\Havefnubb\Members\BansManager;

/**
 * Class that checkes if a user is banned
 */
class banuserCoordPlugin implements jICoordPlugin {
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
        $banok = false;

        if(isset($params['banuser.check'])) {
            $banok = BansManager::check();
        }

        if($banok){
            // disconnect the user if he was connected
            jAuth::logout();

            if($this->config['on_error'] == 1
                || !jApp::coord()->request->isAllowedResponse('jResponseRedirect')){
                throw new jException(jLocale::get("havefnubb~ban.you.are.banned"));
            }else{
                $selector= new jSelectorAct($this->config['on_error_action']);
            }
        }

        return $selector;
    }

    public function beforeOutput(){}

    public function afterProcess (){}

}
