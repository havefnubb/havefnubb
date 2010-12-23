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
 * Class the displays the list of subscription on the profile page
 */
class member_subscriptions_listZone extends jZone {
    /**
     *@var string $_tplname the template name used by the zone
     */
    protected $_tplname='zone.member.subscriptions.list';
    /**
     * function to manage data before assigning to the template of its zone
     */
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
                'thread_id' => jClasses::getService('havefnubb~hfnuposts')->getPost($post->id_post)->thread_id,
                'id_forum'  => $forum->id_forum,
                'ftitle'    => $forum->forum_name
                    );
        }
        $this->_tpl->assign('subs',$subs);
    }
}
