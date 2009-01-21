<?php
/**
* @package   havefnubb
* @subpackage havefnubb
* @author    FoxMaSk
* @copyright 2008 FoxMaSk
* @link      http://www.havefnu.com
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

require_once (JELIX_LIB_CORE_PATH.'response/jResponseHtml.class.php');

class fnuHtmlResponse extends jResponseHtml {

    public $bodyTpl = 'havefnubb~main';

    function __construct() {
        parent::__construct();

        // Include your common CSS and JS files here
    }

    protected function doAfterActions() {
        // Include all process in common for all actions, like the settings of the
        // main template, the settings of the response etc..
        global $gJConfig;
        global $HfnuConfig;

        $chemin = $gJConfig->urlengine['basePath'].'themes/'.$gJConfig->theme.'/';
        $this->addCssLink($chemin.'css/havefnuboard.css');        

        $title = $HfnuConfig->getValue('title','board');
        $description = $HfnuConfig->getValue('description','board');

        if ($this->title)
            $this->title = $title . ' - ' . $this->title;        
        else
            $this->title = $title;
        
       
        
        $this->body->assignIfNone('TITLE',$title);
        $this->body->assignIfNone('DESC',$description);
        $this->body->assignIfNone('BOARD_TITLE',$title);
        
        // let says where we are in the main.tpl template
        $this->body->assign('HfnuCurrentAction',$GLOBALS['gJCoord']->action->toString());               
        
    }
}
