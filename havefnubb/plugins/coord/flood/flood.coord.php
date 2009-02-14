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
require(JELIX_APP_PATH.'modules/havefnubb/classes/flood.class.php');

/**
* @package    jelix
* @subpackage coord_plugin
* @since 1.0.1
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
        $error_message = '';
        $on_error_action = '';
        
        if(isset($params['flood.same.ip'])) {
            if ($this->config['elapsed_time_between_two_post_by_same_ip'] > 0) {
                $error_message = "havefnubb~flood.elapsed_time_between_two_post_by_same_ip.error";
                $on_error_action = 'on_error_action_same_ip';
                $floodok = flood::check('same_ip',$this->config['elapsed_time_between_two_post_by_same_ip']);
            }

        }elseif(isset($params['flood.editing'])) {
            if ($this->config['elapsed_time_after_posting_before_editing'] > 0) {
                $error_message = "havefnubb~flood.elapsed_time_after_posting_before_editing.error";
                $on_error_action = 'on_error_action_editing';
                $floodok = flood::check('editing',$this->config['elapsed_time_after_posting_before_editing']);
            }            
        }

        if(!$floodok){
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

