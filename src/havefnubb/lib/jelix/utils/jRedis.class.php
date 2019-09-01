<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
 * @package     jelix
 * @subpackage  utils
 * @author      Laurent Jouanneau
 * @copyright   2016-2017 Laurent Jouanneau
 * @link        http://jelix.org
 * @licence     GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
 */
class jRedis extends \Redis
{
	public function flushByPrefix($prefix,$maxKeyToDeleteByIter=3000){
		$end=false;
		while(!$end){
			$nextIndex=null;
			$keysToDelete=array();
			while($nextIndex!==0){
				if($nextIndex==-1){
					$nextIndex=0;
				}
				$response=$this->scan($nextIndex,$prefix.'*');
				if($response===false){
					$end=true;
					break;
				}
				foreach($response as $key){
					if(!isset($keysToDelete[$key])){
						$keysToDelete[$key]=true;
					}
				}
				$end=($nextIndex===0);
				if(count($keysToDelete)>=$maxKeyToDeleteByIter){
					$nextIndex=0;
				}
			}
			foreach($keysToDelete as $key=>$v){
				$this->delete($key);
			}
		}
	}
}
