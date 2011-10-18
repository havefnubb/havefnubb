<?php
/**
 * @package   havefnubb
 * @subpackage havefnubb
 * @author    FoxMaSk
 * @contributor Laurent Jouanneau
 * @copyright 2008-2011 FoxMaSk, 2011 Laurent Jouanneau
 * @link      http://havefnubb.org
 * @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
 */
/**
* Class that follows connections of a user
*/
class connectedusers {

    /**
     * to call when a user log in
     * @param string $login
     */
    public function connectUser($login) {
        $dao = jDao::get('activeusers~connectedusers');
        $name = $this->getName();

        $record = $dao->get(session_id());
        if ($record) {
            $record->login = $login; // perhaps the record exist, but with an anonymous user
            $record->name = $name;
            $record->member_ip = $GLOBALS['gJCoord']->request->getIP();
            $record->connection_date = $record->last_request_date = time();
            $record->disconnection_date = null;
            $dao->update($record);
        }
        else {
            $record = jDao::createRecord('activeusers~connectedusers');
            $record->sessionid = session_id();
            $record->login = $login;
            $record->name = $name;
            $record->member_ip = $GLOBALS['gJCoord']->request->getIP();
            $record->connection_date = $record->last_request_date = time();
            $record->disconnection_date = null;
            $dao->insert($record);
        }
        // this is an opportunity to delete old sessions
        $this->deleteOldSessions();
    }

    /**
     * to call when a user logout
     */
    public function disconnectUser($login) {
        $dao = jDao::get('activeusers~connectedusers');
        $record = $dao->get(session_id());
        if ($record) {
            $record->disconnection_date = $record->last_request_date = time();
            $dao->update($record);
        }

        // this is an opportunity to delete old sessions
        $this->deleteOldSessions();
    }

    /**
     * delete sessions that are too old
     */
    protected function deleteOldSessions() {
        jDao::get('activeusers~connectedusers')->clear(time()- (5*24*60*60)); // 5 days
    }

    /**
     * return the date that is the limit of the timeout: if a date is lower than
     * this date, then it is a deprecated date.
     * @return int  the unix timestamp of the timeout date.
     */
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

    /**
     * save the given timeout in the config file
     * @param integer $timeout  the number of second
     * @return boolean true if it has been saved correctly
     */
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

    /**
     * says if the given user is connected, ie, he has viewed a page
     * in the last 'timeout' seconds
     * @param string $login
     * @param boolean true if he is connected
     */
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
     * save the date of the visit for the current user.
     * Call it each time the user visit a page (connected or not)
     */
    public function check() {
        $dao = jDao::get('activeusers~connectedusers');

        $record = $dao->get(session_id());
        if ($record) {
            $record->last_request_date = time();
            $dao->update($record);
        }
        else {
            $record = jDao::createRecord('activeusers~connectedusers');
            $record->sessionid = session_id();

            if (jAuth::isConnected()) {
                $user = jAuth::getUserSession();
                $record->login = $user->login;
                $record->name = $this->getName();
            }
            else {
                $record->login = '';
                $record->name = $this->getBots();
            }
            $record->member_ip = $GLOBALS['gJCoord']->request->getIP();
            $record->connection_date = $record->last_request_date = time();
            $dao->insert($record);
        }
    }

    /**
     * returns the list of connected users, regarding the given timeout.
     * @return array results: 0=>the anonymous users number, 1=>list of connected users, 2=>list of bots
     */
    function getConnectedList($timeout = 0, $alsoDisconnectedUsers = false) {
        if ($timeout == 0)
            $timeout = $this->getVisitTimeout();
        $dao = jDao::get('activeusers~connectedusers');
        $members = $dao->findConnected($timeout);
        $list = array();
        $botlist = array();
        $currentlogin = '';
        $currentbot = '';
        $anonymous = 0;
        // members are ordered by login asc, so anonymous and bots are first
        foreach($members as $m) {
            if ($m->login != '') {
                if ($m->login != $currentlogin) { // we don't want duplicated login in the list
                    if($m->disconnection_date == '' // those who are connected
                       || ($alsoDisconnectedUsers && $m->disconnection_date > $timeout)) // and those who have been connected during the time interval, if we want them too
                        $list[] = $m;
                    else
                        $anonymous ++; //disconnected users are considered as anonymous
                    $currentlogin = $m->login;
                }
            } else {
                if ($m->name) {
                    // this is a bot
                    if ($m->name != $currentbot) {
                        $botlist[] = $m;
                        $currentbot = $m->name;
                    }
                }
                else { // for anonymous users, we just count them
                    $anonymous ++;
                }
            }
        }
        return array($anonymous, $list, $botlist);
    }


     /**
     * return the number of connected users
     * @return int
     */
    function getCount() {
        $timeout = $this->getVisitTimeout();
        if ($timeout) {
            $cn = jDb::getConnection();
            $sql =" SELECT COUNT(DISTINCT(login)) as cnt FROM ".$cn->prefixTable('connectedusers').'
                WHERE last_request_date > '. $timeout. ' AND disconnection_date IS null';
            $rs = $cn->query($sql);
            return $rs->fetch()->cnt;
        }
        return 0;
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

    /**
     * Let's see if the current user uses a Browser or is a Bot/Spider/Crawler
     * then return the name of this one or null
     */
    protected function getBots() {
        if (!isset($_SERVER['HTTP_USER_AGENT']) || $_SERVER['HTTP_USER_AGENT'] == '')
            return null;
        $browser = $_SERVER['HTTP_USER_AGENT'];
        // read the list of bots
        $botsList = jIniFile::read(JELIX_APP_CONFIG_PATH."botsagent.ini.php");

        if ($botsList) {

            $q_s=array("#\.#","#\*#","#\?#");
            $q_r=array("\.",".*",".?");

            foreach ($botsList as $name=>$bot ) {
                if ($bot['active']) {
                    $pattern=preg_replace($q_s,$q_r,$bot['agent']);
                    if (preg_match('#' . $pattern . '#i', $browser)) {
                        return $name;
                    }
                }
            }
        }
        return null;
    }
}
