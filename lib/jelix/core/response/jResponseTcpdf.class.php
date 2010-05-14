<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package     jelix
* @subpackage  core_response
* @author      Julien Issler
* @contributor Uriel Corfa, Laurent Jouannneau
* @copyright   2007 Julien Issler, 2007 Emotic SARL, 2007 Laurent Jouanneau
* @link        http://www.jelix.org
* @licence     GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
* @since 1.0
*/
require_once(JELIX_LIB_UTILS_PATH.'jTcpdf.class.php');
class jResponseTcpdf  extends jResponse{
	protected $_type = 'tcpdf';
	public $tcpdf = null;
	public $outputFileName = 'document.pdf';
	public $doDownload = false;
	public function output(){
		if($this->hasErrors())
			return false;
		if(!($this->tcpdf instanceof jTcpdf))
			throw new jException('jelix~errors.reptcpdf.not_a_jtcpdf');
		$pdf_data = $this->tcpdf->Output('','S');
		header("Cache-Control: public, must-revalidate, max-age=0");
		header("Pragma: public");
		header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
		header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
		header('Content-Length: '.strlen($pdf_data));
		if($this->doDownload){
			header("Content-Type: application/force-download");
			header("Content-Type: application/octet-stream", false);
			header("Content-Transfer-Encoding: binary");
			header('Content-Disposition: attachment; filename="'.str_replace('"','\"',$this->outputFileName).'";');
			echo $pdf_data;
		}
		else{
			header('Content-Type: application/pdf');
			header('Content-Disposition: inline; filename="'.str_replace('"','\"',$this->outputFileName).'";');
			echo $pdf_data;
		}
		flush();
		return true;
	}
	public function outputErrors(){
		global $gJConfig;
		$this->clearHttpHeaders();
		$this->_httpStatusCode ='500';
		$this->_httpStatusMsg ='Internal Server Error';
		$this->addHttpHeader('Content-Type','text/plain;charset='.$gJConfig->charset,false);
		$this->sendHttpHeaders();
		if($this->hasErrors()){
			foreach( $GLOBALS['gJCoord']->errorMessages  as $e)
			   echo '['.$e[0].' '.$e[1].'] '.$e[2]." \t".$e[3]." \t".$e[4]."\n";
		}
		else
			echo "[unknown error]\n";
	}
	public function initPdf($orientation='P', $unit='mm', $format='A4', $encoding=null){
		$this->tcpdf = new jTcpdf($orientation, $unit, $format, $encoding);
	}
	public function __call($method, $attr){
		if($this->tcpdf !== null)
			return call_user_func_array(array($this->tcpdf, $method), $attr);
		else
			throw new jException('jelix~errors.reptcpdf.not_a_jtcpdf');
	}
}