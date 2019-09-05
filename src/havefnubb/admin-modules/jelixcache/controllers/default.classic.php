<?php
/**
* @package   havefnubb
* @subpackage jelixcache
* @author    FoxMaSk
* @contributor Laurent Jouanneau
* @copyright 2008-2011 FoxMaSk, 2010-2019 Laurent Jouanneau
* @link      https://havefnubb.jelix.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/
/**
 * This controller manages the cache of the forum
 */
class defaultCtrl extends jController {
    /**
     * @var array $pluginParams plugins parameters to manage the behavior of the controller
     */
    public $pluginParams = array(
        '*' => array('auth.required'=>true,
            'banuser.check'=>true,
            'jacl2.right' =>'jelixcache.access'
      ),
    );

    function index() {
        $tpl = new jTpl();
        $rep = $this->getResponse('html');
        $rep->body->assign('MAIN', $tpl->fetch('cache_index'));
        $rep->body->assign('selectedMenuItem','cache');
        return $rep;
    }

    function clear() {
        $confirm = $this->param('confirm');

        if ($confirm == 'Y') {
            jFile::removeDir(jApp::tempPath(), false);
            jMessage::add(jLocale::get('jelixcache~jelixcache.cache.clear.done'));
        }
        else {
            jMessage::add(jLocale::get('jelixcache~jelixcache.cache.clear.canceled'));
        }
        $rep = $this->getResponse('redirect');
        $rep->action = 'jelixcache~default:index';
        return $rep;
    }
}
