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
 * Class that handle the banned users
 */
class bans {
	/**
	 * get the Bans
	 * @return recordSet
	 */
	public static function getBans() {
		self::checkExpiry();
		$dao = jDao::get('havefnubb~bans');
		$bans = $dao->findAll();
		return $bans;
	}

	/**
	 * get the Banned Domain
	 * @return recordSet
	 */
	public static function getBannedDomains() {
		self::checkExpiry();
		$dao = jDao::get('havefnubb~bans');
		$bans = $dao->findAllDomains();
		return $bans;
	}

	/**
	 * remove bans that are expired
	 */
	public static function checkExpiry() {
		$dao = jDao::get('havefnubb~bans');
		$dao->deleteExpiry(time());
	}

	/**
	 * does this user banned ?
	 * @return boolean
	 */
	public static function check() {
		$return = false;
		$bans = self::getBans();
		foreach ($bans as $ban) {
			if ($ban->ban_username != '') {
				$return = self::bannedUserName($ban->ban_username);
				if ($return === true)  return true;
			}
			if ($ban->ban_email != '') {
				$return = self::bannedDomain($ban->ban_email);
				if ($return === true)  return true;
			}
			if ($ban->ban_ip) {
				return self::bannedIp($ban->ban_ip);
			}
		}
		return $return;
	}

	/**
	 * does this user banned ?
	 * @param string $email the email
	 * @return mixed : true/false or message of ban
	 */
	public static function checkDomain($email) {
		$return = false;
		$bans = self::getBannedDomains();
		foreach ($bans as $ban) {
			if (strpos($ban->ban_email,'@') > 0 )
				list($bannedAddress,$bannedDomain) = preg_split('/@/',$ban->ban_email);
			else
				$bannedDomain = $ban->ban_email;

			list($userAddress,$userDomain) = preg_split('/@/',$email);

			if ( $bannedDomain == $userDomain ) {
				return $ban->ban_message;
			}
		}
		return $return;
	}

	/**
	 * check if this member name is banned
	 * @param string $userName name of the member
	 * @return boolean
	 */
	public static function bannedUserName($userName) {
		return ($userName == jAuth::getUserSession()->login);
	}

	/**
	 * check if this email domain is banned
	 * @param string $email email domain of the member
	 * @return boolean
	 */
	public static function bannedDomain($email) {
		if (! jAuth::isConnected() ) return false;
		if (strpos($email,'@') == 0 ) {
			list($unused,$userEmail) = preg_split('/@/',jAuth::getUserSession()->email);
		}
		else
			$userEmail = jAuth::getUserSession()->email;
		return ($userEmail == $email);
	}

	/**
	 * check if this IP is banned
	 * @param string $banIp IP of the member
	 * @return boolean
	 */
	public static function bannedIp($banIp) {
	//is this IP one of them ?
		if (strpos($banIp,',') > 0 ) {
			$list = preg_split('/,/',$banIp);
			foreach ($list as $item) {
				if  ($item == $_SERVER['REMOTE_ADDR']) return true;
			}
		}
		// is this IP in this range ?
		elseif (strpos($banIp,'-')> 0 ) {
			// ip is xxx.yyy.zzz-aaa
			$list = preg_split('/-/',$banIp);
			// find xxx.yyy.
			$pos = strrpos($list[0],'.');
			// start is xxx.yyy.zzz
			$start = $list[0];
			// end is xxx.yyy.aaa
			$end = substr($list[0],0,$pos) . '.'.$list[1];
			// validate each of them
			if ($start >=  $_SERVER['REMOTE_ADDR'] and $_SERVER['REMOTE_ADDR'] <= $end )
				return true;
		}
		// is this IP the same ?
		else {
			return ($banIp == $_SERVER['REMOTE_ADDR']);
		}
		//otherwise no ban by ip!
		return false;
	}

	/**
	 * check the validity of an IP address
	 * @param string $ip IP of the member
	 * @return boolean
	 */
	public static function checkIp($ip) {
		$validIp = false;
		$newIp = '';
		//0) checking the content : list or range but not list AND range :
		if (strpos($ip,',') > 0 and strpos($ip,'-') > 0 ) {
			jMessage::add(jLocale::get('havefnubb~ban.list.ip.or.range'));
			return false;
		}
		//1) list of IP with commas
		elseif (strpos($ip,',') > 0 ) {
			$list = preg_split('/,/',$ip);
			foreach ($list as $item) {
				$validIp = jFilter::isIPv4($item);
				if ($validIp === false) {
					jMessage::add(jLocale::get('havefnubb~ban.invalid.list.of.ip'));
					return false;
				}
			}
		}
		//2) range of IP with -
		elseif (strpos($ip,'-')> 0 ) {
		// ip is xxx.yyy.zzz-aaa
			$list = preg_split('/-/',$ip);
			// find xxx.yyy.
			$pos = strrpos($list[0],'.');
			// start is xxx.yyy.zzz
			$start = $list[0];
			// end is xxx.yyy.aaa
			$end = substr($list[0],0,$pos) . '.'.$list[1];
			// validate each of them

			$validIp1 = jFilter::isIPv4($start);
			$validIp2 = jFilter::isIPv4($end);
			if ($validIp1 === false or $validIp2 === false) {
				jMessage::add(jLocale::get('havefnubb~ban.invalid.range.of.ip', array($start,$end)));
				return false;
			}
			else return true;
		}
		else {
			$validIp = jFilter::isIPv4($ip);
			if ($validIp === false) {
				jMessage::add(jLocale::get('havefnubb~ban.invalid.ip'));
				return false;
			}
		}

		return $validIp;
	}

}
