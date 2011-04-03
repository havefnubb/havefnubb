<?php
/**
* @package   havefnubb
* @subpackage servinfo
* @author    FoxMaSk
* @copyright 2008-2011 FoxMaSk
* @link      http://havefnubb.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/
/**
 * Class that grabs server informations
 */
class ServerInfos {
    /**
     * Loads Average
     * @return string the load average
     */
    public static function loadsAvg() {
        // Get the server load averages (if possible)
        if (@file_exists('/proc/loadavg') && is_readable('/proc/loadavg'))
        {
            // We use @ just in case
            $fh = @fopen('/proc/loadavg', 'r');
            $loadAverages = @fread($fh, 64);
            @fclose($fh);

            $loadAverages = @explode(' ', $loadAverages);
            $serverLoad = isset($loadAverages[2]) ? $loadAverages[0].' '.$loadAverages[1].' '.$loadAverages[2] : jLocale::get('hfnuadmin~admin.server.infos.unavailable');;
        }
        else if (!in_array(PHP_OS, array('WINNT', 'WIN32')) && preg_match('/averages?: ([0-9\.]+),[\s]+([0-9\.]+),[\s]+([0-9\.]+)/i', @exec('uptime'), $load_averages))
            $serverLoad = $loadAverages[1].' '.$loadAverages[2].' '.$loadAverages[3];
        else
            $serverLoad = jLocale::get('servinfo~servinfo.server.infos.unavailable');

        return $serverLoad;
    }
    /**
     * Cache Engine detection
     * @return string cache engine
     */
    public static function cacheEngine() {
        // See if MMCache or PHPA is loaded
        if (function_exists('mmcache'))
            $phpAccelerator = '<a href="http://turck-mmcache.sourceforge.net/">Turck MMCache</a>';
        else if (isset($_PHPA))
            $phpAccelerator = '<a href="http://www.php-accelerator.co.uk/">ionCube PHP Accelerator</a>';
        else
            $phpAccelerator = 'N/A';
        return $phpAccelerator;
    }
    /**
     * Database Engine detection
     * @return string the database versin
     */
    public static function dbVersion() {
        $profile = jDb::getProfile();
        //@TODO get the current dbLink to give it to each RDBMS function
        /*
        $tools = jDb::getTools();
        $version = $tools->dbVersion();*/
        if ($profile['driver'] == 'pgsql') $version = pg_version();
        elseif ($profile['driver'] == 'mysql') $version = mysql_get_server_info();
        elseif ($profile['driver'] == 'sqlite') $version = sqlite_version();

        return $profile['driver'] . ' ' . $version;
    }
    /**
     * Size of the database
     * @return array the total size and record of the database
     */
    public static function dbSize() {
        $profile = jDb::getProfile();
        $con = jDb::getConnection();
        $totalRecords = $totalSize = 0;

        if ($profile['driver'] == 'mysql' or $profile['driver'] == 'mysqli') {
            $results = $con->query('SHOW TABLE STATUS FROM `'.$profile['database'].'`');
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
        return  array($totalRecords,$totalSize);
    }

}
