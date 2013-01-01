<?php
/**
* @package   havefnubb
* @subpackage havefnubb
* @author    FoxMaSk
* @copyright 2008-2011 FoxMaSk
* @link      http://havefnubb.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/
/**
 * Class the displays the list of members
 */
class memberlistZone extends jZone {
    /**
     *@var string $_tplname the template name used by the zone
     */
    protected $_tplname='zone.memberlist';
    /**
     * function to manage data before assigning to the template of its zone
     */
    protected function _prepareTpl(){
        $page           = (int) $this->param('page');
        $memberSearch   = (string) $this->param('memberSearch');
        // get letter  in lowercase
        $letter = $this->param('letter');
        if ($letter < chr(97) or $letter > chr(123) ) $letter = '';

        $grpid = -2;
        if ($this->param('grpid'))
            $grpid =  intval($this->param('grpid'));

        $nbMembersPerPage = (int) jApp::config()->havefnubb['members_per_page'];

        $p = jAcl2Db::getProfile();

        // $memberSearch == '' means, we dont search some members by their nickname
        if($grpid == -2) {
            //all users

            $dao = jDao::get('jacl2db~jacl2groupsofuser',$p);
            $cond = jDao::createConditions();
            $cond->addCondition('grouptype', '=', 2);
            $cond->addCondition('status', '=', 1);
            if (strlen($letter) == 1)  {
                $cond->addCondition('login', 'like', $letter . '%');
            }
            elseif ($memberSearch != '') {
                $cond->addCondition('login', 'like', '%'.$memberSearch . '%');
            }
            $rs = $dao->findBy($cond,$page,$nbMembersPerPage);
            $nbMembers = $dao->countBy($cond);

        } else {
            //in a specific group
            $dao = jDao::get('jacl2db~jacl2usergroup',$p);
            if ($letter == '')
                $rs = $dao->getPublicUsersGroupLimit($grpid, $page, $nbMembersPerPage);
            else
                $rs = $dao->getPublicUsersByLetterGroupLimit($grpid, $page, $nbMembersPerPage,$letter.'%');

            $nbMembers = $dao->getUsersGroupCount($grpid);
        }

        $members=array();
        $dao2 = jDao::get('jacl2db~jacl2groupsofuser',$p);
        foreach($rs as $u){
            $u->groups = array();
            $gl = $dao2->getGroupsUser($u->login);
            foreach($gl as $g) {

                if($g->grouptype != 2 and $g->status == 1)
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
        $letters[] = jLocale::get('havefnubb~member.memberlist.select.an.initial.nickname');
        for ($i = 0 ; $i < 26 ; $i ++) {
            $letters[] = chr(97 + $i);
        }
        $daoRank = jDao::get('havefnubb~ranks');
        $ranks = $daoRank->findAll();
        // let's build the pagelink var
        // A Preparing / Collecting datas
        // 0- the properties of the pager
        $properties = array('start-label' => '',
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
        $this->_tpl->assign('letters',$letters);
        $this->_tpl->assign('ranks',$ranks);
    }
}
