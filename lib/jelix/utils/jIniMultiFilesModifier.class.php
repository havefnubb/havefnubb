<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package    jelix
* @subpackage utils
* @author     Laurent Jouanneau
* @copyright  2008-2010 Laurent Jouanneau
* @link       http://jelix.org
* @licence    http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/
class jIniMultiFilesModifier{
	protected $master;
	protected $overrider;
	function __construct($master,$overrider){
		if(is_object($master))
			$this->master=$master;
		else
			$this->master=new jIniFileModifier($master);
		if(is_object($overrider))
			$this->overrider=$overrider;
		else
			$this->overrider=new jIniFileModifier($overrider);
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
