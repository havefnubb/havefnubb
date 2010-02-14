<?php
/**
 * DataSource to get the list of flags of all the countries
 * 
 * @package   havefnubb
 * @subpackage havefnubb
 * @author    FoxMaSk
 * @copyright 2008 FoxMaSk
 * @link      http://havefnubb.org
 * @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/
class flags implements jIFormsDatasource {
	protected $formId = 0; 
	protected $data = array();  
	
	function __construct($id) {

        $data = jClasses::getService('havefnubb~country')->getCountries();

		$this->formId = $id;
		$this->data = $data;
		
	}

	public function getData($form) {
		return ($this->data);
	}
	
	public function getLabel($key) {
		if(isset($this->data[$key]))
			return $this->data[$key];
		else
			return null;
	}
}
