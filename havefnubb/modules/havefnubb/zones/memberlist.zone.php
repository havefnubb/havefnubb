<?php
/**
* @package   havefnubb
* @subpackage havefnubb
* @author    FoxMaSk
* @copyright 2008 FoxMaSk
* @link      http://forge.jelix.org/projects/havefnubb
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

class memberlistZone extends jZone {
    protected $_tplname='zone.memberlist';

    protected function _prepareTpl(){
        global $HfnuConfig;
        $page = (int) $this->param('page');
        
        $grpid = -2;
        if ($this->param('grpid')) 
            $grpid =  intval($this->param('grpid'));
        
        $nbMembersPerPage = (int) $HfnuConfig->getValue('members_per_page');
        
        $p = jAcl2Db::getProfile();

        if($grpid == -2) {
            //all users
            $dao = jDao::get('jelix~jacl2groupsofuser',$p);
            $cond = jDao::createConditions();
            $cond->addCondition('grouptype', '=', 2);
            $cond->addCondition('status', '=', 2);
            $rs = $dao->findBy($cond,$page,$nbMembersPerPage);
            $nbMembers = $dao->countBy($cond);

        } else {
            //in a specific group
            $dao = jDao::get('jelix~jacl2usergroup',$p);
            $rs = $dao->getPublicUsersGroupLimit($grpid, $page, $nbMembersPerPage);
            $nbMembers = $dao->getUsersGroupCount($grpid);
        }
        $members=array();
        $dao2 = jDao::get('jelix~jacl2groupsofuser',$p);
        foreach($rs as $u){
            $u->groups = array();
            $gl = $dao2->getGroupsUser($u->login);
            foreach($gl as $g) {
                
                if($g->grouptype != 2 and $g->status == 2)
                    $u->groups[]=$g;
            }
            $members[] = $u;
        }

        $groups=array();
        $o = new StdClass;
        $o->id_aclgrp ='-2';
        $o->name=jLocale::get('havefnubb~member.memberlist.allgroups');
        $o->grouptype=0;
        $groups[]=$o;
        foreach(jAcl2DbUserGroup::getGroupList() as $grp) {
            $groups[]=$grp;
        }
        
        // let's build the pagelink var
        // A Preparing / Collecting datas
        // 0- the properties of the pager
        $properties = array('start-label' => ' ',
                      'prev-label'  => '',
                      'next-label'  => '',
                      'end-label'   => jLocale::get("havefnubb~member.pagelinks.end"),
                      'area-size'   => 5);

        // 1- vars for pagelinks
        $this->_tpl->assign('groups', $groups);
        $this->_tpl->assign('page',$page);                
        $this->_tpl->assign('nbMembersPerPage',$nbMembersPerPage);
        $this->_tpl->assign('properties',$properties);
        $this->_tpl->assign('members',$members);
        $this->_tpl->assign('nbMembers',$nbMembers);
    }
}