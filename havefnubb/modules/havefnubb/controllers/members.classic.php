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
        $title = $HfnuConfig->getValue('title','board');
        $rep = $this->getResponse('html');

		$page = 0;
		
		$page = (int) $this->param('page');
		
		// change the label of the breadcrumb
        if ($page == 0) {		
			$GLOBALS['gJCoord']->getPlugin('history')->change('label', htmlentities($title) . ' - ' . jLocale::get('havefnubb~member.memberlist.members.list')) ;
			$rep->title .= ' - ' . jLocale::get('havefnubb~member.memberlist.members.list');
		}
		else {
			$rep->title .= ' - ' . jLocale::get('havefnubb~member.members.list') . ' ' .($page+1) ;			
			$GLOBALS['gJCoord']->getPlugin('history')->change('label', htmlentities($title) . ' - ' . jLocale::get('havefnubb~member.memberlist.members.list') . ' ' .($page+1));		
		}
		
        $rep->body->assignZone('MAIN', 'memberlist',array('page'=>$page));
        return $rep;
    }    
    
}

