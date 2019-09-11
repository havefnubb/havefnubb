<?php
/**
* @package   havefnubb
* @subpackage havefnubb
* @author    FoxMaSk
* @copyright 2008-2011 FoxMaSk
* @link      https://havefnubb.jelix.org
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
        // get the threads the user subscribed
        $threads = jDao::get('havefnubb~sub')->findSubscribedPostByUser(jAuth::getUserSession()->id);
        foreach ($threads as $t) {
            // get the thread details
            $thread = jClasses::getService('havefnubb~hfnuposts')->getThread($t->id_post);

            $subs[] = array(
                'id_post'   => $thread->id_last_msg,
                'ptitle'    => jClasses::getService('havefnubb~hfnuposts')->getPost($thread->id_last_msg)->subject,
                'thread_id' => $thread->id_thread,
                'id_forum'  => $thread->id_forum_thread,
                'ftitle'    => jClasses::getService('havefnubb~hfnuforum')->getForum($thread->id_forum_thread)->forum_name
                    );
        }
        $this->_tpl->assign('subs',$subs);
    }
}
