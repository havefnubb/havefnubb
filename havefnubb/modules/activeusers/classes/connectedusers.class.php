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
class connectedusers {
    
    public function connectUser($login, $name='') {
        $dao = jDao::get('activeusers~connectedusers');
		if ($name =='')
			$name = $login;

        $record = $dao->getByLoginSession($login, session_id());
        if ($record) {
            $record->login = $login; // perhaps the record exist, but with an anonymous user
			$record->name = $name;
            $record->member_ip = $GLOBALS['gJCoord']->request->getIP();
            $record->connection_date = $record->last_request_date = time();
            $dao->update($record);
        }
        else {
            $record = jDao::createRecord('activeusers~connectedusers');
            $record->sessionid = session_id();
            $record->login = $login;
			$record->name = $name;
            $record->member_ip = $GLOBALS['gJCoord']->request->getIP();
            $record->connection_date = $record->last_request_date = time();
            $dao->insert($record);
        }
        // this is an opportunity to delete disconnected users
        $this->deleteDisconnectedUsers();
    }
    
    public function disconnectUser($login) {
        jDao::get('activeusers~connectedusers')->disconnectUser($login);
        // this is an opportunity to delete disconnected users
        $this->deleteDisconnectedUsers();
    }
    
    protected function deleteDisconnectedUsers() {
		$timeoutVisit = $this->getVisitTimeout();
		if ($timeoutVisit) {
            jDao::get('activeusers~connectedusers')->clear($timeoutVisit);
        }
    }
    
	public function getVisitTimeout() {
        global $gJCoord;
		$plugin = $gJCoord->getPlugin('activeusers');
        if ($plugin) {
    		$timeoutVisit       = ($plugin->config['timeout_visit'] > 0 ) ? $plugin->config['timeout_visit'] : 1200;
            $timeoutVisit = time() - $timeoutVisit;
			return $timeoutVisit;
		}
		else return 0;
	}
    
    /**
     * save the date of the visit for the current user
     */
    public function check() {
        $dao = jDao::get('activeusers~connectedusers');
        if (jAuth::isConnected()) {
            $login = jAuth::getUserSession()->login;
            if ($login == '')
                $login = null;
        }
        else
            $login = null;

        $record = $dao->get(session_id());
        if ($record) {
            $record->login = $login;
            $record->last_request_date = time();
            $dao->update($record);
        }
        else {
            $record = jDao::createRecord('activeusers~connectedusers');
            $record->sessionid = session_id();
            $record->login = $login;
            $record->member_ip = $GLOBALS['gJCoord']->request->getIP();
            $record->connection_date = $record->last_request_date = time();
            $dao->insert($record);
        }
    }
}
