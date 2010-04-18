<?php
/**
* @package   havefnubb
* @subpackage havefnubb
* @author    FoxMaSk
* @copyright 2008 FoxMaSk
* @link      http://havefnubb.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/
/**
* Controller to manage any default events
*/
class defaultCtrl extends jController {
	/**
	 * @var plugins to manage the behavior of the controller
	 */
	public $pluginParams = array(
		'*'	=> array('auth.required'=>false,
						'banuser.check'=>true
						),
		'cloud'	=> array('hfnu.check.installed'=>true,
						'history.add'=>true,
						'history.label'=>'Accueil',
						'history.title'=>'Aller vers la page d\'accueil'
						 ),
		'index' => array('hfnu.check.installed'=>true,
						'history.add'=>true,
						'history.label'=>'Accueil',
						'history.title'=>'Aller vers la page d\'accueil')
	);
	/**
	 * Main page
	 */
	function index() {
		global $gJConfig;
		$title = stripslashes($gJConfig->havefnubb['title']);
		$rep = $this->getResponse('html');

		$GLOBALS['gJCoord']->getPlugin('history')->change('label', ucfirst ( htmlentities($title,ENT_COMPAT,'UTF-8') ) );
		$GLOBALS['gJCoord']->getPlugin('history')->change('title', jLocale::get('havefnubb~main.goto_homepage'));

		$dao = jDao::get('havefnubb~forum_cat');
		$categories = $dao->findAllCatWithFathers();
		$nbCat = $categories->rowCount();
		$data = array();

		foreach ($categories as $cat) {
			if ( jAcl2::check('hfnu.forum.list','forum'.$cat->id_forum) ) {
				$data[] = $cat;
				// get the list of forum to build the RSS link
				$forums = jClasses::getService('havefnubb~hfnuforum')->findParentByCatId($cat->id_cat);
				foreach ($forums as $forum) {
					$url = jUrl::get('havefnubb~posts:rss', array('ftitle'=>$forum->forum_name,
															'id_forum'=>$forum->id_forum));
					$rep->addHeadContent('<link rel="alternate" type="application/rss+xml" title="'.$forum->forum_name.'" href="'.htmlentities($url).'" />');
				}
			}
		}
		$rep->body->assignZone('MAIN', 'havefnubb~category',array('data'=>$data,'nbCat'=>$nbCat));
		return $rep;
	}
	/**
	* Display cloud of message from a given tag
	*/
	function cloud () {
		$tag = $this->param('tag');

		global $gJConfig;
		$title = stripslashes($gJConfig->havefnubb['title']);
		$rep = $this->getResponse('html');

		$GLOBALS['gJCoord']->getPlugin('history')->change('label', ucfirst ( htmlentities($title,ENT_COMPAT,'UTF-8') ). ' - ' . jLocale::get('havefnubb~main.cloud'));
		$GLOBALS['gJCoord']->getPlugin('history')->change('title', jLocale::get('havefnubb~main.cloud'));

		$rep->title = jLocale::get('havefnubb~main.cloud.posts.by.tag',$tag);
		$rep->body->assignZone('MAIN', 'havefnubb~postlistbytag',array('tag'=>$tag));
		return $rep;
	}
	/**
	* The forum is not installed
	*/
	function notinstalled() {
		$rep = $this->getResponse('html');
		$tpl = new jTpl();
		$rep->body->assign('MAIN', $tpl->fetch('havefnubb~notinstalled'));
		return $rep;
	}
	/**
	* The rules of the forum
	*/
	function rules() {
		global $gJConfig;
		$tpl = new jTpl();
		if ($gJConfig->havefnubb['rules'] != '') {
			$rep = $this->getResponse('html');
			$tpl->assign('rules',$gJConfig->havefnubb['rules']);
			$rep->body->assign('MAIN', $tpl->fetch('havefnubb~rules'));
		}
		else {
			$rep = $this->getResponse('redirect');
			$rep->action = 'default:index';
		}
		return $rep;
	}
}
