<?php
/**
* @package   havefnubb
* @subpackage hfnuadmin
* @author    FoxMaSk
* @copyright 2008 FoxMaSk
* @link      http://forge.jelix.org/projects/havefnubb
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

class forumCtrl extends jController {
    /**
    *
    */
    public $pluginParams = array(
        'index'    => array( 'jacl2.rights.and'=>array('hfnu.admin.forum.create',
														'hfnu.admin.forum.edit')
							),		        
        'delete'    => array( 'jacl2.right'=>'hfnu.admin.forum.delete'),
    );
    

    function index() {
        $form = jForms::create('hfnuadmin~category_list');
        $tpl = new jTpl();        
        $rep = $this->getResponse('html');

        $tpl->assign('form',$form);
        
        $rep->body->assign('MAIN', $tpl->fetch('hfnuadmin~forum_index'));
        return $rep;     
    }

    function create () {
        $possibleActions = array('in_cat','before','after','childof');
        // the choice is ?
        $choice = (string) $this->param('forum');
        // build the next param to check
        $put = 'put_'.$choice;
        // the id_forum is ?
        $id_forum = (int) $this->param($put);
  
        //check if submitted data are ok.
        if ($id_forum == 0 or ! in_array($choice,$possibleActions) ) {
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
                case 'in_cat' : $parent_id = 0;
                            $child_level = 0;
                            $forum_order = 0;
/***********************************************************************************
 /!\ id_forum contains the id cat when we choose to add a forum to a category ! /!\
 ***********************************************************************************/
                            $id_cat = $id_forum; 
                            break;
            }
            
            
            $record = jDao::createRecord('havefnubb~forum');        
            $record->forum_name = jLocale::get('hfnuadmin~forum.new.forum');
            $record->id_cat     = $id_cat;
            $record->parent_id  = $parent_id;
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
        $id_forum = (int) $this->param('id_forum');
        
        if ($id_forum == 0 ) {
            jMessage::add(jLocale::get('hfnuadmin~forum.invalid.datas'),'error');
            $rep = $this->getResponse('redirect');
            $rep->action='hfnuadmin~default:forums';
            return $rep;
        }
        $dao = jDao::get('havefnubb~forum');
        $forum = $dao->get($id_forum);
    

        $rep = $this->getResponse('html');
        $tpl = new jTpl();

        $gid=array(0);
        $o = new StdClass;
        $o->id_aclgrp ='0';
        $o->name = jLocale::get('jacl2_admin~acl2.anonymous.group.name');
        $o->grouptype=0;
        $groups=array($o);
        $grouprights=array(0=>false);
        
        $dao = jDao::get('jelix~jacl2group',jAcl2Db::getProfile())->findAllPublicGroupExceptAdmins();
        foreach($dao as $grp) {
            $gid[]=$grp->id_aclgrp;
            $groups[]=$grp;
            $grouprights[$grp->id_aclgrp]=false;
        }
        $rights=array();
        $p = jAcl2Db::getProfile();

        $rs = jDao::get('jelix~jacl2subject',$p)->findHfnuSubject();
        foreach($rs as $rec){
            $rights[$rec->id_aclsbj] = $grouprights;
        }

        $rs = jDao::get('jelix~jacl2rights',$p)->getHfnuRightsByGroups($gid,'forum'.$id_forum);
        foreach($rs as $rec){
            $rights[$rec->id_aclsbj][$rec->id_aclgrp] = true;
        }

        $tpl->assign(compact('groups', 'rights'));

        $tpl->assign('forum',$forum);
        $rep->body->assign('MAIN',$tpl->fetch('forum_edit'));
        return $rep;        
    }


    function saveedit () {
        $id_forum = (int) $this->param('id_forum');
        
        if ($id_forum == 0) {
            jMessage::add(jLocale::get('hfnuadmin~forum.unknown.forum'),'error');
            $rep = $this->getResponse('redirect');
            $rep->action='hfnuadmin~default:forums';
            return $rep;                 
        }
        $error = '';
        if ( $this->param('forum_name') == '' ) {
            jMessage::add(jLocale::get('hfnuadmin~forum.forum_name.mandatory'),'error');
            $error = '*';
        }
        if ( $this->param('forum_desc') == '' ) {
            jMessage::add(jLocale::get('hfnuadmin~forum.forum_desc.mandatory'),'error');
            $error = '*';
        }
        if ( $this->param('forum_order') == '' ) {
            jMessage::add(jLocale::get('hfnuadmin~forum.forum_desc.mandatory'),'error');
            $error = '*';
        }        
        if ($error == '*') {
            $rep = $this->getResponse('redirect');
            $rep->action='hfnuadmin~forum:edit';
            $rep->params = array('id_forum'=>$id_forum);
            return $rep;                 
        }
        
        
        $dao = jDao::get('havefnubb~forum');
        
        $record = $dao->get($id_forum);
        $record->forum_name = $this->param('forum_name');
        $record->forum_desc = $this->param('forum_desc');
        $record->forum_order = $this->param('forum_order');
        
        $dao->update($record);
        
        jMessage::add(jLocale::get('hfnuadmin~forum.forum.modified'),'ok');
        
        /**************/
        
        $rights = $this->param('rights',array());

        foreach(jAcl2DbUserGroup::getGroupList() as $grp) {
            $id = intval($grp->id_aclgrp);
            self::setRightsOnForum($id, (isset($rights[$id])?$rights[$id]:array()),'forum'.$id_forum);
        }

        self::setRightsOnForum(0, (isset($rights[0])?$rights[0]:array()),'forum'.$id_forum);
       
        /**************/
        
        $rep = $this->getResponse('redirect');
        $rep->action='hfnuadmin~default:forums';
        return $rep;   
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



    /**
     * set rights on the given group. old rights are removed
     * @param int    $group the group id.
     * @param array  $rights, list of rights key=subject, value=true
     *  @param string  $resource, the resource corresponding to the "forum" string + id_forum
     */
    public static function setRightsOnForum($group, $rights, $resource){
        $dao = jDao::get('jelix~jacl2rights', jAcl2Db::getProfile());
        $dao->deleteHfnuByGroup($group,$resource);
        foreach($rights as $sbj=>$val){
            if($val != '')
              jAcl2DbManager::addRight($group,$sbj,$resource);
        }
        jAcl2::clearCache();
    }

    
}   