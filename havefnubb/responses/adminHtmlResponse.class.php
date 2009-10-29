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

class adminHtmlResponse extends jResponseHtml {

    public $bodyTpl = 'master_admin~main';

    function __construct() {
        parent::__construct();

        // Include your common CSS and JS files here
        $this->addCSSLink($GLOBALS['gJConfig']->urlengine['jelixWWWPath'].'design/master_admin.css');

        $this->addCssLink($GLOBALS['gJConfig']->urlengine['basePath'].'hfnu/admin/css/havefnuboard_admin.css');
        $chemin = $GLOBALS['gJConfig']->urlengine['basePath'].'themes/'.$GLOBALS['gJConfig']->theme.'/';
		$this->addCSSLink($chemin.'css/downloads.css');		
    }

    protected function doAfterActions() {
        // Include all process in common for all actions, like the settings of the
        // main template, the settings of the response etc..
        $this->title .= ($this->title !=''?' - ':'').' Administration';
        $this->body->assignIfNone('selectedMenuItem','');
        $this->body->assignZone('MENU','master_admin~admin_menu', array('selectedMenuItem'=>$this->body->get('selectedMenuItem')));
		$this->body->assignZone('INFOBOX','master_admin~admin_infobox');
        $this->body->assignIfNone('MAIN','');
        $this->body->assignIfNone('adminTitle','');
        $this->body->assign('user', jAuth::getUserSession());
    }

}
