<?php
/**
* @package   havefnubb
* @subpackage havefnubb
* @author    FoxMaSk
* @copyright 2008 FoxMaSk
* @link      http://havefnubb.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

class statsZone extends jZone {
	protected $_tplname='zone.stats';

	protected function _prepareTpl(){
		global $gJCoord;
		$dao = jDao::get('havefnubb~posts');
		//posts and thread
		$posts      = $dao->countAllPosts();
		$threads    = $dao->countAllThreads();
		//last posts
		$lastPost   = $dao->getLastPost();

		// if lastPost is "false" the forum is empty !
		if ( $lastPost === false ) {
			$forum = new StdClass;
			$forum->forum_name = '';
			$lastPost = new StdClass;
			$lastPost->parent_id = '';
			$lastPost->subject = '';
			$lastPost->id_forum = '';
		}
		else {
			$dao = jDao::get('havefnubb~forum');
			$forum = $dao->get($lastPost->id_forum);
		}
		$dao = jDao::get('havefnubb~member');
		//members
		$members    = $dao->countAllActivatedMember();
		// last registered user that is validate
		$lastMember = $dao->findLastActiveMember();


		// display in the header ; the date of the last known posts
		$dt = new jDateTime();
		$dt->setFromString($lastPost->date_created, jDateTime::TIMESTAMP_FORMAT);
		$meta = '<meta name="dc.date" content="'.$dt->toString(jDateTime::ISO8601_FORMAT).'" />';
		$gJCoord->response->addHeadContent($meta);

		$this->_tpl->assign('posts',$posts);
		$this->_tpl->assign('threads',$threads);
		$this->_tpl->assign('lastPost',$lastPost);
		$this->_tpl->assign('forum',$forum);

		$this->_tpl->assign('members',$members);
		$this->_tpl->assign('lastMember',$lastMember);
	}
}
