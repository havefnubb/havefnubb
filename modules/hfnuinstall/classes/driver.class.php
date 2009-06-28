<?php
/**
* @package   havefnubb
* @subpackage hfnuinstall
* @author    FoxMaSk
* @copyright 2008 FoxMaSk
* @link      http://forge.jelix.org/projects/havefnubb
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

class driver implements jIFormDatasource
{
    protected $formId = 0;
    
    protected $data = array();
    
    function __construct($id)
    {
        $data = array();

        $d = jClasses::getService('hfnuinstall~supported_drivers');
        $data = $d->getSupportedDrivers();
            
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
?>