<?php
/**
* @package   havefnubb
* @subpackage havefnubb
* @author    FoxMaSk
* @copyright 2008 FoxMaSk
* @link      http://forge.jelix.org/projects/havefnubb
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

class ServerInfos {
    
    
    public static function loadsAvg() {
		// Get the server load averages (if possible)
		if (@file_exists('/proc/loadavg') && is_readable('/proc/loadavg'))
		{
			// We use @ just in case
			$fh = @fopen('/proc/loadavg', 'r');
			$loadAverages = @fread($fh, 64);
			@fclose($fh);
		
			$loadAverages = @explode(' ', $loadAverages);
			$server_load = isset($loadAverages[2]) ? $loadAverages[0].' '.$loadAverages[1].' '.$loadAverages[2] : jLocale::get('hfnuadmin~admin.server.infos.unavailable');;
		}
		else if (!in_array(PHP_OS, array('WINNT', 'WIN32')) && preg_match('/averages?: ([0-9\.]+),[\s]+([0-9\.]+),[\s]+([0-9\.]+)/i', @exec('uptime'), $load_averages))
			$serverLoad = $loadAverages[1].' '.$loadAverages[2].' '.$loadAverages[3];
		else
			$serverLoad = jLocale::get('hfnuadmin~admin.server.infos.unavailable');
        
        return $serverLoad;
    }
    
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

	public static function dbVersion() {
		return 0;
	}
	
	public static function dbSize() {
		return 0;
	}

	public static function dbRecords() {
		return 0;
	}    
}