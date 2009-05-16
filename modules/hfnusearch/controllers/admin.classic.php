<?php
/**
* @package   havefnubb
* @subpackage hfnusearch
* @author    FoxMaSk
* @copyright 2008 FoxMaSk
* @link      http://forge.jelix.org/projects/havefnubb
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

class adminCtrl extends jController {
    /**
    *
    */
    public $pluginParams = array(
        '*'		=>	array('auth.required'=>true,
						  'hfnu.check.installed'=>true,
						  'banuser.check'=>true,
					),
        );
        
    function index() {		
        $rep = $this->getResponse('html');

        $form = jForms::create('hfnusearch~indexing');
        $tpl = new jTpl();
        $tpl->assign('form',$form);
        $rep->body->assign('MAIN', $tpl->fetch('hfnusearch~admin.index'));
		$rep->body->assign('selectedMenuItem','searchengine');	
        return $rep;
    }
    
    function reindexing() {
        $confirm = $this->param('confirm');
        
        if ($confirm == 'Y') {            
            $idx = jClasses::getService('hfnusearch~search_index');
            $idx->searchEngineReindexing();
            //@TODO : adding the count of reindexing words + posts
            jMessage::add(jLocale::get('hfnusearch~search.admin.reindexing.done'));
        }
        else {
            jMessage::add(jLocale::get('hfnusearch~search.admin.reindexing.canceled'));
        }
        $rep = $this->getResponse('redirect');
        $rep->action = 'hfnusearch~admin:index';
        return $rep;
    }
        
}

