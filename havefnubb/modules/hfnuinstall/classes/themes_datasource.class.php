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
class themes_datasource implements jIFormDatasource
{
    protected $formId = 0;
    
    protected $data = array();
    
    function __construct($id)
    {
        $data = array();

        $dir = new DirectoryIterator(JELIX_APP_VAR_PATH.'themes/');
        foreach ($dir as $dirContent) {
            if ($dirContent->isDir() and $dirContent != '.' and $dirContent != '..' and $dirContent != '.svn') 
                $data[$dirContent->getFilename()] = $dirContent->getFilename();
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