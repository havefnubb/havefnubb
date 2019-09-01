<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package     jelix
* @subpackage  utils
* @author      Florian Hatat
* @contributor Laurent Jouanneau
* @copyright   2008 Florian Hatat, 2010 Laurent Jouanneau
* @link        http://www.jelix.org
* @licence     http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/
class jDuration{
	public $months;
	public $days;
	public $seconds;
	function __construct($init=0){
		$this->days=$this->months=$this->seconds=0;
		if(is_array($init)){
			if(isset($init['year'])){
				$this->months+=intval($init['year'])* 12;
			}
			if(isset($init['month'])){
				$this->months+=intval($init['month']);
			}
			if(isset($init['day'])){
				$this->days+=intval($init['day']);
			}
			if(isset($init['hour'])){
				$this->seconds+=intval($init['hour'])* 3600;
			}
			if(isset($init['minute'])){
				$this->seconds+=intval($init['minute'])* 60;
			}
			if(isset($init['second'])){
				$this->seconds+=intval($init['second']);
			}
		}
		elseif(is_int($init)){
			if($init > 86400){
				$this->days=intval($init/86400);
				$this->seconds=$init % 86400;
			}
			else{
				$this->seconds=$init;
			}
		}
	}
	function add(jDuration $data){
		$this->days+=$data->days;
		$this->months+=$data->months;
		$this->seconds+=$data->seconds;
	}
	function mult($scale){
		if(is_int($scale)){
			$this->days*=$scale;
			$this->months*=$scale;
			$this->seconds*=$scale;
		}
	}
}
