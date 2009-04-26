<?php
/**
* @package      downloads
* @subpackage
* @author       foxmask
* @contributor foxmask
* @copyright    2008 foxmask
* @link
* @licence  http://www.gnu.org/licenses/gpl.html GNU General Public Licence, see LICENCE file
*/
class downloadConfig 
{
    const DL_INI_FILE = 'downloads.module.ini.php';
    public static function getConfig() {
        global $gJConfig;
        $file = JELIX_APP_CONFIG_PATH.self::DL_INI_FILE;
        $config =  new jIniFileModifier($file);
        return $config;    
    }
}