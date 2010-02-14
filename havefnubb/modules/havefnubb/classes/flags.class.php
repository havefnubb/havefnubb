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
        global $gJConfig;

        $supportedLanguage = array('fr','en');
        //get the member language
        $language = preg_split('/_/',$_SESSION['JX_LANG']);
        $data = array();
        
        if (! in_array($language[0], $supportedLanguage)) return $data;

		$fh = @fopen (dirname(__FILE__).'/iso_3166-1_list_'.$language[0].'.txt','r');
        if ($fh) {
           while (!feof($fh)) {
                $buffer = utf8_encode(fgets($fh, 4096));
                if (strpos($buffer,';') > 0) {
                    list($countryName,$countryCode) = preg_split('/;/',$buffer);
                    $data[rtrim($countryCode)] = $countryName;
                }
           }
       }

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
