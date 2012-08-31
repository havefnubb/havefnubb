<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package     jelix
* @subpackage  core_response
* @author      Laurent Jouanneau
* @copyright   2006-2010 Laurent Jouanneau
* @link        http://www.jelix.org
* @licence     GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
*/
final class jResponseRdf extends jResponse{
	protected $_type='rdf';
	public $data;
	public $template;
	public $resNs="http://dummy/rdf#";
	public $resNsPrefix='row';
	public $resUriPrefix="urn:data:row:";
	public $resUriRoot="urn:data:row";
	public $asAttribute=array();
	public $asElement=array();
	public function output(){
		$this->_httpHeaders['Content-Type']='text/xml;charset='.$GLOBALS['gJConfig']->charset;
		if($this->template!=''){
			$tpl=new jTpl();
			$tpl->assign('data',$this->data);
			$content=$tpl->fetch($this->template);
		}
		else{
			$content=$this->generateContent();
		}
		$this->sendHttpHeaders();
		echo '<?xml version="1.0" encoding="'.$GLOBALS['gJConfig']->charset.'"?>';
		echo $content;
		return true;
	}
	protected function generateContent(){
		ob_start();
		$EOL="\n";
		echo '<RDF xmlns:RDF="http://www.w3.org/1999/02/22-rdf-syntax-ns#" xmlns="http://www.w3.org/1999/02/22-rdf-syntax-ns#"'.$EOL;
		echo '  xmlns:',$this->resNsPrefix,'="',$this->resNs,'"  xmlns:NC="http://home.netscape.com/NC-rdf#">',$EOL;
		echo '<Bag RDF:about="'.$this->resUriRoot.'">'.$EOL;
		foreach($this->data as $dt){
			echo "<li>\n<Description ";
			if(is_object($dt))
				$dt=get_object_vars($dt);
			if(count($this->asAttribute)||count($this->asElement)){
				foreach($this->asAttribute as $name){
					echo $this->resNsPrefix,':',$name,'="',htmlspecialchars($dt[$name]),'" ';
				}
				if(count($this->asElement)){
					echo ">\n";
					foreach($this->asElement as $name){
						echo '<',$this->resNsPrefix,':',$name,'>',htmlspecialchars($dt[$name]),'</',$this->resNsPrefix,':',$name,">\n";
					}
					echo "</Description>\n</li>\n";
				}else
					echo "/>\n</li>\n";
			}else{
				if(count($dt)){
					echo ">\n";
					foreach($dt as $name=>$val){
						echo '<',$this->resNsPrefix,':',$name,'>',htmlspecialchars($val),'</',$this->resNsPrefix,':',$name,">\n";
					}
					echo "</Description>\n</li>\n";
				}else{
					echo "/>\n</li>\n";
				}
			}
		}
		echo "</Bag>\n</RDF>\n";
		return ob_get_clean();
	}
}
