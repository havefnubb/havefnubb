<?php
/**
* @package   havefnubb
* @subpackage havefnubb
* @author    FoxMaSk
* @copyright 2008 FoxMaSk
* @link      http://havefnubb.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

class defaultCtrl extends jController {
    /**
    *
    */
    public $pluginParams = array(
        '*'		=> array('auth.required'=>false,
						 'banuser.check'=>true
						 ),
		'cloud'	=> array('hfnu.check.installed'=>true),
		'index' => array('hfnu.check.installed'=>true,
						 'history.add'=>true,
						 'history.label'=>'Accueil',
						 'history.title'=>'Aller vers la page d\'accueil')
    );
    /*
	 *
	 */

    function index() {
		global $HfnuConfig;
        $title = stripslashes($HfnuConfig->getValue('title','main'));
        $rep = $this->getResponse('html');
		
		$GLOBALS['gJCoord']->getPlugin('history')->change('label', ucfirst ( htmlentities($title,ENT_COMPAT,'UTF-8') ) );
		$GLOBALS['gJCoord']->getPlugin('history')->change('title', jLocale::get('havefnubb~main.goto_homepage'));
		
        $rep->body->assignZone('MAIN', 'havefnubb~category');
        return $rep;
    }
	
	function cloud () {
		$tag = $this->param('tag');
		
		global $HfnuConfig;
        $title = stripslashes($HfnuConfig->getValue('title','main'));
        $rep = $this->getResponse('html');
		
		$GLOBALS['gJCoord']->getPlugin('history')->change('label', ucfirst ( htmlentities($title,ENT_COMPAT,'UTF-8') ). ' - ' . jLocale::get('havefnubb~main.cloud'));
		$GLOBALS['gJCoord']->getPlugin('history')->change('title', jLocale::get('havefnubb~main.cloud'));
		
		$rep->title = jLocale::get('havefnubb~main.cloud.posts.by.tag',$tag);
        $rep->body->assignZone('MAIN', 'havefnubb~postlistbytag',array('tag'=>$tag));
        return $rep;		
	}
	
	function notinstalled() {
        $rep = $this->getResponse('html');
		$tpl = new jTpl();
        $rep->body->assign('MAIN', $tpl->fetch('havefnubb~notinstalled'));
        return $rep;		
	}
	
	function rules() {
		global $HfnuConfig;        
		$tpl = new jTpl();
		if ($HfnuConfig->getValue('rules','main') != '') {
			$rep = $this->getResponse('html');
			$tpl->assign('rules',$HfnuConfig->getValue('rules','main'));
	        $rep->body->assign('MAIN', $tpl->fetch('havefnubb~rules'));
		}
		else {
			$rep = $this->getResponse('redirect');
			$rep->action = 'default:index';
		}
        return $rep;		
	}
}
