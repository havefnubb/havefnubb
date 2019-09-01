<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package     jelix
* @subpackage  utils
* @author      Gérald Croes, Laurent Jouanneau
* @contributor Laurent Jouanneau, Julien Issler
* @contributor Loic Mathaud
* @contributor Florian Hatat
* @contributor Emmanuel Hesry, Brice G.
* @contributor Hadrien Lanneau <hadrien@over-blog.com>
* @copyright   2005-2011 Laurent Jouanneau
* @copyright   2007 Loic Mathaud
* @copyright   2007-2008 Florian Hatat
* @copyright   2001-2005 CopixTeam, GeraldCroes, Laurent Jouanneau
* @copyright   2008-2011 Julien Issler
* @copyright   2009 Emmanuel Hesry
* @copyright   2010 Hadrien Lanneau, 2011 Brice G.
*
* This class was get originally from the Copix project (CopixDate.lib.php, Copix 2.3dev20050901, http://www.copix.org)
* Only few lines of code are still copyrighted 2001-2005 CopixTeam (LGPL licence).
* Initial authors of this Copix classes are Gerald Croes and Laurent Jouanneau,
* and this class was adapted/improved for Jelix by Laurent Jouanneau
*
* @link        http://www.jelix.org
* @licence     http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/
class jDateTime{
	public $day;
	public $month;
	public $year;
	public $hour;
	public $minute;
	public $second;
	public $defaultFormat=11;
	const LANG_DFORMAT=10;
	const LANG_DTFORMAT=11;
	const LANG_TFORMAT=12;
	const LANG_SHORT_DTFORMAT=13;
	const LANG_SHORT_TFORMAT=14;
	const DB_DFORMAT=20;
	const DB_DTFORMAT=21;
	const DB_TFORMAT=22;
	const ISO8601_FORMAT=40;
	const TIMESTAMP_FORMAT=50;
	const RFC822_FORMAT=60;
	const RFC2822_FORMAT=61;
	const FULL_LANG_DATE=62;
	function __construct($year=0,$month=0,$day=0,$hour=0,$minute=0,$second=0){
		$this->year=$year;
		$this->month=$month;
		$this->day=$day;
		$this->hour=$hour;
		$this->minute=$minute;
		$this->second=$second;
		if(!$this->_check())
		{
		throw new jException('jelix~errors.datetime.invalid',
			array($this->year,$this->month,$this->day,
				$this->hour,$this->minute,$this->second));
		}
	}
	private function _check(){
		if($this->day > 0&&$this->month > 0&&$this->year > 0
			&&!checkdate($this->month,$this->day,$this->year))
		{
			return false;
		}
		if(!(($this->second>=0)&&($this->second < 60)
			&&($this->minute>=0)&&($this->minute < 60)
			&&($this->hour>=0)&&($this->hour < 24)))
		{
			return false;
		}
		return true;
	}
	private function _createDateFromFormat($lf,$str){
		if($res=date_parse_from_format($lf,$str)){
			$this->year=$res['year'];
			$this->month=$res['month'];
			$this->day=$res['day'];
			$this->hour=$res['hour'];
			$this->minute=$res['minute'];
			$this->second=$res['second'];
			return true;
		}
		return false;
	}
	public function isNull(){
		return($this->year===0&&$this->month===0&&$this->day===0&&$this->hour==0&&$this->minute==0&&$this->second==0);
	}
	function toString($format=-1){
		if($format==-1)
			$format=$this->defaultFormat;
		$str='';
		switch($format){
			case self::LANG_DFORMAT:
				$t=mktime($this->hour,$this->minute,$this->second,$this->month,$this->day,$this->year);
				$lf=jLocale::get('jelix~format.date');
				$str=date($lf,$t);
				break;
			case self::LANG_DTFORMAT:
				$t=mktime($this->hour,$this->minute,$this->second,$this->month,$this->day,$this->year);
				$lf=jLocale::get('jelix~format.datetime');
				$str=date($lf,$t);
				break;
			case self::LANG_TFORMAT:
				$t=mktime($this->hour,$this->minute,$this->second,0,0,0);
				$lf=jLocale::get('jelix~format.time');
				$str=date($lf,$t);
				break;
			case self::LANG_SHORT_DTFORMAT:
				$t=mktime($this->hour,$this->minute,$this->second,$this->month,$this->day,$this->year);
				$lf=jLocale::get('jelix~format.short_datetime');
				$str=date($lf,$t);
				break;
			case self::LANG_SHORT_TFORMAT:
				$t=mktime($this->hour,$this->minute,$this->second,$this->month,$this->day,$this->year);
				$lf=jLocale::get('jelix~format.short_time');
				$str=date($lf,$t);
				break;
			case self::DB_DFORMAT:
				$str=sprintf('%04d-%02d-%02d',$this->year,$this->month,$this->day);
				break;
			case self::DB_DTFORMAT:
				$str=sprintf('%04d-%02d-%02d %02d:%02d:%02d',$this->year,$this->month,$this->day,$this->hour,$this->minute,$this->second);
				break;
			case self::DB_TFORMAT:
				$str=sprintf('%02d:%02d:%02d',$this->hour,$this->minute,$this->second);
				break;
			case self::ISO8601_FORMAT:
				$str=sprintf('%04d-%02d-%02dT%02d:%02d:%02dZ',$this->year,$this->month,$this->day,$this->hour,$this->minute,$this->second);
				break;
			case self::TIMESTAMP_FORMAT:
				$str=(string) mktime($this->hour,$this->minute,$this->second,$this->month,$this->day,$this->year);
				break;
			case self::RFC822_FORMAT:
			case self::RFC2822_FORMAT:
				$dt=new DateTime('now',new DateTimeZone('UTC'));
				$dt->setDate($this->year,$this->month,$this->day);
				$dt->setTime($this->hour,$this->minute,$this->second);
				$str=$dt->format('r');
				break;
			case self::FULL_LANG_DATE:
				$t=mktime($this->hour,$this->minute,$this->second,$this->month,$this->day,$this->year);
				$month=jLocale::get('jelix~date_time.month.'.date('m',$t).'.label');
				$day=jLocale::get('jelix~date_time.day.'.date('w',$t).'.label');
				$lf=jLocale::get('jelix~format.date_full');
				$ordinal=jLocale::get('jelix~date_time.day.'.$this->day.'.ordinal');
				$str=sprintf($lf,$day,$this->day,$ordinal,$month,$this->year);
				break;
			default:
				if(is_string($format)){
					$t=mktime($this->hour,$this->minute,$this->second,$this->month,$this->day,$this->year);
					$str=date($format,$t);
				}
		}
		return $str;
	}
	function setFromString($str,$format=-1){
		if($format==-1){
			$format=$this->defaultFormat;
		}
		$this->year=0;
		$this->month=0;
		$this->day=0;
		$this->hour=0;
		$this->minute=0;
		$this->second=0;
		$ok=false;
		switch($format){
			case self::LANG_DFORMAT:
				$lf=jLocale::get('jelix~format.date');
				$ok=$this->_createDateFromFormat($lf,$str);
				break;
			case self::LANG_DTFORMAT:
				$lf=jLocale::get('jelix~format.datetime');
				$ok=$this->_createDateFromFormat($lf,$str);
				break;
			case self::LANG_TFORMAT:
				$lf=jLocale::get('jelix~format.time');
				$ok=$this->_createDateFromFormat($lf,$str);
				break;
			case self::LANG_SHORT_TFORMAT:
				$lf=jLocale::get('jelix~format.short_time');
				$ok=$this->_createDateFromFormat($lf,$str);
				break;
			case self::LANG_SHORT_DTFORMAT:
				$lf=jLocale::get('jelix~format.short_datetime');
				$ok=$this->_createDateFromFormat($lf,$str);
				break;
			case self::DB_DFORMAT:
				$ok=$this->_createDateFromFormat("Y-m-d",$str);
				break;
			case self::DB_DTFORMAT:
				$ok=$this->_createDateFromFormat("Y-m-d H:i:s",$str);
				break;
			case self::DB_TFORMAT:
				$ok=$this->_createDateFromFormat("H:i:s",$str);
				break;
			case self::ISO8601_FORMAT:
				if($ok=preg_match('/^(\d{4})(?:\-(\d{2})(?:\-(\d{2})(?:T(\d{2}):(\d{2})(?::(\d{2})(?:\.(\d{2,3}))?)?(Z|([+\-])(\d{2}):(\d{2})))?)?)?$/',$str,$match)){
					$c=count($match)-1;
					$this->year=intval($match[1]);
					if($c<2)break;
					$this->month=intval($match[2]);
					if($c<3)break;
					$this->day=intval($match[3]);
					if($c<4)break;
					$this->hour=intval($match[4]);
					$this->minute=intval($match[5]);
					if($match[6]!='')$this->second=intval($match[6]);
					if($match[8]!='Z'){
						$d=new jDuration(array('hour'=>$match[10],'minute'=>$match[11]));
						if($match[9]=='+')
							$this->sub($d);
						else
							$this->add($d);
					}
				}
				break;
			case self::TIMESTAMP_FORMAT:
				$ok=true;
				$t=getdate(intval($str));
				$this->year=$t['year'];
				$this->month=$t['mon'];
				$this->day=$t['mday'];
				$this->hour=$t['hours'];
				$this->minute=$t['minutes'];
				$this->second=$t['seconds'];
				break;
			case self::RFC822_FORMAT:
			case self::RFC2822_FORMAT:
				$dt=new DateTime($str);
				$dt=$dt->setTimezone(new DateTimeZone('UTC'));
				$this->year=intval($dt->format('Y'));
				$this->month=intval($dt->format('m'));
				$this->day=intval($dt->format('d'));
				$this->hour=intval($dt->format('H'));
				$this->minute=intval($dt->format('i'));
				$this->second=intval($dt->format('s'));
				break;
			default:
				if(is_string($format)){
					$ok=$this->_createDateFromFormat($format,$str);
				}
		}
		return $ok&&$this->_check();
	}
	public function add($year,$month=0,$day=0,$hour=0,$minute=0,$second=0){
		if($year instanceof jDuration){
			$dt=$year;
		}else{
			$dt=new jDuration(array("year"=>$year,"month"=>$month,
				"day"=>$day,"hour"=>$hour,"minute"=>$minute,
				"second"=>$second));
		}
		$t=mktime($this->hour,$this->minute,$this->second + $dt->seconds,
			$this->month + $dt->months,$this->day + $dt->days,$this->year);
		$t=getdate($t);
		$this->year=$t['year'];
		$this->month=$t['mon'];
		$this->day=$t['mday'];
		$this->hour=$t['hours'];
		$this->minute=$t['minutes'];
		$this->second=$t['seconds'];
	}
	public function sub($year,$month=0,$day=0,$hour=0,$minute=0,$second=0){
		if($year instanceof jDuration){
			$dt=$year;
		}else{
			$dt=new jDuration(array("year"=>$year,"month"=>$month,
				"day"=>$day,"hour"=>$hour,"minute"=>$minute,
				"second"=>$second));
		}
		$dt->mult(-1);
		$this->add($dt);
	}
	public function durationTo($dt,$absolute=true){
		if($absolute){
			$t=gmmktime($dt->hour,$dt->minute,$dt->second,
				$dt->month,$dt->day,$dt->year)
				- gmmktime($this->hour,$this->minute,$this->second,
					$this->month,$this->day,$this->year);
			return new jDuration($t);
		}
		else{
			return new jDuration(array(
				"year"=>$dt->year - $this->year,
				"month"=>$dt->month - $this->month,
				"day"=>$dt->day - $this->day,
				"hour"=>$dt->hour - $this->hour,
				"minute"=>$dt->minute - $this->minute,
				"second"=>$dt->second - $this->second
			));
		}
	}
	public function compareTo($dt){
		$fields=array('year','month','day','hour','minute','second');
		foreach($fields as $field){
			if($dt->$field > $this->$field)
				return -1;
			if($dt->$field < $this->$field)
				return 1;
		}
		return 0;
	}
	public function now(){
		$this->year=intval(date('Y'));
		$this->month=intval(date('m'));
		$this->day=intval(date('d'));
		$this->hour=intval(date('H'));
		$this->minute=intval(date('i'));
		$this->second=intval(date('s'));
	}
	public function substract($date=null){
		if(!$date){
			$date=new jDateTime();
			$date->now();
		}
		$newDate=new jDateTime();
		$items=array(
				'second',
				'minute',
				'hour',
				'day',
				'month',
				'year'
			);
		foreach($items as $k=>$i){
			$newDate->{$i}=$date->{$i}- $this->{$i};
			if($newDate->{$i}< 0){
				switch($i){
					case 'second':
					case 'minute':
						$sub=60;
						break;
					case 'hour':
						$sub=24;
						break;
					case 'day':
						switch($this->month){
							case 1:
							case 3:
							case 5:
							case 7:
							case 8:
							case 10:
							case 12:
								$sub=31;
								break;
							case 4:
							case 6:
							case 9:
							case 11:
								$sub=30;
								break;
							case 2:
								if($this->year % 4==0 and
										!(
												$this->year % 100==0 and
												$this->year % 400!=0
										)){
									$sub=29;
								}
								else{
									$sub=28;
								}
								break;
						}
						break;
					case 'month':
						$sub=12;
						break;
					default:
						$sub=0;
				}
				$newDate->{$i}=abs($sub + $newDate->{$i});
				if(isset($items[$k+1])){
					$newDate->{$items[$k+1]}--;
				}
			}
		}
		return $newDate;
	}
}
