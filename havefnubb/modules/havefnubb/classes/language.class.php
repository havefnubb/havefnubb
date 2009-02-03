<?php
/**
* @package   havefnubb
* @subpackage havefnubb
* @author    FoxMaSk
* @copyright 2008 FoxMaSk
* @link      http://forge.jelix.org/projects/havefnubb
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/


class language implements jIFormDatasource
{
  protected $formId = 0; 
  protected $data = array();  

  function __construct($id)
  {
    $data = array();
    $dir = JELIX_APP_PATH.'modules'.DIRECTORY_SEPARATOR.'havefnubb'.DIRECTORY_SEPARATOR.'locales';
    $dh = opendir($dir);
     while (($file = readdir($dh)) !== false) {
            if ( $file != '.' and $file != '..')
                $data[$file] = $file;
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
