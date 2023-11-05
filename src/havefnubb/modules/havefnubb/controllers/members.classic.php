<?php
/**
* @package   havefnubb
* @subpackage havefnubb
* @author    FoxMaSk
* @copyright 2008-2011 FoxMaSk
* @link      https://havefnubb.jelix.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/
/**
* Controller to manage any specific members events
*/
class membersCtrl extends jController {
    /**
     * @var $pluginParams plugins to manage the behavior of the controller
     */
    public $pluginParams = array (
        '*' => array('auth.required'=>true,
            'banuser.check'=>true,
        ),
        'index' => array('history.add'=>true,
            'history.label'=>'Accueil',
            'history.title'=>'Aller vers la page d\'accueil')
    );
    /**
    * handle the search of specific member
    */
    function index() {
        $title = stripslashes(jApp::config()->havefnubb['title']);
        $rep = $this->getResponse('html');

        $letter = $this->param('letter');
        $id_rank = (int) $this->param('id_rank');

        $memberSearch = (string) $this->param('member_search');

        $page = (int) $this->param('page');

        // get the group name of the group id we request
        $grpid = $this->param('grpid');

        $groupname = jLocale::get('havefnubb~member.memberlist.allgroups');

        if ($grpid && $grpid != '__anonymous' ) {
            $dao = jDao::get('jacl2db~jacl2group');
            $grpname = $dao->get($grpid);
            $groupname = $grpname->name;
        }

        $beginningBy = '';

        if (strlen($letter) == 1 ) {
            $beginningBy = ' - ' . jLocale::get('havefnubb~member.memberlist.members.beginning.by', array($letter));
        }
        // change the label of the breadcrumb
        if ($page == 0) {
            jApp::coord()->getPlugin('history')->change('label', jLocale::get('havefnubb~member.memberlist.members.list'));
            $rep->title = jLocale::get('havefnubb~member.memberlist.members.list') . ' - ' . $groupname . $beginningBy;
        }
        else {
            jApp::coord()->getPlugin('history')->change('label', jLocale::get('havefnubb~member.memberlist.members.list') . ' ' .($page+1));
            $rep->title = jLocale::get('havefnubb~member.memberlist.members.list') . ' - ' . $groupname .$beginningBy. ' ' .($page+1) ;
        }

        $rep->body->assignZone('MAIN',
            'memberlist',
            array('page'=>$page,
                 'grpid'=>$grpid,
                 'letter'=>$letter,
                 'memberSearch'=>$memberSearch)
        );
        return $rep;
    }

    /**
     * call of internal messaging
     */
    function mail() {
        $rep = $this->getResponse('html');
        $rep->body->assign('selectedMenuItem','members');
        $rep->body->assignZone('MAIN', 'jmessenger~links');
        return $rep;
    }
}
