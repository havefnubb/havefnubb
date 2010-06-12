<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package    jelix
* @subpackage utils
* @author     Laurent Jouanneau
* @contributor
* @copyright  2008-2009 Laurent Jouanneau
* @link       http://jelix.org
* @licence    http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/
class jIniMultiFilesModifier{
	protected $master;
	protected $overrider;
	function __construct($masterfilename,$overriderFilename){
		$this->master=new jIniFileModifier($masterfilename);
		$this->overrider=new jIniFileModifier($overriderFilename);
	}
	public function setValue($name,$value,$section=0,$key=null,$master=false){
		if($master){
			$this->master->setValue($name,$value,$section,$key);
		}
		else{
			$this->overrider->setValue($name,$value,$section,$key);
		}
	}
	public function getValue($name,$section=0,$key=null,$masterOnly=false){
		if($masterOnly){
			return $this->master->getValue($name,$section,$key);
		}
		else{
			$val=$this->overrider->getValue($name,$section,$key);
			if($val===null)
				$val=$this->master->getValue($name,$section,$key);
			return $val;
		}
	}
	public function save(){
		$this->master->save();
		$this->overrider->save();
	}
	public function isModified(){
		return $this->master->isModified()||$this->overrider->isModified();
	}
	public function getMaster(){
		return $this->master;
	}
	public function getOverrider(){
		return $this->overrider;
	}
}
