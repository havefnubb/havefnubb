<?php
/**
 * @package   havefnubb
 * @subpackage havefnubb
 * @author    FoxMaSk
 * @contributor Laurent Jouanneau
 * @copyright 2008-2011 FoxMaSk, 2019 Laurent Jouanneau
 * @link      https://havefnubb.jelix.org
 * @license  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
 */
/**
 * main UI to manage subscriptions of member to posts in HaveFnuBB!
*/
class hfnusub {
    /**
     * @var string $daoSub dao of the subscription table
     */
    private $daoSub = 'havefnubb~sub';
    /**
     * Have I SubScribe to this post ?
     * @param integer $id of the subscribed post
     * @return jDaoRecordBase
     */
    public function getSubscribed($id) {
        if (jAuth::isConnected()) {
            return jDao::get($this->daoSub)->get($id, jAuth::getUserSession()->id);
        }
        return null;
    }
    /**
     * Subscribe to a thread
     * @param integer $id of the THREAD! to subscribe
     * @return boolean
     */
    public function subscribe($id) {
        $dao = jDao::get($this->daoSub);
        if (jAuth::isConnected()) {
            $id_user = jAuth::getUserSession ()->id;
            if (! $dao->get($id, $id_user)) {
                $record = jDao::createRecord($this->daoSub);
                $record->id_post = $id;// thread ID
                $record->id_user = $id_user;
                $dao->insert($record);
                return true;
            }
        }
        return false;
    }
    /**
     * Unsubscribe to a thread
     * @param integer $id of the THREAD! to unsubscribe
     * @return boolean
     */
    public function unsubscribe($id) {
        $dao = jDao::get($this->daoSub);
        if ( jAuth::isConnected() && $dao->get($id,jAuth::getUserSession ()->id)) {
            $dao->delete($id,jAuth::getUserSession ()->id);
            return true;
        }
        return false;
    }
    /**
     * Send an email to the members that have subsribe to this post
     * @param integer $id of the subscribed post
     * @return void
     */
    public function sendMail($id) {

        if (!jAuth::isConnected())
            return;

        $dao = jDao::get($this->daoSub);
        $memberDao = jDao::get('havefnubb~member');

        //get all the members that subscribe to this thread except "ME" !!!
        $records = $dao->findSubscribedPost($id,jAuth::getUserSession ()->id);

        $gJConfig = jApp::config();
        // then send them a mail
        foreach ($records as $record) {
            //get all the member that subscribe to the thread id $id (called by hfnupost -> savereply )
            $thread = jClasses::getService('havefnubb~hfnuposts')->getThread($id);
            $post = jClasses::getService('havefnubb~hfnuposts')->getPost($thread->id_last_msg);
            //get the email of the member that subscribes this thread
            $member = $memberDao->getById($record->id_user);

            $subject = jLocale::get('havefnubb~post.new.comment.received') . " : " .$post->subject ;

            $mail = new jMailer();
            $mail->From       = $gJConfig->mailer['webmasterEmail'];
            $mail->FromName   = $gJConfig->mailer['webmasterName'];
            $mail->Sender     = $gJConfig->mailer['webmasterEmail'];
            $mail->Subject    = $subject;

            $tpl = new jTpl();
            $tpl->assign('post',$post);
            $tpl->assign('login',$member->login);
            $mail->Body = $tpl->fetch('havefnubb~new_comment_received', 'text');
            $mail->AddAddress($member->email);
            $mail->Send();
        }
    }

}
