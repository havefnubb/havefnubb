<?php
/**
* @package   havefnubb
* @subpackage havefnubb
* @author    FoxMaSk
* @copyright 2008 FoxMaSk
* @link      http://forge.jelix.org/projects/havefnubb
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

class membersCtrl extends jController {
    /**
    *
    */
    public $pluginParams = array(
        '*'		=> array('auth.required'=>true),
		'index' => array('history.add'=>true,
						 'history.label'=>'Accueil',
						 'history.title'=>'Aller vers la page d\'accueil')
    );
    
    function index() {
		global $HfnuConfig;
        $title = stripslashes($HfnuConfig->getValue('title'));
        $rep = $this->getResponse('html');

		$page = 0;		
		$page = (int) $this->param('page');
		
		// get the group name of the group id we request
		$grpid = (int) $this->param('grpid');
		$groupname = jLocale::get('havefnubb~member.memberlist.allgroups');
		if ($grpid > 0 ) {
			echo "$grpid";
			$dao = jDao::get('jelix~jacl2group');
			$conditions = jDao::createConditions();
			$conditions->addCondition('id_aclgrp','=',$grpid);
			$grpnames = $dao->findBy($conditions);
			foreach ($grpnames as $grpname)
				$groupname = $grpname->name;
		}
		
		
		// change the label of the breadcrumb
        if ($page == 0) {		
			$GLOBALS['gJCoord']->getPlugin('history')->change('label', htmlentities($title) . ' - ' . jLocale::get('havefnubb~member.memberlist.members.list')) ;
			$rep->title .= ' - ' . jLocale::get('havefnubb~member.memberlist.members.list') . ' - ' . $groupname;
		}
		else {
			$rep->title .= ' - ' . jLocale::get('havefnubb~member.members.list') . ' - ' . $groupname . ' ' .($page+1) ;
			$GLOBALS['gJCoord']->getPlugin('history')->change('label', htmlentities($title) . ' - ' . jLocale::get('havefnubb~member.memberlist.members.list') . ' ' .($page+1));		
		}
		
        $rep->body->assignZone('MAIN', 'memberlist',array('page'=>$page,'grpid'=>$grpid));
        return $rep;
    }
	    
}

