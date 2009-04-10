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


/**
* @package    jelix
* @subpackage coord_plugin
* @since 1.0.1
*/
class hfnuinstalledCoordPlugin implements jICoordPlugin {
    public $config;

    function __construct($conf){
        $this->config = $conf;
    }

    /**
     * @param  array  $params   plugin parameters for the current action
     * @return null or jSelectorAct  if action should change
     */
    public function beforeAction ($params){
        global $HfnuConfig;
        $selector = null;
        $ok = true;
        $error_message = '';
        $on_error_action = '';
        
        if(isset($params['hfnu.check.installed'])) {
            if ($HfnuConfig->getValue('installed') == 1)
                $ok = true;
            else {
                $on_error_action = 'on_error_action';
                $ok = false;
                $error_message = jLocale::get('havefnubb~main.forum.is.not.installed');
            }
        }
        if(!$ok){
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

