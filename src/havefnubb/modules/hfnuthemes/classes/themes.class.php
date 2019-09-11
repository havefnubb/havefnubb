<?php
/**
* @package   havefnubb
* @subpackage hfnuthemes
* @author    FoxMaSk
* @contributor Laurent Jouanneau
* @copyright 2008-2011 FoxMaSk, 2019 Laurent Jouanneau
* @link      https://havefnubb.jelix.org
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
    function lists() {
        $themes = array();

        $dir = new DirectoryIterator(jApp::appPath().'app/themes/');
        foreach ($dir as $dirContent) {
            if ($dirContent->isDir() and !$dirContent->isDot())
                $themes[] = $this->readManifest($dirContent->getFilename());
        }
        $dir = new DirectoryIterator(jApp::varPath().'themes/');
        foreach ($dir as $dirContent) {
            if ($dirContent->isDir() and !$dirContent->isDot())
                $themes[] = $this->readManifest($dirContent->getFilename());
        }
        return $themes;
    }
    /**
     * get the info of a given theme
     * @param string $theme the name of the theme
     * @return array details of the theme
     */
    function readManifest($theme) {
        $themeInfos = array();
        $path = jApp::appPath().'/app/themes/'.$theme .'/theme.php';
        if (file_exists($path)) {
            include $path;
        }
        else {
            $path = jApp::varPath().'/themes/'.$theme .'/theme.php';
            if (file_exists($path)) {
                include $path;
            }
        }
        return $themeInfos;
    }

}
