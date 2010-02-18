<?php
/**
* @package   havefnubb
* @subpackage havefnubb
* @author    FoxMaSk
* @copyright 2008 FoxMaSk
* @link      http://havefnubb.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

class member_subscriptions_listZone extends jZone {
	protected $_tplname='zone.member.subscriptions.list';

	protected function _prepareTpl(){
        $subs = array();
        $posts = jDao::get('havefnubb~sub')->findSubscribedPostByUser(jAuth::getUserSession()->id);
        foreach ($posts as $post) {

            $forum = jClasses::getService('havefnubb~hfnuforum')->getForum(
                        jClasses::getService('havefnubb~hfnuposts')->getPost($post->id_post)->id_forum
                    );
            $subs[] = array(
                'id_post'   => $post->id_post,
                'ptitle'    => jClasses::getService('havefnubb~hfnuposts')->getPost($post->id_post)->subject,
                'parent_id' => jClasses::getService('havefnubb~hfnuposts')->getPost($post->id_post)->parent_id,
                'id_forum'  => $forum->id_forum,
                'ftitle'    => $forum->forum_name
                    );
        }
		$this->_tpl->assign('subs',$subs);
	}
}
