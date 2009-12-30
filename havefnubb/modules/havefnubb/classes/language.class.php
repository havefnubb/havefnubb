<?php
/**
* Language DataSource
* 
* @package   havefnubb
* @subpackage havefnubb
* @author    FoxMaSk
* @copyright 2008 FoxMaSk
* @link      http://havefnubb.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*
*/
class language implements jIFormsDatasource
{
	protected $formId = 0; 
	protected $data = array();  
	
	function __construct($id) {
		$data = array();
		$dir = dirname(__FILE__) . '/../locales';
		$dh = opendir($dir);
		while (($file = readdir($dh)) !== false) {
			if ( $file != '.' and $file != '..' and $file != '.svn' and $file != '.CVS' and $file != '.htaccess')
				$data[$file] = $file;
		}

		$this->formId = $id;
		$this->data = $data;

	}
	
	public function getData($form)    {
		return ($this->data);
	}
	
	public function getLabel($key)    {
		if(isset($this->data[$key]))
			return $this->data[$key];
		else
			return null;
	}

}
