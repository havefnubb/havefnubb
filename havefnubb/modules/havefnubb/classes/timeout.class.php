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
* Class that handles the timeout of a connection of a visit
*/
class timeout {
    /**
     * check if the users are idle or no more connected.
     * the parameters come from the timeout.coord.ini.php file
     * and pass from the timeout Coordinator
     * @param string $daoConnected string of the DAO from where to check the connection
     * @param string $daoMember string of the DAO from where to check the member
     * @param integer $timeoutConnected integer of the duration of a connection
     * @param integer $timeoutVisit integer of the duration of a visit
     */
    public static function check($daoConnected,$daoMember,$timeoutConnected,$timeoutVisit) {
        $daoC = jDao::get($daoConnected);
        $now = time();
        // get the user that are still connected ( where the timeout_connected is not reach )
        $timeout = $now - $timeoutConnected;

        $connected = $daoC->findAllConnected($timeout);

        foreach ($connected as $online) {

            //check if the visit timeout is reach
            $timeout = $now - $timeoutVisit;

            //yes it is so we update the last visit
            if ($online->connected  < $timeout) {
                //get the member dao
                $daoM = jDao::get($daoMember);
                //get the member by its PK
                $user = $daoM->getById($online->id);
                //put the current date
                $user->member_last_connect = time();
                $user->connected = time();
                //update the Member
                $daoM->update($user);
                //delete the entry from connected entries as this user no more online
                $daoC->delete($online->id);
            }
            // the user is online but Idle
            elseif  ($online->idle == 0) {
                $online->idle=1;
                $daoC->update($online);
            }
        }

        if (jAuth::isConnected()) {
            $daoC = jDao::get($daoConnected);
            $rec = $daoC->get(jAuth::getUserSession()->id);
        // the user id not in registered as online
            if ( $rec === false) {
                $record = jDao::createRecord($daoConnected);
                $record->id = jAuth::getUserSession()->id;
                $record->connected = time();
                $record->member_ip = $_SERVER['REMOTE_ADDR'];
                $record->idle = 0;
                $daoC->insert($record);
            }
        }
    }
}
