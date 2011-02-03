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
* Member Statistic datas handling
*/
class postStuffListener extends jEventListener{
    /**
     * Event to handle statistics data of the current member after inserting data
     * @pararm event $event Object of a listener
     */
    function onHfnuPostAfterInsert ($event) {
        $this->updateMember();
    }
    /**
     * Event to handle statistics data of the current member after updating data
     * @pararm event $event Object of a listener
     */
    function onHfnuPostAfterUpdate ($event) {
        $daoUser = jDao::get('havefnubb~member');
        $daoUser->updateLastPostedMsg(jAuth::getUserSession ()->id,time());
    }
    /**
     * Event to handle statistics data of the current member after replying
     * @pararm event $event Object of a listener
     */
    function onHfnuPostAfterSaveReply ($event) {
        $this->updateMember();
    }
    /**
     * Event to handle statistics data of the current member before removing data
     * @pararm event $event Object of a listener
     */
    function onHfnuPostBeforeDelete ($event) {
        //does this id user exists ?
        if (jDao::get('havefnubb~member')->getById($event->getParam('id_user')))
            //remove one post
            jDao::get('havefnubb~member')->removeOneMsg($event->getParam('id_user'));
    }
    /**
     * Function that updates member's datas
     */
    private function updateMember() {
        $daoUser = jDao::get('havefnubb~member');
        if (jAuth::isConnected()) {
            $id_user = jAuth::getUserSession ()->id;
            $daoUser->updateNbMsg($id_user);
        } else {
            $id_user = 0;
        }
        $daoUser->updateLastPostedMsg($id_user,time());
    }
}
