<?php
/**
* @package     jelix
* @subpackage  core_request
* @author      Laurent Jouanneau
* @contributor Loic Mathaud
* @contributor Thibault PIRONT < nuKs >
* @contributor Thiriot Christophe
* @copyright   2005-2008 Laurent Jouanneau, 2006-2007 Loic Mathaud
* @copyright   2007 Thibault PIRONT
* @copyright   2008 Thiriot Christophe
* @link        http://www.jelix.org
* @licence     GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
*/

/**
 * a request object for scripts used in a command line
 * @package     jelix
 * @subpackage  core_request
 */
class jCmdLineRequest extends jRequest {

    public $type = 'cmdline';

    public $defaultResponseType = 'cmdline';

    protected $onlyDefaultAction = false;
    
    /**
     * If you want to have a CLI script dedicated to the default action,
     * tell it by given true, so you haven't to indicate the action
     * on the command line. It means of course you couldn't execute any other
     * actions with this script.
     * @param boolean $onlyDefaultAction 
     */
    function __construct($onlyDefaultAction = false){
        $this->onlyDefaultAction = $onlyDefaultAction;
    }

    public function isAllowedResponse($respclass){
        return ('jResponseCmdline' == $respclass);
    }

    protected function _initUrlData(){ 
        global $gJConfig; 
        $this->urlScriptPath = '/'; 
        $this->urlScriptName = $this->urlScript = $_SERVER['SCRIPT_NAME']; 
        $this->urlPathInfo = ''; 
    }

    protected function _initParams(){
        global $gJConfig;

        $argv = $_SERVER['argv'];
        $scriptName = array_shift($argv); // shift the script name

        if ($this->onlyDefaultAction) {
            $mod = $gJConfig->startModule;
            $act = $gJConfig->startAction;
            if ($_SERVER['argc'] > 1 && $argv[0] == 'help') {
                $mod = 'jelix';
                $act = 'help:index';
                $argv[0] = $gJConfig->startModule.'~'.$gJConfig->startAction;
            }
        }
        else {
            // note: we cannot use jSelectorAct to parse the action
            // because in the opt edition, jSelectorAct needs an initialized jCoordinator
            // and this is not the case here. see bug #725.
    
            if ($_SERVER['argc'] == 1) {
                $mod = $gJConfig->startModule;
                $act = $gJConfig->startAction;
            } else {
                $argsel = array_shift($argv); // get the module~action selector
                if ($argsel == 'help') {
                    $mod = 'jelix';
                    $act = 'help:index';
                }else if (($pos = strpos($argsel,'~')) !== false) {
                    $mod = substr($argsel,0,$pos);
                    $act = substr($argsel,$pos+1);
                }else {
                    $mod= $gJConfig->startModule;
                    $act= $argsel;
                }
            }
        }
        $this->params = $argv;
        $this->params['module'] = $mod;
        $this->params['action'] = $act;
    }
}

