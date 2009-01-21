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
        $page = $this->param('page');
        
        $nbMembersPerPage = $HfnuConfig->getValue('members_per_page','board');

        $dao = jDao::get('havefnubb~member');
        $members = $dao->findAllActivatedMember($page,$nbMembersPerPage);        
        $nbMembers = $dao->countAllActivatedMember();

        // let's build the pagelink var
        // A Preparing / Collecting datas
        // 0- the properties of the pager
        $properties = array('start-label' => ' ',
                      'prev-label'  => '',
                      'next-label'  => '',
                      'end-label'   => jLocale::get("havefnubb~member.pagelinks.end"),
                      'area-size'   => 5);

        // 1- vars for pagelinks
        $this->_tpl->assign('page',$page);                
        $this->_tpl->assign('nbMembersPerPage',$nbMembersPerPage);
        $this->_tpl->assign('properties',$properties);
        $this->_tpl->assign('members',$members);
        $this->_tpl->assign('nbMembers',$nbMembers);
    }
}