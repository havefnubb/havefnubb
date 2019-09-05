<?php
/**
* @package   havefnubb
* @subpackage servinfo
* @author    FoxMaSk
* @copyright 2008-2011 FoxMaSk, 2019 Laurent Jouanneau
* @link      https://havefnubb.jelix.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

namespace HavefnuBB\ServerInfos;

/**
 * Class that grabs server informations
 */
class ServerInfos {
    /**
     * Loads Average
     * @return string the load average
     */
    public function loadsAvg() {
        // Get the server load averages (if possible)
        if (@file_exists('/proc/loadavg') && is_readable('/proc/loadavg'))
        {
            // We use @ just in case
            $fh = @fopen('/proc/loadavg', 'r');
            $loadAverages = @fread($fh, 64);
            @fclose($fh);

            $loadAverages = @explode(' ', $loadAverages);
            $serverLoad = isset($loadAverages[2]) ? $loadAverages[0].' '.$loadAverages[1].' '.$loadAverages[2] : \jLocale::get('hfnuadmin~admin.server.infos.unavailable');
        }
        else if (!in_array(PHP_OS, array('WINNT', 'WIN32')) &&
            preg_match('/averages?: ([0-9\.]+),[\s]+([0-9\.]+),[\s]+([0-9\.]+)/i', @exec('uptime'), $loadAverages)
        ) {
            $serverLoad = $loadAverages[1] . ' ' . $loadAverages[2] . ' ' . $loadAverages[3];
        }
        else {
            $serverLoad = \jLocale::get('servinfo~servinfo.server.infos.unavailable');
        }

        return $serverLoad;
    }
    /**
     * Cache Engine detection
     * @return string cache engine
     */
    public function cacheEngine() {

        if (extension_loaded('apc') && ini_get('apc.enabled') == 1) {
            $phpAccelerator = 'APC';
        }
        else if (extension_loaded('opcache') && ini_get('opcache.enabled') == 1) {
            $phpAccelerator = 'OPcache';
        } else {
            $phpAccelerator = 'N/A';
        }
        return $phpAccelerator;
    }

    /**
     * Database Engine detection
     * @return string the database versin
     */
    public function dbVersion() {

        $cnt = \jDb::getConnection();
        $version = $cnt->getAttribute(\jDbConnection::ATTR_SERVER_VERSION);

        return $cnt->dbms . ' ' . $version;
    }

    /**
     * Size of the database
     * @return array the total size and record of the database
     */
    public static function dbSize() {

        $con = \jDb::getConnection();
        $totalRecords = $totalSize = 0;

        if ($con->dbms == 'mysql') {
            $results = $con->query('SHOW TABLE STATUS FROM `'.$con->profile['database'].'`');
            foreach($results as $status) {
                $totalRecords += $status->Rows;
                $totalSize += $status->Data_length + $status->Index_length;
            }

            $totalSize = $totalSize / 1024;

            if ($totalSize > 1024)
                $totalSize = round($totalSize / 1024, 2).' MB';
            else
                $totalSize = round($totalSize, 2).' KB';
        }
        return array($totalRecords,$totalSize);
    }

}
