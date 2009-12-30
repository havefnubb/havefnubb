<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package    jelix
* @subpackage utils
* @author     Laurent Jouanneau
* @contributor
* @copyright  2006-2007 Laurent Jouanneau
* @link       http://www.jelix.org
* @licence    GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
*/
require_once(LIB_PATH.'wikirenderer/WikiRenderer.lib.php');
class jWiki extends  WikiRenderer{
	function __construct( $config=null){
		if(is_string($config)){
			$f = WIKIRENDERER_PATH.'rules/'.basename($config).'.php';
			if(file_exists($f)){
				require_once($f);
				$this->config= new $config();
			}else{
				global $gJConfig;
				require_once($gJConfig->_pluginsPathList_wr_rules[$config].$config.'.rule.php');
				$this->config = new $config();
			}
		}elseif(is_object($config)){
			$this->config=$config;
		}else{
			require_once(WIKIRENDERER_PATH . 'rules/wr3_to_xhtml.php');
			$this->config= new wr3_to_xhtml();
		}
		$this->inlineParser = new WikiInlineParser($this->config);
		foreach($this->config->bloctags as $name){
			$this->_blocList[]= new $name($this);
		}
   }
}