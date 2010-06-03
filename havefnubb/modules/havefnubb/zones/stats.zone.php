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
 * Class the displays the stats of the forum
 */
class statsZone extends jZone {
    /**
     *@var string $_tplname the template name used by the zone
     */
    protected $_tplname='zone.stats';
    /**
     * function to manage data before assigning to the template of its zone
     */
    protected function _prepareTpl(){
        global $gJCoord;
        $daoThreads = jDao::get('havefnubb~threads_alone');
        //posts and thread
        $msgs = $daoThreads->countAllPosts();
        $threads = $daoThreads->countAllThreads();
        //last posts
        $lastPost   = jDao::get('havefnubb~posts')->getLastPost();
        // if lastPost is "false" the forum is empty !
        if ( $lastPost === false ) {
            $forum = new StdClass;
            $forum->forum_name = '';
            $lastPost = new StdClass;
            $lastPost->parent_id = 0;
            $lastPost->subject = '';
            $lastPost->id_forum = 0;
            $lastPost->date_created = 0;
            $lastPost->date_last_post = 0;
            $lastPost->id_first_msg = 0;
            $lastPost->id_last_msg = 0;
        }
        else {
            $thread = $daoThreads->get($lastPost->parent_id);
            $dao = jDao::get('havefnubb~forum');
            $forum = $dao->get($lastPost->id_forum);
            $lastPost->id_first_msg = $thread->id_first_msg;
            $lastPost->id_last_msg = $thread->id_last_msg;
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

        $this->_tpl->assign('posts',$msgs->nb_replies + $msgs->total_replies);
        $this->_tpl->assign('threads',$threads);
        $this->_tpl->assign('lastPost',$lastPost);
        $this->_tpl->assign('forum',$forum);

        $this->_tpl->assign('members',$members);
        $this->_tpl->assign('lastMember',$lastMember);
    }
}
