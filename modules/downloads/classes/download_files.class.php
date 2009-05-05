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
class downloadFiles {
    /*
     * getFileSize
     *
     * display the filesize on the preview page
     */
	public static function getFileSize($filename,$path) {
		jClasses::inc('download_config');
		$config = downloadConfig::getConfig();
		
        $file = realpath($config->getValue('commons.upload.dir')).DIRECTORY_SEPARATOR.$path.DIRECTORY_SEPARATOR.$filename;
		
		$filesize = '';
		
		if (! file_exists($file)) return $filesize;
        
        //on ne calcul pas la taille de fichiers "distants"

        // calculate the size of the file to download and display it
		$size = @filesize($file);
		$i=0;
		$iec = array("B", "KB", "MB", "GB", "TB", "PB", "EB", "ZB", "YB");
		while (($size/1024)>1)
		{
			$size=$size/1024;
			$i++;
		}
		$filesize = substr($size,0,strpos($size,'.')+4).$iec[$i];

        return $filesize;
    }
	
    // AntiLeech : check the HTTP_REFERER vs HTTP_HOST
    public static function antileech() {
        $referer = getenv("HTTP_REFERER");
        $host = parse_url($referer);        

        if (!isset($host['host'])) return false;			

        $host_string='';
        
        if (array_key_exists('port',$host))
            if ( $host['port'] != '80' ) 
                $host_string = $host['host'].":".$host['port'];
            else 
                $host_string = $host['host'];
        else $host_string = $host['host'];

        if ($host_string != '' && $host_string != $_SERVER['HTTP_HOST'])
            return true; // yes it's a leecher ; we bloc it
        else
            return false;  
    }	
}