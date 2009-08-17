<?php
/**
* @package   havefnubb
* @subpackage havefnubb
* @author    FoxMaSk
* @copyright 2008 FoxMaSk
* @link      http://havefnubb.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

class forumCtrl extends jController {
    /**
    *
    */
    public $pluginParams = array(
        '*'		=> array('auth.required'=>false,
						 'banuser.check'=>true
						 ),
		'index' => array('hfnu.check.installed'=>true,
						 'history.add'=>true,
						 'history.label'=>'Accueil',
						 'history.title'=>'Aller vers la page d\'accueil')
    );
    /*
	 *
	 */
	public function read_rss() {
        $id_forum = (int) $this->param('id_forum');
		
		if ( ! jAcl2::check('hfnu.posts.list','forum'.$id_forum) ) {
			$rep = $this->getResponse('redirect');
            $rep->action = 'default:index';
			return $rep;
		}
		
        if ($id_forum == 0 ) {
            $rep = $this->getResponse('redirect');
            $rep->action = 'default:index';
			return $rep;
        }
		
        $GLOBALS['gJCoord']->getPlugin('history')->change('label', htmlentities($forum->forum_name,ENT_COMPAT,'UTF-8'));
		
		$dao = jDao::get('havefnubb~forum');
		$forum = $dao->get($id_forum);

		$feed_reader = new jFeedReader;
		$feed_reader->setCacheDir(JELIX_APP_VAR_PATH.'feeds');
		$feed_reader->setTimeout(2);
		$feed_reader->setUserAgent('HaveFnuBB - http://www.havefnubb.org/');
		$feed = $feed_reader->parse($forum->forum_url);
		
        $rep = $this->getResponse('html');	
        $tpl = new jTpl();				
        $tpl->assign('feed',$feed);
		$tpl->assign('forum',$forum);
        $rep->title = $forum->forum_name;
        $rep->body->assign('MAIN', $tpl->fetch('havefnubb~forum_rss.view'));
        return $rep;	
	}	

}
