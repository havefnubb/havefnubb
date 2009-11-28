<?php
/**
* Class that handle the flood protection
* 
* @package   havefnubb
* @subpackage havefnubb
* @author    FoxMaSk
* @copyright 2008 FoxMaSk
* @link      http://havefnubb.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

class flood {

    function __construct() {}
    /**
     * check if there is a flood
     * @param $action string same_ip or editing
     * @param $value integer time between two $action
     * @return boolean
     */
    public static function check($action,$value) {
        //we are never sure enough of the datas we get :P
        
        if (! in_array($action,array('same_ip','editing'))) {
            die("");
        }
        //check if the user is member of Admins (groupid 0) / Moderators (groupid 3)
        // if so, no need to stop the action of this group of users        
        foreach(jAcl2DbUserGroup::getGroupList() as $grp) 
            if ( $grp->id_aclgrp == 1 or $grp->id_aclgrp == 3)
                return true;
        return self::$action($value);
    }
    
    /**
     * check if there is a flood from the same ip
     * @param $value integer time between two 'same_ip'
     * @return boolean
     */    
    public static function same_ip($value) {
        $dao = jDao::get('havefnubb~posts');        
        $rec = $dao->getMyLastEditedPost(  jAuth::getUserSession()->id );
        if ($rec->member_last_post + $value < time() and $rec->poster_ip == $_SERVER['REMOTE_ADDR'] )
            return true;
        else
            return false;
    }
    
    /**
     * check if there is a flood when editing post
     * @param $value integer time between two 'editing'
     * @return boolean
     */    
    public static function editing($value) {
        $dao = jDao::get('havefnubb~posts');
        $rec = $dao->getMyLastEditedPost( jAuth::getUserSession()->id );
        if ($rec->member_last_post + $value < time() )        
            return true;
        else
            return false;
    }

}
