<?php
/**
* @package   havefnubb
* @subpackage havefnubb
* @author    FoxMaSk
* @copyright 2008 FoxMaSk
* @link      http://havefnubb.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

require_once (JELIX_LIB_CORE_PATH.'response/jResponseHtml.class.php');

class installHtmlResponse extends jResponseHtml {

    public $bodyTpl = 'hfnuinstall~main_install';

    function __construct() {
        parent::__construct();

        // Include your common CSS and JS files here
    }

    protected function doAfterActions() {
        // Include all process in common for all actions, like the settings of the
        // main template, the settings of the response etc..
        global $gJConfig;
        global $HfnuConfig;

        $chemin = $gJConfig->urlengine['basePath'].'themes/install/';
        $this->addCssLink($chemin.'css/install.css');        

        $title = stripslashes($HfnuConfig->getValue('title','main'));
        $description = stripslashes($HfnuConfig->getValue('description','main'));

        if ($this->title)
            $this->title = $title . ' - ' . $this->title;        
        else
            $this->title = $title;       
        
        $this->body->assignIfNone('TITLE',$title);
        $this->body->assignIfNone('DESC',$description);
        $this->body->assignIfNone('BOARD_TITLE',$title);        
        
        if (!empty($GLOBALS['gJCoord']->request->params)) {
            $params = $GLOBALS['gJCoord']->request->params;
   
            if (!array_key_exists('step',$params))
                $this->body->assign('step','home');
            else   
            switch($params['step']) {
                 case 'check':
                    $this->body->assign('step','check');
                    break;
                 case 'config':
                    $this->body->assign('step','config');
                    break;
                 case 'dbconfig':
                    $this->body->assign('step','dbconfig');
                    break;
                 case 'installdb':
                    $this->body->assign('step','installdb');
                    break;
                 case 'adminaccount':
                    $this->body->assign('step','adminaccount');
                    break;                
                 case 'end':
                    $this->body->assign('step','end');
                    break;
				case 'update':
                    $this->body->assign('step','update');
                    break;   
                default:
                    $this->body->assign('step','home');
                    break;
            }
        }
        else
            $this->body->assign('step','home');
                
    }
}
