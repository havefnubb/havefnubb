<?php
/**
* @package   havefnubb
* @subpackage havefnubb
* @author    FoxMaSk
* @copyright 2008 FoxMaSk
* @link      http://havefnubb.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/


class bans 
{

    function __construct() {
    }
    
	//get the Bans
    public static function getBans() {
		self::checkExpiry();		
		$dao = jDao::get('havefnubb~bans');
		$bans = $dao->findAll();
		return $bans;
    }

	//get the Banned Domain
    public static function getBannedDomains() {
		self::checkExpiry();		
		$dao = jDao::get('havefnubb~bans');
		$bans = $dao->findAllDomains();
		return $bans;
    }
	
	//remove bans that are expired
	public static function checkExpiry() {
		$dao = jDao::get('havefnubb~bans');
		$dao->deleteExpiry(time());
	}
	
	// does this user banned ?
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

	// does this user banned ?
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
	
	// does this user banned ?
	public static function bannedUserName($userName) {
		return ($userName == jAuth::getUserSession()->login);
	}
	
	// does this email banned ?
	public static function bannedDomain($email) {
		if (! jAuth::isConnected() ) return false;
		if (strpos($email,'@') == 0 ) {
			list($unused,$userEmail) = preg_split('/@/',jAuth::getUserSession()->email);
		}
		else
			$userEmail = jAuth::getUserSession()->email;
		return ($userEmail == $email);
	}
	
	// does this IP banned ?
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
	
    public static function checkIp($ip) {
        $validIp = false;
        $newIp = '';
        //0) checking the content : list or range but not list AND range :
        if (strpos($ip,',') > 0 and strpos($ip,'-') > 0 ) {
            jMessage::add(jLocale::get('hfnuadmin~ban.list.or.range'));
            return false;            
        }
        //1) list of IP with commas
		elseif (strpos($ip,',') > 0 ) {
            $list = preg_split('/,/',$ip);
            foreach ($list as $item) {
                $validIp = jFilter::isIPv4($item);
                if ($validIp === false) {
					jMessage::add(jLocale::get('hfnuadmin~ban.invalid.list.of.ip'));
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
                    jMessage::add(jLocale::get('hfnuadmin~ban.invalid.range.of.ip'). ' end '.$end.' start '.$start);
                    return false;
            }
			else return true;
        }
        else {
            $validIp = jFilter::isIPv4($ip);
            if ($validIp === false) {
				jMessage::add(jLocale::get('hfnuadmin~ban.invalid.ip'));
                return false;
            }            
        }
        
        return $validIp;
    }

 
}
