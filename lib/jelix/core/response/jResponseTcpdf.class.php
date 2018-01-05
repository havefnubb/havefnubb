<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package     jelix
* @subpackage  core_response
* @author      Julien Issler
* @contributor Uriel Corfa, Laurent Jouanneau
* @copyright   2007 Julien Issler, 2007 Emotic SARL, 2007-2010 Laurent Jouanneau
* @link        http://www.jelix.org
* @licence     GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
* @since 1.0
*/
require_once(JELIX_LIB_UTILS_PATH.'jTcpdf.class.php');
class jResponseTcpdf  extends jResponse{
	protected $_type='tcpdf';
	public $tcpdf=null;
	public $outputFileName='document.pdf';
	public $doDownload=false;
	public function output(){
		if(!($this->tcpdf instanceof jTcpdf))
			throw new jException('jelix~errors.reptcpdf.not_a_jtcpdf');
		$pdf_data=$this->tcpdf->Output('','S');
		$this->addHttpHeader('Cache-Control','public, must-revalidate, max-age=0',false);
		$this->addHttpHeader('Pragma','public',false);
		$this->addHttpHeader('Expires','Sat, 26 Jul 1997 05:00:00 GMT',false);
		$this->addHttpHeader('Last-Modified',gmdate("D, d M Y H:i:s")." GMT",false);
		$this->addHttpHeader('Content-Length',strlen($pdf_data));
		if($this->doDownload){
			$this->addHttpHeader("Content-Type","application/force-download");
			$this->addHttpHeader("Content-Type","application/octet-stream",-1);
			$this->addHttpHeader("Content-Transfer-Encoding","binary");
			$this->addHttpHeader('Content-Disposition','attachment; filename="'.str_replace('"','\"',$this->outputFileName).'";');
		}
		else{
			$this->addHttpHeader('Content-Type','application/pdf');
			$this->addHttpHeader('Content-Disposition','inline; filename="'.str_replace('"','\"',$this->outputFileName).'";');
		}
		$this->sendHttpHeaders();
		echo $pdf_data;
		flush();
		return true;
	}
	public function initPdf($orientation='P',$unit='mm',$format='A4',$encoding=null){
		$this->tcpdf=new jTcpdf($orientation,$unit,$format,$encoding);
	}
	public function __call($method,$attr){
		if($this->tcpdf!==null)
			return call_user_func_array(array($this->tcpdf,$method),$attr);
		else
			throw new jException('jelix~errors.reptcpdf.not_a_jtcpdf');
	}
}
