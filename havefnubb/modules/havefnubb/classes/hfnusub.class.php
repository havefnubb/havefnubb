<?php
/**
 * @package   havefnubb
 * @subpackage havefnubb
 * @author    FoxMaSk
 * @copyright 2008 FoxMaSk
 * @link      http://havefnubb.org
 * @license  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
 */
/**
 * main UI to manage subscriptions of member to posts in HaveFnuBB!
*/
class hfnusub {
	/**
	 * @var string $daoSub dao of the subscription table
	 */
    private static $daoSub = 'havefnubb~sub';
	/**
	 * Have I SubScribe to this post ?
	 * @param integer $id of the subscribed post
	 * @return record
	 */
    public function getSubscribed($id) {
		if (jAuth::isConnected())
			return jDao::get(self::$daoSub)->get($id,jAuth::getUserSession ()->id);
    }
	/**
	 * Subscribe to a thread
	 * @param integer $id of the post to subscribe
	 * @return boolean
	 */
	public static function subscribe($id) {
		$dao = jDao::get(self::$daoSub);
        if (jAuth::isConnected()) {
            $id_user = jAuth::getUserSession ()->id;
            if (! $dao->get($id, $id_user)) {
                $record = jDao::createRecord(self::$daoSub);
                $record->id_post = $id;
                $record->id_user = $id_user;
                $dao->insert($record);
                return true;
            }
        }
        return false;
	}
	/**
	 * Unsubscribe to a thread
	 * @param integer $id of the post to unsubscribe
	 * @return boolean
	 */
	public static function unsubscribe($id) {
		$dao = jDao::get(self::$daoSub);
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
	public static function sendMail($id) {
        global $gJConfig;

        if (!jAuth::isConnected())
            return;

        $dao = jDao::get(self::$daoSub);
        $user = jAuth::getUserSession ();
        $member = jDao::get('havefnubb~member')->get($user->login);
        //get all the members that subscribe to this thread except "me"
        $records = $dao->findSubscribedPost($id,$user->id);

        // then send them a mail
        foreach ($records as $record) {

            $post = jClasses::getService('havefnubb~hfnuposts')->getPost($id);

            $subject = jLocale::get('havefnubb~post.new.comment.received') . " :" .$post->subject ;

			$mail = new jMailer();
			$mail->From       = $gJConfig->mailer['webmasterEmail'];
			$mail->FromName   = $gJConfig->mailer['webmasterName'];
			$mail->Sender     = $gJConfig->mailer['webmasterEmail'];
			$mail->Subject    = $subject;

			$tpl = new jTpl();
			$tpl->assign('server',$_SERVER['SERVER_NAME']);
			$tpl->assign('post',$post);
			$mail->Body = $tpl->fetch('havefnubb~new_comment_received', 'text');

			$mail->AddAddress($member->email); // FIXME email is not in $user ??
			$mail->Send();
        }
	}
}
