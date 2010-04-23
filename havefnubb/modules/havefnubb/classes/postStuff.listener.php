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
		 $daoUser = jDao::get('havefnubb~member');
         $id_user = jAuth::getUserSession ()->id;
		 $daoUser->updateNbMsg($id_user);
		 $daoUser->updateLastPostedMsg($id_user,time());
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
		 $daoUser = jDao::get('havefnubb~member');
         $id_user = jAuth::getUserSession ()->id;
		 $daoUser->updateNbMsg($id_user);
		 $daoUser->updateLastPostedMsg($id_user,time());
	 }
}
