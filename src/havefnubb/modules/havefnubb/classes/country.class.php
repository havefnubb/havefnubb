<?php
/**
 * @package   havefnubb
 * @subpackage havefnubb
 * @author    FoxMaSk
 * @contributor Laurent Jouanneau
 * @copyright 2008-2011 FoxMaSk, 2019 Laurent Jouanneau
 * @link      https://havefnubb.jelix.org
 * @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
 */
/**
 * classes that reads the ISO country file
 */
class country {
    /**
     * get the list of all ISO countries code
     * @return array $data return an array of ISO country code
     */
    public function getCountries() {

        $supportedLanguage = array('fr','en');
        //get the member language
        if ( array_key_exists('JX_LANG',$_SESSION))
            $language = preg_split('/_/',$_SESSION['JX_LANG']);
        else
            $language = preg_split('/_/', jApp::config()->locale);

        $data = array();

        if (! in_array($language[0], $supportedLanguage)) return $data;

        $fh = @fopen (__DIR__.'/iso_3166-1_list_'.$language[0].'.txt','r');
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

    /**
     * get the name of a given country code
     * @param string $code of the country to get its name
     */
    public function getCountryName($code) {
        $data = $this->getCountries();
        return $data[$code];
    }
}
