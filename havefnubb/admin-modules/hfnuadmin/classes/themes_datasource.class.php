<?php
/**
* @package   havefnubb
* @subpackage hfnuadmin
* @author    FoxMaSk
* @copyright 2008-2011 FoxMaSk
* @link      http://havefnubb.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

// class that list the themes directory.
class themes_datasource implements jIFormDatasource {
    protected $formId = 0;

    protected $data = array();

    function __construct($id)
    {
        $dir = jApp::wwwPath('themes');
        $data = array();

        if (is_dir($dir)) {
            if ($dh = opendir($dir)) {
                while (($file = readdir($dh)) !== false) {
                    if ($file != "." && $file != ".." && $file != ".svn" && $file !='.cvs') {
                        if (is_dir($dir. DIRECTORY_SEPARATOR.$file))
                            $data[$file] = $file;
                    }
                }
                closedir($dh);
            }
        }

        $this->formId = $id;
        $this->data = $data;
    }

    public function getData($form)
    {
        return ($this->data);
    }

    public function getLabel($key)
    {
        if(isset($this->data[$key]))
          return $this->data[$key];
        else
          return null;
    }
}
