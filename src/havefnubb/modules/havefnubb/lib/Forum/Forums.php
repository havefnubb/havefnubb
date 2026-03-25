<?php
/**
 * @package   havefnubb
 * @subpackage havefnubb
 * @author    FoxMaSk
 * @contributor Laurent Jouanneau
 * @copyright 2008-2011 FoxMaSk, 2010-2019 Laurent Jouanneau
 * @link      https://havefnubb.jelix.org
 * @license  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
 */
namespace Havefnubb\Havefnubb\Forum;

/**
 * main API to manage the statement of the forums of HaveFnuBB!
 *
 * Don't call this class directly, use the Services class instead
 */
class Forums {

    /**
     * content of the forum
     * @var $forums array
     */
    protected $forums = array() ;

    /**
     * get info of a forum
     * @param  integer $id of the forum
     * @return object the forum datas
     */
    public function getForum($id)
    {
        if (!isset($this->forums[$id])) {
            $this->forums[$id] = \jDao::get('havefnubb~forum')->get($id);
        }
        return $this->forums[$id];
    }

    /**
     *  retrieve the list of forums
     *  @return ForumList
     */
    public function getFullList()
    {
        $c = \jDb::getConnection();

        $select="SELECT c.id_cat, cat_name, f.id_forum, forum_name, forum_desc, f.parent_id, f.nb_msg,f.nb_thread,
                child_level, forum_type, forum_url, post_expire, p.date_created, p.date_modified, p.thread_id,
                p.id_post, p2.subject as thread_subject, u.nickname, u.login, u.id as user_id, t.status";
        $from= " FROM ".$c->prefixTable('hfnu_forum_category')." as c,
                      ".$c->prefixTable('hfnu_forum')." as f
                      LEFT JOIN ".$c->prefixTable('hfnu_posts')." as p ON (f.id_last_msg = p.id_post)
                      LEFT JOIN ".$c->prefixTable('community_users')." as u ON (p.id_user = u.id)
                      LEFT JOIN ".$c->prefixTable('hfnu_threads')." as t ON (p.thread_id = t.id_thread)
                      LEFT JOIN ".$c->prefixTable('hfnu_posts')." as p2 ON (t.id_last_msg = p2.id_post)
                      ";
        $where = " WHERE c.id_cat = f.id_cat";

        $order = " ORDER BY c.cat_order asc, c.id_cat asc, f.parent_id, f.child_level asc, f.forum_order asc";

        $result = new ForumList();

        $rs = $c->query($select.$from.$where.$order);

        foreach($rs as $f) {
            $result->addForum($f);
        }
        return $result;
    }
    /**
     * subscribe to one forum
     * @param int $id_forum id of the forum to subscribe
     */
    public function subscribe($id_forum)
    {
        if (\jAuth::isConnected()) {
            //check if this forum is already subscribed
            if (! \jDao::get('havefnubb~forum_sub')->get(\jAuth::getUserSession()->id,$id_forum)) {
                $dao = \jDao::get('havefnubb~forum_sub');
                $rec = \jDao::createRecord('havefnubb~forum_sub');
                $rec->id_forum = $id_forum;
                $rec->id_user = \jAuth::getUserSession()->id;
                $dao->insert($rec);
            }
        }
    }
    /**
     * unsubscribe to one forum
     * @param int $id_forum id of the forum to unsubscribe
     */
    public function unsubscribe($id_forum)
    {
        if (\jAuth::isConnected()) {
            //check if this forum is already subscribe
            $dao = \jDao::get('havefnubb~forum_sub');
            $userId = \jAuth::getUserSession()->id;
            if ($dao->get($userId, $id_forum)) {
                $dao->delete($userId, $id_forum);
            }
        }
    }
    /**
     * let's check if a member has subcribed to this forum, then mail him the new thread
     * @param int $id_forum id of the forum to unsubscribe
     * @param int $id_post id of the new post
     */
    public function checkSubscribedForumAndSendMail($id_forum,$thread_id)
    {
        $gJConfig = \jApp::config();
        
        //check if this forum is already subscribe
        $recs = \jDao::get('havefnubb~forum_sub')->getByIdForum($id_forum);
        foreach ($recs as $rec) {
            if (\jAuth::getUserSession()->id != $rec->id_user) {
                $thread = \jDao::get('havefnubb~threads_alone')->get($thread_id);
                $post = \jDao::get('havefnubb~posts')->get($thread->id_last_msg);
                // let's mail the new post to the user
                $mail = new \jMailer();
                $mail->From       = $gJConfig->mailer['webmasterEmail'];
                $mail->FromName   = $gJConfig->mailer['webmasterName'];
                $mail->Sender     = $gJConfig->mailer['webmasterEmail'];
                $mail->Subject    = \jLocale::get('havefnubb~forum.new.post.in.forum');

                $tpl = new \jTpl();
                $tpl->assign('post',$post);
                $mail->Body = $tpl->fetch('havefnubb~forum_new_message', 'text');
                $mail->AddAddress(\jDao::get('havefnubb~member')->getById($rec->id_user)->email);
                $mail->Send();
            }
        }
    }
}
