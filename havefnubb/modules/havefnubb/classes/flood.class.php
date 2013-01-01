<?php
/**
 * @package   havefnubb
 * @subpackage havefnubb
 * @author    FoxMaSk
 * @contributor Laurent Jouanneau
 * @copyright 2008-2011 FoxMaSk, 2010 Laurent Jouanneau
 * @link      http://havefnubb.org
 * @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
 */
/**
 * Class that handle the flood protection
 */
class flood {

    private function __construct() {}
    /**
     * check if there is a flood
     * @param integer $timeInterval time between two actions
     * @param integer $onlySameIp  true: the flood is checked only between same ip
     * @return boolean  true if flood is detected
     */
    public static function check($timeInterval, $onlySameIp) {

        // since we don't store data of anonymous user, and anonymous user
        // are not allowed to post, we don't check
        if (!jAuth::isConnected())
            return false;

        // check if the user is member of Admins (groupid 0) / Moderators (groupid 3)
        // if so, no need to stop the action of this group of users
        // FIXME we should check, not the group, but the rights !
        foreach(jAcl2DbUserGroup::getGroupList() as $grp)
            if ( $grp->id_aclgrp == 'admins' or $grp->id_aclgrp == 'moderators')
                return false;

        $dao = jDao::get('havefnubb~posts');
        $rec = $dao->getMyLastEditedPost( jAuth::getUserSession()->id );
        if ($rec->member_last_post + $timeInterval > time())
            return false;

        if ($onlySameIp && isset($_SERVER['REMOTE_ADDR']) && $rec->poster_ip != $_SERVER['REMOTE_ADDR']) {
            return false;
        }

        return true;
    }
}
