<?php
/**
* @package   havefnubb
* @subpackage havefnubb
* @author    FoxMaSk
* @copyright 2008 FoxMaSk
* @link      http://forge.jelix.org/projects/havefnubb
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

class authhavefnubbListener extends jEventListener{

   /**
   *
   */
   function onAuthLogin ($event) {
        $login = $event->getParam('login');

        // update the last connection access
        $dao = jDao::get('havefnubb~member');
        
        $user = $dao->getByLogin($login);
        if (!$user) {
            die("");
        }
        // put the current date
        $user->member_last_connect = date("Y-m-d H:i:s");
        $dao->update($user);
   }
   
   function onjcommunity_save_account ($event) {
      jMessage::add(jLocale::get('havefnubb~member.profile.updated'),'ok');
   }
}
?>