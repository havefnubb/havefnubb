<?php
/**
* @package   havefnubb
* @subpackage havefnubb
* @author    FoxMaSk
* @copyright 2008 FoxMaSk
* @link      http://havefnubb.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

class postStuffListener extends jEventListener{

   /**
   *
   */
   function onHfnuPostAfterInsert ($event) {
	  $daoUser = jDao::get('havefnubb~member');			
	  $daoUser->updateNbMsg(jAuth::getUserSession ()->id);
	  $daoUser->updateLastPostedMsg(jAuth::getUserSession ()->id,time());
   }
   
   function onHfnuPostAfterUpdate ($event) {
	  $daoUser = jDao::get('havefnubb~member');
	  $daoUser->updateLastPostedMsg(jAuth::getUserSession ()->id,time());	  
   }
   
   function onHfnuPostAfterSaveReply ($event) {
	  $daoUser = jDao::get('havefnubb~member');			
	  $daoUser->updateNbMsg(jAuth::getUserSession ()->id);
	  $daoUser->updateLastPostedMsg(jAuth::getUserSession ()->id,time());
   }
   
}
