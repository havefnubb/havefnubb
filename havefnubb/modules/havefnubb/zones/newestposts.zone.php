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
 * Class the displays gives a "css class" if a post is new or not
 */
class newestpostsZone extends jZone {
	/**
	 *@var string $_tplname the template name used by the zone
	 */
	protected $_tplname='zone.newestposts';
	/**
	 * function to manage data before assigning to the template of its zone
	 */
	protected function _prepareTpl(){
		global $gJConfig;
		$status = '';
		$source = $this->param('source');

		$dao = jDao::get('havefnubb~newest_posts');
		$post = '';
		$statusCss='';
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

			$post = jDao::get('havefnubb~posts')->get($id_post);

			$viewed = 0;
			if ( $rec === false ) {
				$status = $this->param('status');
				$dateDiff = 0;
			} else {
				if ($this->param('display') == 'icon' and $this->param('status') == 'opened' ) {
					$status = 'post-new';
				}
				else
					$status = $this->param('status');

				$dayInSecondes = 24 * 60 * 60;
				$dateDiff =  ($rec->date_modified == 0) ? floor( (time() - $rec->date_created ) / $dayInSecondes) : floor( (time() - $rec->date_modified ) / $dayInSecondes) ;
			}
			$viewed = jClasses::getService('havefnubb~hfnuposts')->getPost($id_post)->viewed;
			$recForum = jClasses::getService('havefnubb~hfnuforum')->getForum($id_forum);

			if ( $recForum->post_expire > 0 and $dateDiff >= $recForum->post_expire )
				$status = 'closed';

			//important one ?
			$dao = jDao::get('havefnubb~posts');
			$responseTtl = $dao->countResponse($id_post);
			$important = false;
			if ($responseTtl >= $gJConfig->havefnubb['important_nb_replies']) {
				$important = true;
			}
			if ($viewed >= $gJConfig->havefnubb['important_nb_views']) {
				$important = true;
			}
			$status = ($important === true) ? $status . '_important' : $status;
			$statusCss = $status;
		}
		if ($this->param('display') == 'text')
			$status = jLocale::get('havefnubb~post.status.'.$status);

		$this->_tpl->assign('statusCss',$statusCss);
		$this->_tpl->assign('postStatus',$status);
		$this->_tpl->assign('post',$post);
		$this->_tpl->assign('display',$this->param('display'));
	}
}
