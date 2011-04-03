<?php
/**
* @package   havefnubb
* @subpackage hfnuthemes
* @author    FoxMaSk
* @copyright 2008-2011 FoxMaSk
* @link      http://havefnubb.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/
/**
 * class that lists the themes directory.
 */
class themes  {
    /**
     * get the themes
     * @return array the list of theme
     */
    static function lists() {
        $themes = array();

        $dir = new DirectoryIterator(JELIX_APP_VAR_PATH.'themes/');
        foreach ($dir as $dirContent) {
            if ($dirContent->isDir() and !$dirContent->isDot())
                $themes[] = self::readManifest($dirContent->getFilename());
        }
        return $themes;
    }
    /**
     * get the info of a given theme
     * @param string $theme the name of the theme
     * @return array details of the theme
     */
    static function readManifest($theme) {
        $themeInfos = array();
        $path = JELIX_APP_VAR_PATH.'/themes/'.$theme .'/theme.php';
        if (file_exists($path))
            include $path;
        return $themeInfos;
    }

}
