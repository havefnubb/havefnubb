<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package    jelix
* @subpackage utils
* @author     Laurent Jouanneau
* @copyright  2008-2013 Laurent Jouanneau
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
			throw new Exception('(23)The file '.$filename.' doesn\'t exist');
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
					$currentValue[2].=$line."\n";
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
					if($firstquote==''&&$secondquote=='')
						$currentValue[2]=trim($value);
					$this->content[$currentSection][]=$currentValue;
				}
			}else if(preg_match('/^(\s*;.*)$/',$line,$m)){
				$this->content[$currentSection][]=array(self::TK_COMMENT,$m[1]);
			}else if(preg_match('/^(\s*\[([a-z0-9_.\-@:]+)\]\s*)/i',$line,$m)){
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
		$lastKey=-1;
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
					if($item[3]!=$key||$key===''){
						$lastKey=$item[3];
						continue;
					}
				}
				if($key!==null){
					if($key==='')
						$key=0;
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
				if($lastKey!=-1)
					$lastKey++;
				else
					$lastKey=0;
				$this->content[$section][]=array(self::TK_ARR_VALUE,$name,$value,$lastKey);
			}
		}
		$this->modified=true;
	}
	public function setValues($values,$section=0){
		foreach($values as $name=>$val){
			if(is_array($val)){
				$i=0;
				foreach($val as $arval){
					$this->setValue($name,$arval,$section,$i++);
				}
			}
			else
				$this->setValue($name,$val,$section);
		}
	}
	public function removeValue($name,$section=0,$key=null,$removePreviousComment=true){
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
						continue;
					}
				}
				break;
			}
		}
		$this->modified=true;
	}
	public function getValue($name,$section=0,$key=null){
		if(!isset($this->content[$section])){
			return null;
		}
		$arrayValue=array();
		$isArray=false;
		foreach($this->content[$section] as $k=>$item){
			if(($item[0]!=self::TK_VALUE&&$item[0]!=self::TK_ARR_VALUE)
				||$item[1]!=$name)
				continue;
			if($item[0]==self::TK_ARR_VALUE){
				if($key!==null){
					if($item[3]!=$key)
						continue;
				}
				else{
					$isArray=true;
					$arrayValue[]=$item[2];
					continue;
				}
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
		if($isArray)
			return $arrayValue;
		return null;
	}
	public function getValues($section=0){
		if(!isset($this->content[$section])){
			return array();
		}
		$values=array();
		foreach($this->content[$section] as $k=>$item){
			if($item[0]!=self::TK_VALUE&&$item[0]!=self::TK_ARR_VALUE)
				continue;
			if(preg_match('/^-?[0-9]$/',$item[2])){
				$val=intval($item[2]);
			}
			else if(preg_match('/^-?[0-9\.]$/',$item[2])){
				$val=floatval($item[2]);
			}
			else if(strtolower($item[2])==='true'||strtolower($item[2])==='on'){
				$val=true;
			}
			else if(strtolower($item[2])==='false'||strtolower($item[2])==='off'){
				$val=false;
			}
			else
				$val=$item[2];
			if($item[0]==self::TK_VALUE){
				$values[$item[1]]=$val;
			}
			else{
				$values[$item[1]][$item[3]]=$val;
			}
		}
		return $values;
	}
	public function save($chmod=null){
		if($this->modified){
			if(false===@file_put_contents($this->filename,$this->generateIni())){
				throw new Exception("Impossible to write into ".$this->filename);
			}
			else if($chmod){
				chmod($this->filename,$chmod);
			}
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
		if(is_bool($value)){
			if($value===false){
				return "off";
			}else{
				return "on";
			}
		}
		if($value===''||is_numeric(trim($value))||(preg_match("/^[\w-.]*$/",$value)&&strpos("\n",$value)===false)){
			return $value;
		}else{
			$value='"'.$value.'"';
		}
		return $value;
	}
	public function import(jIniFileModifier $ini,$sectionPrefix='',$separator='_'){
		foreach($ini->content as $section=>$values){
			if($sectionPrefix){
				if($section=="0"){
					$realSection=$sectionPrefix;
				}
				else{
					$realSection=$sectionPrefix.$separator.$section;
				}
			}
			else $realSection=$section;
			if(isset($this->content[$realSection])){
				$this->mergeValues($values,$realSection);
			}
			else{
				if($values[0][0]==self::TK_SECTION)
					$values[0][1]='['.$realSection.']';
				else{
					array_unshift($values,array(self::TK_SECTION,'['.$realSection.']'));
				}
				$this->content[$realSection]=$values;
				$this->modified=true;
			}
		}
	}
	public function mergeSection($sectionSource,$sectionTarget){
		if(!isset($this->content[$sectionTarget]))
			return $this->renameSection($sectionSource,$sectionTarget);
		if(!isset($this->content[$sectionSource]))
			return false;
		$this->mergeValues($this->content[$sectionSource],$sectionTarget);
		if($sectionSource=="0")
			$this->content[$sectionSource]=array();
		else
			unset($this->content[$sectionSource]);
		$this->modified=true;
		return true;
	}
	protected function mergeValues($values,$sectionTarget){
		$previousItems=array();
		foreach($values as $k=>$item){
			switch($item[0]){
				case self::TK_SECTION:
				break;
				case self::TK_WS:
				if($item[1]=='--')
					break;
				case self::TK_COMMENT:
				$previousItems []=$item;
				break;
				case self::TK_VALUE:
				case self::TK_ARR_VALUE:
					$found=false;
					$lastNonValues=-1;
					foreach($this->content[$sectionTarget] as $j=>$item2){
						if($item2[0]!=self::TK_VALUE&&$item2[0]!=self::TK_ARR_VALUE){
							if($lastNonValues==-1&&$item2[0]!=self::TK_SECTION)
								$lastNonValues=$j;
							continue;
						}
						if($item2[1]!=$item[1]){
							$lastNonValues=-1;
							continue;
						}
						$found=true;
						$this->content[$sectionTarget][$j][2]=$item[2];
						$this->modified=true;
						break;
					}
					if(!$found){
						$previousItems[]=$item;
						if($lastNonValues > 0){
							$previousItems=array_splice($this->content[$sectionTarget],$lastNonValues,$j,$previousItems);
						}
						$this->content[$sectionTarget]=array_merge($this->content[$sectionTarget],$previousItems);
						$this->modified=true;
					}
					$previousItems=array();
					break;
			}
		}
	}
	public function renameValue($name,$newName,$section=0){
		if(!isset($this->content[$section]))
			return false;
		foreach($this->content[$section] as $k=>$item){
			if($item[0]!=self::TK_VALUE&&$item[0]!=self::TK_ARR_VALUE){
				continue;
			}
			if($item[1]!=$name){
				continue;
			}
			$this->content[$section][$k][1]=$newName;
			$this->modified=true;
			break;
		}
		return true;
	}
	public function renameSection($oldName,$newName){
		if(!isset($this->content[$oldName]))
			return false;
		if(isset($this->content[$newName])){
			return $this->mergeSection($oldName,$newName);
		}
		$newcontent=array();
		foreach($this->content as $section=>$values){
			if((string)$oldName==(string)$section){
				if($section=="0"){
					$newcontent[0]=array();
				}
				if($values[0][0]==self::TK_SECTION)
					$values[0][1]='['.$newName.']';
				else{
					array_unshift($values,array(self::TK_SECTION,'['.$newName.']'));
				}
				$newcontent[$newName]=$values;
			}
			else
				$newcontent [$section]=$values;
		}
		$this->content=$newcontent;
		$this->modified=true;
		return true;
	}
}
