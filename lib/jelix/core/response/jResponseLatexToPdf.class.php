<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package     jelix
* @subpackage  core_response
* @author      Aubanel Monnier
* @contributor Laurent Jouanneau, Thomas, Johannb
* @copyright   2007 Aubanel Monnier, 2009 Thomas, 2009-2010 Laurent Jouanneau
* @link        http://aubanel.info
* @licence     GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
*/
class jResponseLatexToPdf extends jResponse{
	protected $_type='ltx2pdf';
	public $bodyTpl='';
	public $body=null;
	public $authors=array();
	public $title='';
	public $date='\today';
	protected $_commands=array();
	public $pdflatexPath='pdflatex';
	public $cachePath='';
	public $outputFileName='document.pdf';
	function __construct(){
		$this->cachePath=jApp::tempPath('responseLatexToPdf/');
		$this->body=new jTpl();
		parent::__construct();
	}
	public function addCommand($command,$argument,$options=array()){
		$cmd='\\'.$command;
		if(count($options))
			$cmd.='['.join(',',$options).']';
		$this->_commands []=$cmd.'{'.$argument.'}';
	}
	public function addDefaultCommands(){
		$this->addCommand('documentclass','article',array('a4','11pt'));
		$this->addCommand('usepackage','fontenc',array('T1'));
		$this->addCommand('usepackage','graphicx');
		$this->addCommand('usepackage','geometry',array('pdftex'));
		$this->addCommand('geometry','hmargin=1cm, vmargin=1cm');
	}
	function output(){
		$this->_commonProcess();
		if(count($this->_commands)<=0)
			$this->addDefaultCommands();
		$data=join("\n",$this->_commands).'
\begin{document}
\title{'.$this->title.'}
\author{';
		foreach($this->authors as $a)
			$data.=$a.'\\\\'."\n";
		$data.='}
\date{'.$this->date.'}
';
		$data.=$this->body->fetch($this->bodyTpl);
		$data.='

\end{document}';
		$fbase='cache-'.md5($data);
		$texFile=$this->cachePath.$fbase.'.tex';
		$pdfFile=$this->cachePath.$fbase.'.pdf';
		if(! file_exists($pdfFile)){
			jFile::write($texFile,$data);
			$output=array();
			$retVal=1;
			exec($this->pdflatexPath.' --interaction batchmode --output-directory '.$this->cachePath.' '.$texFile,$output,$retval);
			if($retVal!=0){
				$outputStr=implode('<br />',$output);
				throw new jException('jelix~errors.ltx2pdf.exec',array($this->pdflatexPath,$outputStr));
			}
		}
		$this->_httpHeaders['Content-Type']='application/pdf';
		$this->_httpHeaders['Content-length']=@filesize($pdfFile);
		$this->_httpHeaders['Content-Disposition']='attachment; filename='.$this->outputFileName;
		$this->sendHttpHeaders();
		readfile($pdfFile);
		return true;
	}
	protected function _commonProcess(){
	}
	public function clearCache(){
		jFile::removeDir($this->cachePath,false);
	}
}
