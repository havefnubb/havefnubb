<?php
/**
* @package     jelix
* @subpackage  utils
* @author      Gérald Croes, Laurent Jouanneau
* @contributor Laurent Jouanneau, Julien Issler
* @contributor Loic Mathaud
* @contributor Florian Hatat
* @copyright   2005-2008 Laurent Jouanneau
* @copyright   2007 Loic Mathaud
* @copyright   2007-2008 Florian Hatat
* @copyright   2001-2005 CopixTeam, GeraldCroes, Laurent Jouanneau
* @copyright   2008 Julien Issler
*
* This class was get originally from the Copix project (CopixDate.lib.php, Copix 2.3dev20050901, http://www.copix.org)
* Only few lines of code are still copyrighted 2001-2005 CopixTeam (LGPL licence).
* Initial authors of this Copix classes are Gerald Croes and Laurent Jouanneau,
* and this class was adapted/improved for Jelix by Laurent Jouanneau
*
* @link        http://www.jelix.org
* @licence     http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

#if PHP50
if(!function_exists('strptime')){ // existe depuis php 5.1
    /**
     * @ignore
     */
    function strptime ( $strdate, $format ){
        // c'est pas une compatibilité 100% avec strptime de PHP 5.1 mais c'est suffisant pour nos besoins
        $plop = array( 'S'=>'tm_sec', 'M'=>'tm_min', 'H'=>'tm_hour',
            'd'=>'tm_mday', 'm'=>'tm_mon', 'Y'=>'tm_year');

        $regexp = preg_quote($format, '/');
        $regexp = str_replace(
                array('%d','%m','%Y','%H','%M','%S'),
                array('(?P<tm_mday>\d{2})','(?P<tm_mon>\d{2})',
                      '(?P<tm_year>\d{4})','(?P<tm_hour>\d{2})',
                      '(?P<tm_min>\d{2})','(?P<tm_sec>\d{2})'),
                $regexp);
        if(preg_match('/^'.$regexp.'$/', $strdate, $m)){
            $result=array('tm_sec'=>0,'tm_min'=>0,'tm_hour'=>0,'tm_mday'=>0,'tm_mon'=>0,'tm_year'=>0,'tm_wday'=>0,'tm_yday'=>0,'unparsed'=>'');
            foreach($m as $key => $value){
                if(!isset($result[$key])){
                    continue;
                }
                $result[$key] = intval($value);
                switch($key){
                case 'tm_mon':
                    $result[$key]--;
                    break;
                case 'tm_year':
                    $result[$key] -= 1900;
                    break;
                default:
                    break;
                }
            }
            return $result;
        }
        return false;
    }
}
#endif

/**
 * Utility to manipulate dates and convert date format
 * @package     jelix
 * @subpackage  utils
 */
class jDateTime {
    public $day;
    public $month;
    public $year;
    public $hour;
    public $minute;
    public $second;

    public $defaultFormat = 11;

    const LANG_DFORMAT=10;
    const LANG_DTFORMAT=11;
    const LANG_TFORMAT=12;
    const DB_DFORMAT=20;
    const DB_DTFORMAT=21;
    const DB_TFORMAT=22;
    const ISO8601_FORMAT=40;
    const TIMESTAMP_FORMAT=50;
    const RFC822_FORMAT=60;
    const RFC2822_FORMAT=61;

    /**#@+
     * use DB_* consts instead
     * @deprecated
     */
    const BD_DFORMAT=20;
    const BD_DTFORMAT=21;
    const BD_TFORMAT=22;
    /**#@-*/

    /**
     *
     */
    function __construct($year=0, $month=0, $day=0, $hour=0, $minute=0, $second=0){
        $this->year = $year;
        $this->month = $month;
        $this->day = $day;
        $this->hour = $hour;
        $this->minute = $minute;
        $this->second = $second;

        if(!$this->_check())
        {
          throw new jException('jelix~errors.datetime.invalid',
              array($this->year, $this->month, $this->day,
                $this->hour, $this->minute, $this->second));
        }
    }

    /**
     * checks if the current jDateTime object is a valid gregorian date/time
     * @return bool true if the date/time are valid.
     */
    private function _check() {
        // Only check the date if it is defined (eg. day, month and year are 
        // strictly positive).
        if($this->day > 0 && $this->month > 0 && $this->year > 0
            && !checkdate($this->month, $this->day, $this->year))
        {
            return false;
        }
        if(!(($this->second >= 0) && ($this->second < 60)
            && ($this->minute >= 0) && ($this->minute < 60)
            && ($this->hour >= 0) && ($this->hour < 24)))
        {
            return false;
        }
        return true;
    }

    /**
     * convert the date to a string format
     * @param int $format one of the class constant xxx_FORMAT, or -1 if it should use the default format
     * @return string the string date
     * @see jDateTime:$defaultFormat
     */
    function toString($format=-1){
        if($format==-1)
            $format = $this->defaultFormat;

        $str='';
        switch($format){
           case self::LANG_DFORMAT:
               $t = mktime ( $this->hour, $this->minute,$this->second , $this->month, $this->day, $this->year );
               $lf = jLocale::get('jelix~format.date');
               $str = date($lf, $t);
               break;
           case self::LANG_DTFORMAT:
               $t = mktime ( $this->hour, $this->minute,$this->second , $this->month, $this->day, $this->year );
               $lf = jLocale::get('jelix~format.datetime');
               $str = date($lf, $t);
               break;
           case self::LANG_TFORMAT:
               $t = mktime ( $this->hour, $this->minute,$this->second , 0 , 0, 0 );
               $lf = jLocale::get('jelix~format.time');
               $str = date($lf, $t);
               break;
           case self::DB_DFORMAT:
           case self::BD_DFORMAT:
               $str = sprintf('%04d-%02d-%02d', $this->year, $this->month, $this->day);
               break;
           case self::DB_DTFORMAT:
           case self::BD_DTFORMAT:
               $str = sprintf('%04d-%02d-%02d %02d:%02d:%02d', $this->year, $this->month, $this->day, $this->hour, $this->minute, $this->second);
               break;
           case self::DB_TFORMAT:
           case self::BD_TFORMAT:
               $str = sprintf('%02d:%02d:%02d', $this->hour, $this->minute, $this->second);
               break;
           case self::ISO8601_FORMAT:
               $str = sprintf('%04d-%02d-%02dT%02d:%02d:%02dZ', $this->year, $this->month, $this->day, $this->hour, $this->minute, $this->second);
               break;
           case self::TIMESTAMP_FORMAT:
               $str =(string) mktime ( $this->hour, $this->minute,$this->second , $this->month, $this->day, $this->year );
               break;
           case self::RFC822_FORMAT:
           case self::RFC2822_FORMAT:
               $str = date('r', mktime ( $this->hour, $this->minute,$this->second , $this->month, $this->day, $this->year ));
               break;
        }
       return $str;
    }

    /**
     * read a string to extract date values
     * @param string $str the string date
     * @param int $format one of the class constant xxx_FORMAT, or -1 if it should use the default format
     * @see jDateTime:$defaultFormat
     */
    function setFromString($str,$format=-1){
        if($format==-1){
            $format = $this->defaultFormat;
        }
        $this->year = 0;
        $this->month = 0;
        $this->day = 0;
        $this->hour = 0;
        $this->minute = 0;
        $this->second = 0;
        $ok=false;

        switch($format){
           case self::LANG_DFORMAT:
               $lf = jLocale::get('jelix~format.date_st');
               if($res = strptime ( $str, $lf )){
                   $ok=true;
                   $this->year = $res['tm_year']+1900;
                   $this->month = $res['tm_mon'] +1;
                   $this->day = $res['tm_mday'];
               }
               break;
           case self::LANG_DTFORMAT:
               $lf = jLocale::get('jelix~format.datetime_st');
               if($res = strptime ( $str, $lf )){
                   $ok=true;
                   $this->year = $res['tm_year'] + 1900;
                   $this->month = $res['tm_mon'] + 1;
                   $this->day = $res['tm_mday'];
                   $this->hour = $res['tm_hour'];
                   $this->minute = $res['tm_min'];
                   $this->second = $res['tm_sec'];
               }
               break;
           case self::LANG_TFORMAT:
               $lf = jLocale::get('jelix~format.time_st');
               if($res = strptime ( $str, $lf )){
                   $ok=true;
                   $this->hour = $res['tm_hour'];
                   $this->minute = $res['tm_min'];
                   $this->second = $res['tm_sec'];
               }
               break;
           case self::DB_DFORMAT:
           case self::BD_DFORMAT:
               if($res = strptime( $str, "%Y-%m-%d" )){
                   $ok=true;
                   $this->year = $res['tm_year'] + 1900;
                   $this->month = $res['tm_mon'] + 1;
                   $this->day = $res['tm_mday'];
               }
               break;
           case self::DB_DTFORMAT:
           case self::BD_DTFORMAT:
               if($res = strptime( $str, "%Y-%m-%d %H:%M:%S" )){
                   $ok=true;
                   $this->year = $res['tm_year'] + 1900;
                   $this->month = $res['tm_mon'] + 1;
                   $this->day = $res['tm_mday'];
                   $this->hour = $res['tm_hour'];
                   $this->minute = $res['tm_min'];
                   $this->second = $res['tm_sec'];
               }
               break;
           case self::DB_TFORMAT:
           case self::BD_TFORMAT:
               if($res = strptime( $str, "%H:%M:%S" )){
                   $ok=true;
                   $this->hour = $res['tm_hour'];
                   $this->minute = $res['tm_min'];
                   $this->second = $res['tm_sec'];
               }
               break;
           case self::ISO8601_FORMAT:
               if($ok=preg_match('/^(\d{4})(?:\-(\d{2})(?:\-(\d{2})(?:T(\d{2}):(\d{2})(?::(\d{2})(?:\.(\d{2}))?)?(Z|[+\-]\d{2}:\d{2}))?)?)?$/', $str, $match)){
                    $c = count($match)-1;
                    $this->year = $match[1];
                    if($c<2) break;
                    $this->month = $match[2];
                    if($c<3) break;
                    $this->day = $match[3];
                    if($c<4) break;
                    $this->hour = $match[4];
                    $this->minute = $match[5];
                    if($match[6] != '') $this->second = $match[6];
                    if($match[8] != 'Z'){
                        $d = new jDuration(array('hour'=>$match[10],'minute'=>$match[11]));
                        if($match[9] == '+')
                            $this->add($d);
                        else
                            $this->sub($d);
                    }
               }
               break;
           case self::TIMESTAMP_FORMAT:
               $ok=true;
               $t = getdate ( intval($str) );
               $this->year = $t['year'];
               $this->month = $t['mon'];
               $this->day = $t['mday'];
               $this->hour = $t['hours'];
               $this->minute = $t['minutes'];
               $this->second = $t['seconds'];
               break;
           case self::RFC822_FORMAT:
           case self::RFC2822_FORMAT:
               // Note the "x" modifier, otherwise the pattern would look like 
               // obfuscated code.
               $regexp = "/^
                     (?: (?P<nday> Mon | Tue | Wed | Thu | Fri | Sat | Sun) , )? \s+
                     (?P<day>\d{1,2}) \s+
                     (?P<nmonth> Jan | Feb | Mar | Apr | May | Jun |
                               Jul | Aug | Sep | Oct | Nov | Dec) \s+
                     (?P<year>\d{4}) \s+
                     (?P<hour>\d{2}) : (?P<minute>\d{2}) (?: : (?P<second>\d{2}))? \s+
                     (?P<tzsign>[+-]) (?P<tzhour>\d{2}) (?P<tzminute>\d{2})$/x";

               $english_months = array("Jan", "Feb", "Mar", "Apr", "May", "Jun",
                   "Jul", "Aug", "Sep", "Oct", "Nov", "Dec");

               $match = array("year" => 0, "month" => 0, "day" => 0,
                   "hour" => 0, "minute" => 0, "second" => 0, "tzsign" => "+",
                   "tzhour" => 0, "tzminute" => 0);

               if($ok = preg_match($regexp, $str, $match)){
                   $this->year = intval($match['year']);
                   $this->month = array_search($match['nmonth'], $english_months) + 1;
                   $this->day = intval($match['day']);
                   $this->hour = intval($match['hour']);
                   $this->minute = intval($match['minute']);
                   $this->second = intval($match['second']);

                   # Adjust according to the timezone, so that the stored time 
                   # corresponds to UTC.
                   $tz = new jDuration(array('hour'=>intval($match['tzhour']),
                       'minute'=>intval($match['tzminute'])));
                   if($match['tzsign'] == '+'){
                       $this->sub($tz);
                   }
                   else{
                       $this->add($tz);
                   }
               }
               break;
        }

        return $ok && $this->_check();
    }

    /**
     * Add a duration to the date.
     * You can specify the duration in a jDuration object or give each value of
     * the duration.
     * @param jDuration/int $year the duration value or a year with 4 digits
     * @param int $month month with 2 digits
     * @param int $day day with 2 digits
     * @param int $hour hour with 2 digits
     * @param int $minute minute with 2 digits
     * @param int $second second with 2 digits
     */
    public function add($year, $month=0, $day=0, $hour=0, $minute=0, $second=0) {
        if ($year instanceof jDuration) {
            $dt = $year;
        } else {
            $dt = new jDuration(array("year" => $year, "month" => $month,
                "day" => $day, "hour" => $hour, "minute" => $minute,
                "second" => $second));
        }
        $t = mktime($this->hour, $this->minute, $this->second + $dt->seconds,
             $this->month + $dt->months, $this->day + $dt->days, $this->year);

        $t = getdate ($t);
        $this->year = $t['year'];
        $this->month = $t['mon'];
        $this->day = $t['mday'];
        $this->hour = $t['hours'];
        $this->minute = $t['minutes'];
        $this->second = $t['seconds'];
    }

    /**
     * substract a <b>duration</b> to the date
     * You can specify the duration in a jDuration object or give each value of
     * the duration.
     * @param jDuration/int $year the duration value or a year with 4 digits
     * @param int $month month with 2 digits
     * @param int $day day with 2 digits
     * @param int $hour hour with 2 digits
     * @param int $minute minute with 2 digits
     * @param int $second second with 2 digits
     */
    public function sub($year, $month=0, $day=0, $hour=0, $minute=0, $second=0) {
        if ($year instanceof jDuration) {
            $dt = $year;
        } else {
            $dt = new jDuration(array("year" => $year, "month" => $month,
                "day" => $day, "hour" => $hour, "minute" => $minute,
                "second" => $second));
        }
        $dt->mult(-1);
        $this->add($dt);
    }

    /**
     * to know the duration between two dates.
     * @param jDateTime $dt  the date on which a sub will be made with the date on the current object
     * @param bool $absolute 
     * @return jDuration a jDuration object
     */
    public function durationTo($dt, $absolute=true){
        if($absolute){
            $t = mktime($dt->hour, $dt->minute, $dt->second,
                $dt->month, $dt->day, $dt->year)
                - mktime($this->hour, $this->minute, $this->second,
                    $this->month, $this->day, $this->year);
            return new jDuration($t);
        }
        else{
            return new jDuration(array(
                "year" => $dt->year - $this->year,
                "month" => $dt->month - $this->month,
                "day" => $dt->day - $this-> day,
                "hour" => $dt->hour - $this->hour,
                "minute" => $dt->minute - $this->minute,
                "second" => $dt->second - $this->second
            ));
        }
    }

    /**
     * compare two date
     * @param jDateTime $dt the date to compare
     * @return integer -1 if $dt > $this, 0 if $dt = $this, 1 if $dt < $this
     */
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

    /**
    * set date to current datetime
    */
    public function now() {
        $this->year = date('Y');
        $this->month = date('m');
        $this->day = date('d');
        $this->hour = date('H');
        $this->minute = date('i');
        $this->second = date('s');
    }
}
