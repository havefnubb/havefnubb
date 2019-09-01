<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package      jelix
* @subpackage   coord_plugin
* @author       Loic Mathaud
* @copyright    2007 Loic Mathaud
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/
class zendframeworkCoordPlugin implements jICoordPlugin{
	public $config;
	public function __construct($config){
		$this->config=$config;
	}
	public function beforeAction($params){
		if(isset($params['zf.active'])&&$params['zf.active']=='true'){
			set_include_path(get_include_path().PATH_SEPARATOR.$this->config['zendLibPath']);
			include_once($this->config['zendLibPath'].'/Zend/Loader.php');
		}
		return null;
	}
	public function beforeOutput(){}
	public function afterProcess(){}
}
