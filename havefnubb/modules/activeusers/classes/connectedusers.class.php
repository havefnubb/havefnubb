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
    
    public function connectUser($login) {
        $dao = jDao::get('activeusers~connectedusers');
        $name = $this->getName();

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
        static $timeoutVisit = null;
        
        if ($timeoutVisit !== null)
            return $timeoutVisit;

        global $gJCoord;
		$plugin = $gJCoord->getPlugin('activeusers', false);
        $timeoutVisit = 1200;
        if ($plugin) {
    		$timeoutVisit = ($plugin->config['timeout_visit'] > 0 ) ? $plugin->config['timeout_visit'] : 1200;
		}
        else {
            // for activeusers_admin
            global $gJConfig;
            if (isset($gJConfig->activeusers_admin['pluginconf'])
                && $gJConfig->activeusers_admin['pluginconf']) {
                $conffile = $gJConfig->activeusers_admin['pluginconf'];
                if (in_array(substr($conffile, 0,4), array('app:','lib:','var:'))) {
                    $conffile = str_replace(array('app:','lib:','var:'), array(JELIX_APP_PATH, LIB_PATH, JELIX_APP_VAR_PATH), $conffile);
                }
                else
                    $conffile = JELIX_APP_CONFIG_PATH.$conffile;
                $config = @parse_ini_file($conffile);
            }
        }
        if ($timeoutVisit)
            $timeoutVisit = time() - $timeoutVisit;
        return $timeoutVisit;
	}

	public function saveVisitTimeout($timeout) {
		global $gJConfig;
		if (isset($gJConfig->activeusers_admin['pluginconf'])
			&& $gJConfig->activeusers_admin['pluginconf']) {
			$conffile = $gJConfig->activeusers_admin['pluginconf'];
			if (in_array(substr($conffile, 0,4), array('app:','lib:','var:'))) {
				$conffile = str_replace(array('app:','lib:','var:'), array(JELIX_APP_PATH, LIB_PATH, JELIX_APP_VAR_PATH), $conffile);
			}
			else
				$conffile = JELIX_APP_CONFIG_PATH.$conffile;
			$ini = new jIniFileModifier($conffile);
			$ini->setValue('timeout_visit', $timeout);
			$ini->save();
			return true;
		}
		return false;

	}


	public function isConnected ($login) {
		$dao = jDao::get('activeusers~connectedusers');
		$timeoutVisit = $this->getVisitTimeout();

        $record = $dao->getIfConnected($login, $timeoutVisit);
        if ($record) {
			return true;
		}
		else return false;
	}

    /**
     * save the date of the visit for the current user
     */
    public function check() {
        $dao = jDao::get('activeusers~connectedusers');
        if (jAuth::isConnected()) {
            $user = jAuth::getUserSession();
            $login = $user->login;
            if ($login == '') {
                $login = null;
                $name = null;
            }
            else {
                $name = $this->getName();
            }
        }
        else {
            $login = null;
            $name = null;
        }

        $record = $dao->get(session_id());
        if ($record) {
            $record->login = $login;
            $record->name = $name;
            $record->last_request_date = time();
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
    }

	function getList() {
		$timeout = $this->getVisitTimeout();
        $dao = jDao::get('activeusers~connectedusers');
        $members = $dao->findConnected($timeout);
		return $members;
	}

	function getCount() {
		$timeout = $this->getVisitTimeout();
        $dao = jDao::get('activeusers~connectedusers');
        $members = $dao->countConnected($timeout);
		return $members;
	}



	protected function getName() {
		$user = jAuth::getUserSession();
        $name = '';
        if (isset($user->nickname))
            $name = $user->nickname;
        elseif (isset($user->name))
            $name = $user->name;
		if ($name == '')
			$name = $user->login;
		return $name;
	}
}
