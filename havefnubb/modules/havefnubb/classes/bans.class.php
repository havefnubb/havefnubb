<?php
/**
* @package   havefnubb
* @subpackage havefnubb
* @author    FoxMaSk
* @copyright 2008 FoxMaSk
* @link      http://forge.jelix.org/projects/havefnubb
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/


class bans 
{

    function __construct() {
    }
    // @TODO
    public static function getBans() {
		
    }
	//@TODO  
	public static function checkExpiry() {
		
	}
	
    public static function checkIp($ip) {
        $validIp = false;
        $newIp = '';
        //0) checking the content : list or range but not list AND range :
        if (strpos($ip,',') > 0 and strpos('-',$ip) > 0 ) {
            jMessage::add(jLocale::get('hfnuadmin~ban.list.or.range'));
            return false;            
        }
        //1) list of IP with commas
		elseif (strpos($ip,',') > 0 ) {
            $list = split(',',$ip);
            foreach ($list as $item) {
                $validIp = jFilter::isIPv4($ip);
                if ($validIp === false) {
					jMessage::add(jLocale::get('hfnuadmin~ban.invalid.list.of.ip'));
                    return false;
                }
            }
        }
        //2) range of IP with -
        elseif (strpos($ip,'-')> 0 ) {
            $list = split('-',$ip);
            foreach ($list as $item) {
                $validIp = jFilter::isIPv4($ip);
                if ($validIp === false) {
                    jMessage::add(jLocale::get('hfnuadmin~ban.invalid.range.of.ip'));
                    return false;
                }
            }            
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
