<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package     jelix
* @subpackage  utils
* @author      Laurent Jouanneau
* @contributor 
* @copyright   2008-2009 Laurent Jouanneau
* @link        http://www.jelix.org
* @licence     GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
*/
class jVersionComparator{
	static function compareVersion($version1,$version2){
		if($version1==$version2)
			return 0;
		$v1=explode('.',$version1);
		$v2=explode('.',$version2);
		if(count($v1)> count($v2)){
			$v2=array_pad($v2,count($v1),($v2[count($v2)-1]=='*'?'*':'0'));
		}
		elseif(count($v1)< count($v2)){
			$v1=array_pad($v1,count($v2),($v1[count($v1)-1]=='*'?'*':'0'));
		}
		$r='/^([0-9]+)([a-zA-Z]*|pre|-?dev)([0-9]*)(pre|-?dev)?$/';
		foreach($v1 as $k=>$v){
			if($v==$v2[$k]||$v=='*'||$v2[$k]=='*')
				continue;
			$pm=preg_match($r,$v,$m1);
			$pm2=preg_match($r,$v2[$k],$m2);
			if($pm&&$pm2){
				if($m1[1]!=$m2[1]){
					return($m1[1] < $m2[1] ? -1: 1);
				}
				self::normalizeVersionNumber($m1);
				self::normalizeVersionNumber($m2);
				if($m1[2]!=$m2[2]){
					return($m1[2] < $m2[2] ? -1: 1);
				}
				if($m1[3]!=$m2[3]){
					return($m1[3] < $m2[3] ? -1: 1);
				}
				$v1pre=($m1[4]=='dev');
				$v2pre=($m2[4]=='dev');
				if($v1pre&&!$v2pre){
					return -1;
				}
				elseif($v2pre&&!$v1pre){
					return 1;
				}
				else if(!isset($v1[$k+1])&&!isset($v2[$k+1])){
					return 0;
				}
			}
			elseif($pm){
				throw new Exception("bad version number :". $version2);
			}
			else
				throw new Exception("bad version number :".$version1);
		}
		return 0;
	}
	static protected function normalizeVersionNumber(&$n){
		$n[2]=strtolower($n[2]);
		if($n[2]=='pre'||$n[2]=='dev'||$n[2]=='-dev'){
			$n[2]='';
			$n[3]='';
			$n[4]='dev';
		}
		if(!isset($n[4]))
			$n[4]='';
		else{
			$n[4]=strtolower($n[4]);
			if($n[4]=='pre'||$n[4]=='-dev')$n[4]='dev';
		}
		if($n[2]=='a')$n[2]='alpha';
		elseif($n[2]=='b')$n[2]='beta';
		elseif($n[2]=='')$n[2]='zzz';
	}
	static public function getBranchVersion($version){
	$v=explode('.',$version);
	$r='/^([0-9]+)([a-zA-Z]*|pre|-?dev)([0-9]*)(pre|-?dev)?$/';
	if(count($v)< 2)
		$v[1]='0';
	if(!preg_match($r,$v[0],$m)){
		return $version;
	}
	$version=$m[1];
	if(!preg_match($r,$v[1],$m)){
		return $version.'.0';
	}
	return $version.'.'.$m[1];
	}
}
