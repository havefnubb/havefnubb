<?php
/**
* @package   havefnubb
* @subpackage responses
* @author    FoxMaSk
* @copyright 2008-2011 FoxMaSk
* @link      https://havefnubb.jelix.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/
require_once (JELIX_LIB_CORE_PATH.'response/jResponseHtml.class.php');
/**
 * Class that manages the Response of the Admin part of HaveFnuBB
 */
class adminHtmlResponse extends jResponseHtml {
    /**
     * @var $bodyTpl the main template of the entire application
     */
    public $bodyTpl = 'master_admin~main';
    /**
     * method which manages the things that go to <HEAD>...</HEAD>
     */
    function __construct() {
        parent::__construct();

        $conf = jApp::config();
        // Include your common CSS and JS files here
        $this->addCSSLink($conf->urlengine['jelixWWWPath'].'design/master_admin.css');

        $this->addCssLink($conf->urlengine['basePath'].'hfnu/admin/css/havefnuboard_admin.css');
        $chemin = $conf->urlengine['basePath'].'themes/'.$conf->theme.'/';
    }
    /**
     * method which manages 'globales' behavior/var
     */
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
