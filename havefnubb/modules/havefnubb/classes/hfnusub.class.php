<?php
/**
* main UI to manage subscriptions of member to posts in HaveFnuBB!
*
* @package   havefnubb
* @subpackage hfnusub
* @author    FoxMaSk
* @copyright 2008 FoxMaSk
* @link      http://havefnubb.org
* @license  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*
*/
class hfnusub {

    private static $daoSub = 'havefnubb~sub';
    
	public static function subscribe($id) {
		$dao = jDao::get($this->daoSub);

		if (! $dao->get($id,jAuth::getUserSession ()->id)) {
			$record = jDao::createRecord('havefnubb~subscription');
			$record->id_post = $id;
			$record->id_user = jAuth::getUserSession ()->id;
			$dao->insert($record);
		}
	}

	public static function unsubscribe($id) {
		$dao = jDao::get($this->daoSub);
		if ( $dao->get($id,jAuth::getUserSession ()->id)) {
			$dao->delete($id,jAuth::getUserSession ()->id);
			return true;
		}
		return false;
	}
	
	public static function sendMail() {
        $dao = jDao::get($this->daoSub);
        //get all the members that subscribe to this thread except "me"
        $records = $dao->findSubscribedPost($id_post,jAuth::getUserSession ()->id);
        // then send them a mail
        $daoMember = jDao::get('havefnubb~member');
        foreach ($records as $record) {
            
            $post = jClasses::getService('havefnubb~hfnupost')->get($id_post);
            
            $member = $daoMember->get(jAuth::getUserSession ()->id);
            $subject = $post->subject . jLocale::get('havefnubb~post.new.comment.received');
            
			$mail = new jMailer();
			$mail->From       = $gJConfig->mailer['webmasterEmail'];
			$mail->FromName   = $gJConfig->mailer['webmasterName'];
			$mail->Sender     = $gJConfig->mailer['webmasterEmail'];
			$mail->Subject    = $subject;
	
			$tpl = new jTpl();
			$tpl->assign('server',$_SERVER['SERVER_NAME']);
			$tpl->assign('post',$post);
			$mail->Body = $tpl->fetch('havefnubb~new_comment_received', 'text');
	
			$mail->AddAddress($member->email);
			$mail->Send();
            
        }
	}
}