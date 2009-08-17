<?php
/**
* @package   havefnubb
* @subpackage hfnuadmin
* @author    FoxMaSk
* @copyright 2008 FoxMaSk
* @link      http://havefnubb.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

class hfnumanageuserListener extends jEventListener{
    
    // setting the default number of message to 0
    // this will avoid an error about rank !
    public function onAuthNewUser($event) {
        $id = $event->getParam('user')->id;      
        if (! $id or $id == 0) return;
        $request_date = date('Y-m-d H:i:s');
        $dao = jDao::get('havefnubb~member');
        $dao->updateNbMsgAfterCreatingAccount($id,$request_date);
    }
}
