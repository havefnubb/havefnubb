<?php
/**
* @package   havefnubb
* @subpackage hfnuthemes
* @author    FoxMaSk
* @copyright 2008 FoxMaSk
* @link      http://havefnubb.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

// class that list the themes directory.
class themes 
{
    
    static function lists() {
        $themes = array();

        $dir = new DirectoryIterator(JELIX_APP_VAR_PATH.'themes/');
        foreach ($dir as $dirContent) {
            if ($dirContent->isDir() and $dirContent != '.' and $dirContent != '..' and $dirContent != '.svn') 
                $themes[] = self::readManifest($dirContent->getFilename());
        }
        return $themes;
    }

    static function readManifest($theme) {
        
        $themeInfos = array(); 
                 
        include JELIX_APP_VAR_PATH.'/themes/'.$theme .'/theme.php';

        return $themeInfos; 
         
    }     
}