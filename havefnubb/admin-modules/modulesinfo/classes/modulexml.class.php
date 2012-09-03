<?php
/**
 * @package   havefnubb
 * @subpackage modulesinfo
 * @author    FoxMaSk
 * @contributor Laurent Jouanneau, Vincent Viaud
 * @copyright 2008-2012 FoxMaSk, 2010 Laurent Jouanneau, 2010 Vincent Viaud
 * @link      http://havefnubb.org
 * @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
 */

require_once (dirname(__FILE__). '/filexml.class.php');

class moduleInfo {
    public $id='';
    public $name='';
    public $createDate='';

    public $version = '';
    public $versionDate = '';
    public $versionStability = '';

    public $label = '';
    public $description = '';

    public $creators = array();
    public $contributors = array();
    public $notes = '';
    public $homepageURL = '';
    public $updateURL = '';
    public $license = '';
    public $licenseURL = '';
    public $copyright = '';
    public $dependencies = array();
}

/**
 * Class to parse the module.xml file of each module
 */
class modulexml extends filexml {
    public function getList() {
        $list = array();
        foreach (jApp::config()->_modulesPathList as $name=>$path) {
            $info = $this->getModule($name);
            if ($info)
                $list[$name] = $info;
        }
        return $list;
    }

    public function getModule($name) {
        $gJConfig = jApp::config();

        if (! array_key_exists($name,$gJConfig->_modulesPathList) ) return false;

        $file = $gJConfig->_modulesPathList[$name] . '/module.xml';

        if (!file_exists($file)) {
            return null;
        }

        $module = new moduleInfo();

        return $this->parse($file, $module);
    }
}
