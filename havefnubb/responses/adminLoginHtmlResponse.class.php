<?php
/**
* @package   havefnubb
* @subpackage responses
* @author    FoxMaSk
* @copyright 2008 FoxMaSk
* @link      http://havefnubb.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/
require_once (JELIX_LIB_CORE_PATH.'response/jResponseHtml.class.php');
/**
 * Class that manages the Response of the Admin part of HaveFnuBB
 */
class adminLoginHtmlResponse extends jResponseHtml {
	/**
	 * method which manages the things that go to <HEAD>...</HEAD>
	 */
	function __construct() {
		parent::__construct();
		// Include your common CSS and JS files here
		$this->addCSSLink($GLOBALS['gJConfig']->urlengine['jelixWWWPath'].'design/master_admin.css');
	}
	/**
	 * method which manages 'globales' behavior/var
	 */
	protected function doAfterActions() {
		$this->bodyTpl = 'master_admin~index_login';
		// Include all process in common for all actions, like the settings of the
		// main template, the settings of the response etc..
	   $this->title .= ($this->title !=''?' - ':'').'Administration';
	   $this->body->assignIfNone('MAIN','');
	}
}
