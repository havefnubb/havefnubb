<?php
/**
* @package   havefnubb
* @subpackage havefnubb
* @author    FoxMaSk
* @copyright 2008 FoxMaSk
* @link      http://havefnubb.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

class newestpostsZone extends jZone {
	protected $_tplname='zone.newestposts';

	protected function _prepareTpl(){
		$status = '';
		$source = $this->param('source');

		$dao = jDao::get('havefnubb~newest_posts');

		if ($source == 'forum') {
			$id_forum = (int) $this->param('id_forum');
			if ($id_forum < 1) return;
			$rec = $dao->getLastPostByIdForum($id_forum);

			if ( $rec === false)
				$status = 'forumicone';
			else
				$status = 'forumiconenew';
		}
		elseif ($source =='post') {

			//@TODO put the $availableStatus in the config file .
			$availableStatus = array('opened','closed','pined','pinedclosed','censored','hidden');
			if (! in_array($this->param('status'),$availableStatus)) return;

			$id_post    = (int) $this->param('id_post');
			$id_forum   = (int) $this->param('id_forum');

			if ($id_post < 1) return "";
			if ($id_forum < 1) return "";

			$rec = jClasses::getService('havefnubb~hfnuposts')->getPostStatus($id_post);

			if ( $rec === false ) {
				$status = $this->param('status');
				$dateDiff = 0;
			} else {
				if ($this->param('display') == 'icon' and $this->param('status') == 'opened' )
					$status = 'post-new';
				else
					$status = $this->param('status');

				$day_in_secondes = 24 * 60 * 60;
				$dateDiff =  ($rec->date_modified == 0) ? floor( (time() - $rec->date_created ) / $day_in_secondes) : floor( (time() - $rec->date_modified ) / $day_in_secondes) ;
			}
			$recForum = jClasses::getService('havefnubb~hfnuforum')->getForum($id_forum);

			if ( $recForum->post_expire > 0 and $dateDiff >= $recForum->post_expire )
				$status = 'closed';

		}
		if ($this->param('display') == 'text')
			$status = jLocale::get('havefnubb~post.status.'.$status);

		$this->_tpl->assign('post_status',$status);
	}
}
