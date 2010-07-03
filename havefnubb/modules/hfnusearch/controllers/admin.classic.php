<?php
/**
* @package   havefnubb
* @subpackage hfnusearch
* @author    FoxMaSk
* @copyright 2008 FoxMaSk
* @link      http://havefnubb.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/
/**
 * Controller that manages the search engine index
 */
class adminCtrl extends jController {
    /**
     * @var plugins to manage the behavior of the controller
     */
    public $pluginParams = array(
        '*' => array('auth.required'=>true,
            'banuser.check'=>true,
          ),
        );
    /**
     * form to ask to reindex
     */
    function index() {
        $rep = $this->getResponse('html');

        $form = jForms::create('hfnusearch~indexing');
        $tpl = new jTpl();
        $tpl->assign('form',$form);
        $rep->body->assign('MAIN', $tpl->fetch('hfnusearch~admin.index'));
        $rep->body->assign('selectedMenuItem','searchengine');
        return $rep;
    }
    /**
     * Reindexing the search engine
     */
    function reindexing() {
        $confirm = $this->param('confirm');

        if ($confirm == 'Y') {
            $idx = jClasses::getService('hfnusearch~search_index');
            $nbWords = $idx->searchEngineReindexing();
            jMessage::add(jLocale::get('hfnusearch~search.admin.reindexing.done',$nbWords));
        }
        else {
            jMessage::add(jLocale::get('hfnusearch~search.admin.reindexing.canceled'));
        }
        $rep = $this->getResponse('redirect');
        $rep->action = 'hfnusearch~admin:index';
        return $rep;
    }

}
