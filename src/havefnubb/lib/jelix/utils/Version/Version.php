<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @author      Laurent Jouanneau
* @copyright   2016 Laurent Jouanneau
*
* @link        http://www.jelix.org
* @licence     MIT
*/
namespace Jelix\Version;
class Version
{
	private $version=array();
	private $stabilityVersion=array();
	private $buildMetadata='';
	public function __construct(array $version,
								array $stabilityVersion=array(),
								$buildMetadata='')
	{
		$this->version=$version;
		$this->stabilityVersion=$stabilityVersion;
		$this->buildMetadata=$buildMetadata;
	}
	public function __toString()
	{
		return $this->toString();
	}
	public function toString($withPatch=true)
	{
		$version=$this->version;
		if($withPatch&&count($version)< 3){
			$version=array_pad($version,3,'0');
		}
		$vers=implode('.',$version);
		if($this->stabilityVersion){
			$vers.='-'.implode('.',$this->stabilityVersion);
		}
		if($this->buildMetadata){
			$vers.='+'.$this->buildMetadata;
		}
		return $vers;
	}
	public function getMajor()
	{
		return $this->version[0];
	}
	public function hasMinor()
	{
		return isset($this->version[1]);
	}
	public function getMinor()
	{
		if(isset($this->version[1])){
			return $this->version[1];
		}
		return 0;
	}
	public function hasPatch()
	{
		return isset($this->version[2]);
	}
	public function getPatch()
	{
		if(isset($this->version[2])){
			return $this->version[2];
		}
		return 0;
	}
	public function getTailNumbers()
	{
		if(count($this->version)> 3){
			return array_slice($this->version,3);
		}
		return array();
	}
	public function getVersionArray()
	{
		return $this->version;
	}
	public function getBranchVersion()
	{
		return $this->version[0].'.'.$this->getMinor();
	}
	public function getStabilityVersion()
	{
		return $this->stabilityVersion;
	}
	public function getBuildMetadata()
	{
		return $this->buildMetadata;
	}
	public function getNextMajorVersion()
	{
		return($this->version[0] + 1).'.0.0';
	}
	public function getNextMinorVersion()
	{
		return $this->version[0].'.'.($this->getMinor()+ 1).'.0';
	}
	public function getNextPatchVersion()
	{
		return $this->version[0].'.'.$this->getMinor().'.'.($this->getPatch()+ 1);
	}
	public function getNextTailVersion()
	{
		if(count($this->stabilityVersion)&&$this->stabilityVersion[0]!='stable'){
			return implode('.',$this->version);
		}
		$v=$this->version;
		++$v[count($v)- 1];
		return implode('.',$v);
	}
}
