<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package    jelix
* @subpackage utils
* @author     Laurent Jouanneau
* @contributor
* @copyright  2008-2010 Laurent Jouanneau
* @link       http://jelix.org
* @licence    http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/
class jIniFileModifier{
	const TK_WS=0;
	const TK_COMMENT=1;
	const TK_SECTION=2;
	const TK_VALUE=3;
	const TK_ARR_VALUE=4;
	protected $content=array();
	protected $filename='';
	protected $modified=false;
	function __construct($filename){
		if(!file_exists($filename)||!is_file($filename))
			throw new jException('jelix~errors.file.notexists',$filename);
		$this->filename=$filename;
		$this->parse(preg_split("/(\r\n|\n|\r)/",file_get_contents($filename)));
	}
	function getFileName(){
		return $this->filename;
	}
	protected function parse($lines){
		$this->content=array(0=>array());
		$currentSection=0;
		$multiline=false;
		$currentValue=null;
		$arrayContents=array();
		foreach($lines as $num=>$line){
			if($multiline){
				if(preg_match('/^(.*)"\s*$/',$line,$m)){
					$currentValue[2].=$m[1];
					$multiline=false;
					$this->content[$currentSection][]=$currentValue;
				}else{
					$currentValue[2].=$m[1]."\n";
				}
			}else if(preg_match('/^\s*([a-z0-9_.-]+)(\[\])?\s*=\s*(")?([^"]*)(")?(\s*)/i',$line,$m)){
				list($all,$name,$foundkey,$firstquote,$value,$secondquote,$lastspace)=$m;
				if($foundkey!=''){
					if(isset($arrayContents[$currentSection][$name]))
						$key=count($arrayContents[$currentSection][$name]);
					else
						$key=0;
					$currentValue=array(self::TK_ARR_VALUE,$name,$value,$key);
					$arrayContents[$currentSection][$name][$key]=$value;
				}
				else
					$currentValue=array(self::TK_VALUE,$name,$value);
				if($firstquote=='"'&&$secondquote==''){
					$multiline=true;
					$currentValue[2].="\n";
				}else{
					$this->content[$currentSection][]=$currentValue;
				}
			}else if(preg_match('/^(\s*;.*)$/',$line,$m)){
				$this->content[$currentSection][]=array(self::TK_COMMENT,$m[1]);
			}else if(preg_match('/^(\s*\[([a-z0-9_.-@:]+)\]\s*)/i',$line,$m)){
				$currentSection=$m[2];
				$this->content[$currentSection]=array(
					array(self::TK_SECTION,$m[1]),
				);
			}else{
				$this->content[$currentSection][]=array(self::TK_WS,$line);
			}
		}
	}
	public function setValue($name,$value,$section=0,$key=null){
		$foundValue=false;
		$lastKey=0;
		if(isset($this->content[$section])){
			$deleteMode=false;
			foreach($this->content[$section] as $k=>$item){
				if($deleteMode){
					if($item[0]==self::TK_ARR_VALUE&&$item[1]==$name)
						$this->content[$section][$k]=array(self::TK_WS,'--');
					continue;
				}
				if(($item[0]!=self::TK_VALUE&&$item[0]!=self::TK_ARR_VALUE)
					||$item[1]!=$name)
					continue;
				if($item[0]==self::TK_ARR_VALUE&&$key!==null){
					if($item[3]!=$key){
						$lastKey=$item[3];
						continue;
					}
				}
				if($key!==null){
					$this->content[$section][$k]=array(self::TK_ARR_VALUE,$name,$value,$key);
				}else{
					$this->content[$section][$k]=array(self::TK_VALUE,$name,$value);
					if($item[0]==self::TK_ARR_VALUE){
						$deleteMode=true;
						$foundValue=true;
						continue;
					}
				}
				$foundValue=true;
				break;
			}
		}
		else{
			$this->content[$section]=array(array(self::TK_SECTION,'['.$section.']'));
		}
		if(!$foundValue){
			if($key===null){
				$this->content[$section][]=array(self::TK_VALUE,$name,$value);
			}else{
				$this->content[$section][]=array(self::TK_ARR_VALUE,$name,$value,$lastKey);
			}
		}
		$this->modified=true;
	}
	public function removeValue($name,$section=0,$key=null,$removePreviousComment=true){
		$foundValue=false;
		if($section===0&&$name=='')
			return;
		if($name==''){
			if($section===0||!isset($this->content[$section]))
				return;
			if($removePreviousComment){
				$previousSection=-1;
				foreach($this->content as $s=>$c){
					if($s===$section){
						break;
					}
					else{
						$previousSection=$s;
					}
				}
				if($previousSection!=-1){
					$s=$this->content[$previousSection];
					end($s);
					$tok=current($s);
					while($tok!==false){
						if($tok[0]!=self::TK_WS&&$tok[0]!=self::TK_COMMENT){
							break;
						}
						if($tok[0]==self::TK_COMMENT&&strpos($tok[1],'<?')===false){
							$this->content[$previousSection][key($s)]=array(self::TK_WS,'--');
						}
						$tok=prev($s);
					}
				}
			}
			unset($this->content[$section]);
			$this->modified=true;
			return;
		}
		if(isset($this->content[$section])){
			$deleteMode=false;
			$previousComment=array();
			foreach($this->content[$section] as $k=>$item){
				if($deleteMode){
					if($item[0]==self::TK_ARR_VALUE&&$item[1]==$name)
						$this->content[$section][$k]=array(self::TK_WS,'--');
					continue;
				}
				if($item[0]==self::TK_COMMENT){
					if($removePreviousComment)
						$previousComment[]=$k;
					continue;
				}
				if($item[0]==self::TK_WS){
					if($removePreviousComment)
						$previousComment[]=$k;
					continue;
				}
				if($item[1]!=$name){
					$previousComment=array();
					continue;
				}
				if($item[0]==self::TK_ARR_VALUE&&$key!==null){
					if($item[3]!=$key){
						$previousComment=array();
						continue;
					}
				}
				if(count($previousComment)){
					$kc=array_pop($previousComment);
					while($kc!==null&&$this->content[$section][$kc][0]==self::TK_WS){
						$kc=array_pop($previousComment);
					}
					while($kc!==null&&$this->content[$section][$kc][0]==self::TK_COMMENT){
						if(strpos($this->content[$section][$kc][1],"<?")===false){
							$this->content[$section][$kc]=array(self::TK_WS,'--');
						}
						$kc=array_pop($previousComment);
					}
				}
				if($key!==null){
					$this->content[$section][$k]=array(self::TK_WS,'--');
				}else{
					$this->content[$section][$k]=array(self::TK_WS,'--');
					if($item[0]==self::TK_ARR_VALUE){
						$deleteMode=true;
						$foundValue=true;
						continue;
					}
				}
				$foundValue=true;
				break;
			}
		}
		$this->modified=true;
	}
	public function getValue($name,$section=0,$key=null){
		if(!isset($this->content[$section])){
			return null;
		}
		foreach($this->content[$section] as $k=>$item){
			if(($item[0]!=self::TK_VALUE&&$item[0]!=self::TK_ARR_VALUE)
				||$item[1]!=$name)
				continue;
			if($item[0]==self::TK_ARR_VALUE&&$key!==null){
				if($item[3]!=$key)
					continue;
			}
			if(preg_match('/^-?[0-9]$/',$item[2])){
				return intval($item[2]);
			}
			else if(preg_match('/^-?[0-9\.]$/',$item[2])){
				return floatval($item[2]);
			}
			else if(strtolower($item[2])==='true'||strtolower($item[2])==='on'){
				return true;
			}
			else if(strtolower($item[2])==='false'||strtolower($item[2])==='off'){
				return false;
			}
			return $item[2];
		}
		return null;
	}
	public function save(){
		if($this->modified){
			if(false===@file_put_contents($this->filename,$this->generateIni()))
				throw new Exception("Impossible to write into ".$this->filename);
			$this->modified=false;
		}
	}
	public function saveAs($filename){
		file_put_contents($filename,$this->generateIni());
	}
	public function isModified(){
		return $this->modified;
	}
	public function isSection($name){
		return isset($this->content[$name]);
	}
	public function getSectionList(){
		$list=array_keys($this->content);
		array_shift($list);
		return $list;
	}
	protected function generateIni(){
		$content='';
		foreach($this->content as $sectionname=>$section){
			foreach($section as $item){
				switch($item[0]){
				case self::TK_SECTION:
					if($item[1]!='0')
						$content.=$item[1]."\n";
					break;
				case self::TK_WS:
					if($item[1]=='--')
						break;
				case self::TK_COMMENT:
					$content.=$item[1]."\n";
					break;
				case self::TK_VALUE:
						$content.=$item[1].'='.$this->getIniValue($item[2])."\n";
					break;
				case self::TK_ARR_VALUE:
						$content.=$item[1].'[]='.$this->getIniValue($item[2])."\n";
					break;
				}
			}
		}
		return $content;
	}
	protected function getIniValue($value){
		if($value===''||is_numeric(trim($value))||(preg_match("/^[\w-.]*$/",$value)&&strpos("\n",$value)===false)){
			return $value;
		}else if($value===false){
			$value="0";
		}else if($value===true){
			$value="1";
		}else{
			$value='"'.$value.'"';
		}
		return $value;
	}
}
