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
 *
 */
require(JELIX_APP_PATH.'modules/havefnubb/classes/bans.class.php');

/**
* @package    jelix
* @subpackage coord_plugin
* @since 1.0.1
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
        $error_message = jLocale::get("hfnuadmin~ban.you.are.banned");
        $on_error_action = 'on_error_action';
        
        if(isset($params['banuser.check'])) {            
            $banok = bans::check();
        }

        if($banok){
            // disconnect the user if he was connected
            jAuth::logout();
            
            if($this->config['on_error'] == 1 
                || !$GLOBALS['gJCoord']->request->isAllowedResponse('jResponseRedirect')){
                throw new jException($error_message);
            }else{
                $selector= new jSelectorAct($this->config[$on_error_action]);
            }
        }

        return $selector;
    }

    public function beforeOutput(){}

    public function afterProcess (){}

}

