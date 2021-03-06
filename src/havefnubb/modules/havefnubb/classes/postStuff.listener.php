<?php
/**
* @package   havefnubb
* @subpackage havefnubb
* @author    FoxMaSk
* @copyright 2008-2011 FoxMaSk
* @link      https://havefnubb.jelix.org
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
        //let's check if a member has subcribed to this forum, then mail him the new thread
        jClasses::getService('havefnubb~hfnuforum')
                ->checkSubscribedForumAndSendMail(  $event->getParam('id_forum'),
                                                    $event->getParam('id')
                                            );
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
