<?php
/**
* @package   havefnubb
* @subpackage hfnuadmin
* @author    FoxMaSk
* @copyright 2008 FoxMaSk
* @link      http://forge.jelix.org/projects/havefnubb
* @license    All right reserved
*/

class forumCtrl extends jController {
    /**
    *
    */
    public $pluginParams = array(
        'create'    => array( 'jacl2.right'=>'hfnu.admin.forum.create'),
        'edit'      => array( 'jacl2.right'=>'hfnu.admin.forum.edit'),
        'delete'    => array( 'jacl2.right'=>'hfnu.admin.forum.delete'),
    );
    

    function create () {
        // the choice is ?
        $choice = (string) $this->param('forum');
        // build the next param to check
        $put = 'put_'.$choice;
        // the id_forum is ?
        $id_forum = (int) $this->param($put);
        
        if ($id_forum == 0 ) {
            jMessage::add(jLocale::get('hfnuadmin~forum.invalid.datas'),'error');
            $rep = $this->getResponse('redirect');
            $rep->action='hfnuadmin~default:forums';
            return $rep;
        }
               
        if ($this->param('validate') == jLocale::get('hfnuadmin~forum.createBt')) {

            $dao = jDao::get('havefnubb~forum');
            
            $forum = $dao->get($id_forum);

            $parent_id      = 0;
            $child_level    = 0;
            $forum_order    = 0;
            $id_cat         = 0;
            // now check what choice has been done :
            switch ($choice) {
                // my parent is the selected forum so i add one level to child_level
                case 'childof' : $parent_id      = $forum->id_forum;
                            $child_level    = $forum->child_level +1;
                            $forum_order    = 0;
                            $id_cat         = $forum->id_cat;
                            break;
                // we have the same parent and same child_level        
                case 'before' :  $parent_id      = $forum->parent_id;
                            $child_level    = $forum->child_level;
                            $forum_order    = ($forum_order -1 < 0) ? 0 : $forum_order -1;
                            $id_cat         = $forum->id_cat;
                            break;
                case 'after' :   $parent_id      = $forum->parent_id;
                            $child_level    = $forum->child_level;
                            $forum_order    = $forum->forum_order +1;
                            $id_cat         = $forum->id_cat;
                            break;
            }
            
            
            $record = jDao::createRecord('havefnubb~forum');        
            $record->forum_name = jLocale::get('hfnuadmin~forum.new.forum');
            $record->id_cat = $id_cat;
            $record->parent_id = $parent_id;
            $record->child_level = $child_level;
            $record->forum_order = $forum_order;
            $record->forum_desc = jLocale::get('hfnuadmin~forum.new.forum');
            
            $dao->insert($record);
            
            jMessage::add(jLocale::get('hfnuadmin~forum.forum.added'),'ok');
            $rep = $this->getResponse('redirect');
            $rep->action='hfnuadmin~default:forums';
            return $rep;
        }
        else {
            $rep = $this->getResponse('redirect');
            $rep->action='hfnuadmin~default:forums';
            return $rep;
        }    
    }

    function edit () {
        $rep = $this->getResponse('html');
        $tpl = new jTpl();
        $tpl->assign('action','hfnuadmin~forum:savecreate');
        $tpl->assign('forum_heading',jLocale::get('hfnuadmin~forum.edit.a.forum'));
        $rep->body->assign('MAIN',$tpl->fetch('forum_edit'));
        return $rep;        
    }

    function saveedit () {
    
    }
    
    function delete() {
		$id_forum = (integer) $this->param('id_forum');
	
		$dao = jDao::get('havefnubb~forum');        
        $dao->delete($id_forum);
        
        jMessage::add(jLocale::get('hfnuadmin~forum.forum.deleted'),'ok');
        
        $rep = $this->getResponse('redirect');
        $rep->action='hfnuadmin~default:forums';
        return $rep;         
    }

    
}   