<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package     jelix
* @subpackage  core_response
* @author      Aubanel MONNIER
* @contributor Laurent Jouanneau
* @contributor johannb
* @copyright   2007 Aubanel MONNIER
* @link        http://aubanel.info
* @licence     GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
*/
class jResponseLatexToPdf extends jResponse{
	protected $_type = 'ltx2pdf';
	public $bodyTpl = '';
	public $body = null;
	public $authors = array();
	public $title = '';
	protected $_commands=array();
	public $pdflatexPath='pdflatex';
	public $cachePath= '';
	function __construct(){
		$this->cachePath = JELIX_APP_TEMP_PATH.'responseLatexToPdf/';
		$this->body = new jTpl();
		parent::__construct();
	}
	public function addCommand($command, $argument, $options=array()){
		$cmd = '\\'.$command;
		if(count($options))
			$cmd.='['.join(',',$options).']';
		$this->_commands []= $cmd.'{'.$argument.'}';
	}
	public function addDefaultCommands(){
		$this->addCommand('documentclass', 'article', array('a4', '11pt'));
		$this->addCommand('usepackage', 'fontenc', array('T1'));
		$this->addCommand('usepackage', 'graphicx');
		$this->addCommand('usepackage', 'geometry', array('pdftex'));
		$this->addCommand('geometry', 'hmargin=1cm, vmargin=1cm');
	}
	function output(){
		$this->_commonProcess();
		if(count($this->_commands) <= 0)
			$this->addDefaultCommands();
		$data =  join("\n", $this->_commands).'
\begin{document}
\title{'.$this->title.'}
\author{';
		foreach($this->authors as $a)
			$data.= $a.'\\\\'."\n";
		$data.= '}
\date{\today}
\maketitle
';
		$data.=$this->body->fetch($this->bodyTpl);
		$data.= '

\end{document}';
		$fbase='cache-'.md5($data);
		$texFile=$this->cachePath.$fbase.'.tex';
		$pdfFile=$this->cachePath.$fbase.'.pdf';
		if(! file_exists($pdfFile)){
			jFile::write($texFile, $data);
			$output=array();
			$retVal=1;
				exec('
            TEXMFOUTPUT='.$this->cachePath.' && export TEXMFOUTPUT && TEXINPUTS=:'.$this->cachePath.' && export TEXINPUTS &&
            '.$this->pdflatexPath.' --interaction=batchmode '.$texFile, $output, $retVal);
			if($retVal!=0){
				$outputStr=implode('<br />',$output);
				throw new jException('jelix~errors.ltx2pdf.exec',array($this->pdflatexPath, $outputStr));
			}
		}
		$this->_httpHeaders['Content-Type']='application/pdf';
		$this->_httpHeaders['Content-length']=@filesize($pdfFile);
		$this->_httpHeaders['Content-Disposition']='attachment; filename='.$this->title.'.pdf';
		$this->sendHttpHeaders();
		readfile($pdfFile);
		return true;
	}
	protected function _commonProcess(){
	}
	public function clearCache(){
		jFile::removeDir($this->cachePath, false);
	}
	public function outputErrors(){
		global $gJConfig;
		header("HTTP/1.0 500 Internal Server Error");
		header('Content-Type: text/plain;charset='.$gJConfig->charset);
		if($this->hasErrors()){
			foreach( $GLOBALS['gJCoord']->errorMessages  as $e){
				echo '['.$e[0].' '.$e[1].'] '.$e[2]." \t".$e[3]." \t".$e[4]."\n";
			}
		}else{
			echo "[unknown error]\n";
		}
	}
}