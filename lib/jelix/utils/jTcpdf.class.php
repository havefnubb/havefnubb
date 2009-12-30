<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package     jelix
* @subpackage  utils
* @author      Julien Issler
* @contributor Laurent Jouanneau
* @copyright   2007-2009 Julien Issler, 2007 Laurent Jouanneau
* @link        http://www.jelix.org
* @licence     GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
* @since 1.0
*/
define('K_TCPDF_EXTERNAL_CONFIG',true);
define('K_PATH_MAIN', LIB_PATH.'tcpdf/');
define('K_PATH_URL', $GLOBALS['gJConfig']->urlengine['basePath']);
define('K_PATH_FONTS', LIB_PATH.'pdf-fonts/');
define('K_PATH_CACHE', JELIX_APP_TEMP_PATH);
define('K_PATH_IMAGES', JELIX_APP_PATH);
define('K_BLANK_IMAGE', K_PATH_MAIN.'images/_blank.png');
define('K_CELL_HEIGHT_RATIO', 1.25);
define('K_SMALL_RATIO', 2/3);
require_once(LIB_PATH.'tcpdf/tcpdf.php');
class jTcpdf extends TCPDF{
	public function __construct($orientation='P', $unit='mm', $format='A4', $encoding=null){
		if($encoding === null)
			$encoding = $GLOBALS['gJConfig']->charset;
		parent::__construct($orientation, $unit, $format,($encoding == 'UTF-8' || $encoding == 'UTF-16'), $encoding);
		$this->setHeaderFont(array('helvetica','',10));
		$this->setFooterFont(array('helvetica','',10));
		$this->setFont('helvetica','',10);
	}
	public function Error($msg){
		throw new Exception($msg);
	}
	public function saveToDisk($filename,$path){
		if(!is_dir($path))
			throw new jException('jelix~errors.file.directory.notexists',array($path));
		if(!is_writable($path))
		   throw new jException('jelix~errors.file.directory.notwritable',array($path));
		if(file_put_contents(realpath($path).'/'.$filename, $this->Output('','S')))
		   return true;
		throw new jException('jelix~errors.file.write.error',array($path.'/'.$filename,''));
	}
}