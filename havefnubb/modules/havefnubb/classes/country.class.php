<?php
/**
 * classes that read the ISO country file
 *
 * @package   havefnubb
 * @subpackage havefnubb
 * @author    FoxMaSk
 * @copyright 2008 FoxMaSk
 * @link      http://havefnubb.org
 * @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/
class country {
    //get the list of all countries
    public static function getCountries() {
        global $gJConfig;

        $supportedLanguage = array('fr','en');
        //get the member language
        if ( array_key_exists('JX_LANG',$_SESSION))
            $language = preg_split('/_/',$_SESSION['JX_LANG']);
        else
            $language = preg_split('/_/',$gJConfig->locale);

        $data = array();

        if (! in_array($language[0], $supportedLanguage)) return $data;

		$fh = @fopen (dirname(__FILE__).'/iso_3166-1_list_'.$language[0].'.txt','r');
        if ($fh) {
           while (!feof($fh)) {
                $buffer = utf8_encode(fgets($fh, 4096));
                if (strpos($buffer,';') > 0) {
                    list($countryName,$countryCode) = preg_split('/;/',$buffer);
                    $data[rtrim($countryCode)] = $countryName;
                }
           }
       }
       return $data;
    }

    //get the name of a given country code
    public static function getCountryName($code) {
        $data = self::getCountries();
        return $data[$code];
    }
}
?>
