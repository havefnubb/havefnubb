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
        $language = preg_split('/_/',$gJConfig->locale);
        $data = array();
        
        if (! in_array($language[0], $supportedLanguage)) return $data;

		$fh = @fopen (dirname(__FILE__).'/iso_3166-1_list_'.$language[0].'.txt','r');

       if ($fh) {
           while (!feof($fh)) {
                $buffer = fgets($fh, 4096);
                list($countryName,$countryCode) = preg_split('/;/',$buffer);
                $data[$countryCode] = utf8_encode($countryName);
           }
       }
/*

		$data = array();
		$dir = JELIX_APP_WWW_PATH.DIRECTORY_SEPARATOR.'hfnu'.DIRECTORY_SEPARATOR.'images'.DIRECTORY_SEPARATOR.'flags';
		$dh = opendir($dir);
		 while (($file_complet = readdir($dh)) !== false) {
			if ( strpos($file_complet,".") > 2) {
				list($file) = preg_split('/\.gif/',$file_complet);
				$data[$file] = $file;
			}
		}*/

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
