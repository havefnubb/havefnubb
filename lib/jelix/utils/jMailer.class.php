<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* jMailer : based on PHPMailer - PHP email class
* Class for sending email using either
* sendmail, PHP mail(), or SMTP.  Methods are
* based upon the standard AspEmail(tm) classes.
*
* @package     jelix
* @subpackage  utils
* @author      Laurent Jouanneau
* @contributor Kévin Lepeltier, GeekBay
* @copyright   2006-2009 Laurent Jouanneau
* @copyright   2008 Kévin Lepeltier, 2009 Geekbay
* @link        http://jelix.org
* @licence     GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
*/
require(LIB_PATH.'phpMailer/class.phpmailer.php');
class jMailer extends PHPMailer{
	public $bodyTpl = '';
	protected $lang;
	function __construct(){
		global $gJConfig;
		$this->lang = $gJConfig->locale;
		$this->CharSet = $gJConfig->charset;
		$this->Mailer = $gJConfig->mailer['mailerType'];
		$this->Hostname = $gJConfig->mailer['hostname'];
		$this->Sendmail = $gJConfig->mailer['sendmailPath'];
		$this->Host = $gJConfig->mailer['smtpHost'];
		$this->Port = $gJConfig->mailer['smtpPort'];
		$this->Helo = $gJConfig->mailer['smtpHelo'];
		$this->SMTPAuth = $gJConfig->mailer['smtpAuth'];
		$this->SMTPSecure = $gJConfig->mailer['smtpSecure'];
		$this->Username = $gJConfig->mailer['smtpUsername'];
		$this->Password = $gJConfig->mailer['smtpPassword'];
		$this->Timeout = $gJConfig->mailer['smtpTimeout'];
		if($gJConfig->mailer['webmasterEmail'] != ''){
			$this->From = $gJConfig->mailer['webmasterEmail'];
		}
		$this->FromName = $gJConfig->mailer['webmasterName'];
	}
	function getAddrName($address){
		preg_match('`^([^<]*)<([^>]*)>$`', $address, $tab);
		array_shift($tab);
		return $tab;
	}
	protected $tpl = null;
	function Tpl( $selector, $isHtml = false){
		$this->bodyTpl = $selector;
		$this->tpl = new jTpl();
		$this->IsHTML($isHtml);
		return $this->tpl;
	}
	function Send(){
		$header = "";
		$body = "";
		$result = true;
		if( isset($this->bodyTpl) && $this->bodyTpl != ""){
			if($this->tpl == null)
				$this->tpl = new jTpl();
			$mailtpl = $this->tpl;
			$metas = $mailtpl->meta( $this->bodyTpl ,($this->ContentType == 'text/html'?'html':'text'));
			if( isset($metas['Subject']))
				$this->Subject = $metas['Subject'];
			if( isset($metas['Priority']))
				$this->Priority = $metas['Priority'];
			$mailtpl->assign('Priority', $this->Priority);
			if( isset($metas['From'])){
				$adr = $this->getAddrName( $metas['From']);
				$this->From = $adr[1];
				$this->FromName = $adr[0];
			}
			$mailtpl->assign('From', $this->From);
			$mailtpl->assign('FromName', $this->FromName);
			if( isset($metas['Sender']))
				$this->Sender = $metas['Sender'];
			$mailtpl->assign('Sender', $this->Sender);
			if( isset($metas['to']))
				foreach( $metas['to'] as $val)
					$this->to[] = $this->getAddrName( $val);
			$mailtpl->assign('to', $this->to);
			if( isset($metas['cc']))
				foreach( $metas['cc'] as $val)
					$this->cc[] = $this->getAddrName( $val);
			$mailtpl->assign('cc', $this->cc);
			if( isset($metas['bcc']))
				foreach( $metas['bcc'] as $val)
					$this->bcc[] = $this->getAddrName( $val);
			$mailtpl->assign('bcc', $this->bcc);
			if( isset($metas['ReplyTo']))
				foreach( $metas['ReplyTo'] as $val)
					$this->ReplyTo[] = $this->getAddrName( $val);
			$mailtpl->assign('ReplyTo', $this->ReplyTo);
			$this->Body = $mailtpl->fetch( $this->bodyTpl,($this->ContentType == 'text/html'?'html':'text'));
		}
		return parent::Send();
	}
	function SetLanguage($lang_type = 'en_EN', $lang_path = 'language/'){
		$this->lang = $lang_type;
	}
	protected function SetError($msg){
		if(preg_match("/^([^#]*)#([^#]+)#(.*)$/", $msg, $m)){
			$arg = null;
			if($m[1] != '')
				$arg = $m[1];
			if($m[3] != '')
				$arg = $m[3];
			if(strpos($m[2], 'WARNING:') !== false){
				$locale = 'jelix~errors.mail.'.substr($m[2],8);
				if($arg !== null)
					parent::SetError(jLocale::get($locale, $arg, $this->lang, $this->CharSet));
				else
					parent::SetError(jLocale::get($locale, array(), $this->lang, $this->CharSet));
				return;
			}
			$locale = 'jelix~errors.mail.'.$m[2];
			if($arg !== null){
				throw new jException($locale, $arg, 1, $this->lang, $this->CharSet);
			}
			else
				throw new jException($locale, array(), 1, $this->lang, $this->CharSet);
		}
		else{
			throw new Exception($msg);
		}
	}
	protected function Lang($key){
		if($key == 'tls' || $key == 'authenticate')
			$key = 'WARNING:'.$key;
		return '#'.$key.'#';
	}
}