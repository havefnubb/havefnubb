<?php
/**
* @package   havefnubb
* @subpackage hfnuadmin
* @author    FoxMaSk
* @copyright 2008 FoxMaSk
* @link      http://havefnubb.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

// class that list the themes directory.
class themes 
{
    public static $ns     =  'jelixtheme';
    public static $nsURL  = 'http://jelix.org/ns/theme/1.0';
    
    static function lists() {
        global $gJConfig;
        $themes = array();

        $dir = new DirectoryIterator(HFNU_APP_THEME_PATH);
        foreach ($dir as $dirContent) {
            if ($dirContent->isDir() and $dirContent != '.' and $dirContent != '..') 
                $themes[] = self::readManifest($dirContent->getFilename());
        }
        return $themes;
    }

    static function readManifest($theme) {
        
        global $gJConfig;
        
        $themesInfo = array(); 
                 
        $doc = new DOMDocument; 
        $doc->Load(HFNU_APP_THEME_PATH.$theme .'/theme.xml'); 
        
        $xpath  = new DOMXPath($doc); 
        $xpath->registerNamespace(self::$ns,self::$nsURL); 
 
        $query = '//'.self::$ns.':info/@id'; 
        $entries = $xpath->query($query);          
        $themeId =  $entries->item(0)->nodeValue; 
         
        $query = '//'.self::$ns.':info/@name'; 
        $entries = $xpath->query($query); 
        $themeName =  $entries->item(0)->nodeValue; 

        $query = '//'.self::$ns.':info/@createdate'; 
        $entries = $xpath->query($query); 
        $themeDateCreated =  $entries->item(0)->nodeValue; 
    
        $query = '//'.self::$ns.':version/@stability'; 
        $entries = $xpath->query($query);  
        $versionStability = $entries->item(0)->nodeValue; 
 
        $query = '//'.self::$ns.':version/text()'; 
        $entries = $xpath->query($query);          
        $versionNumber = $entries->item(0)->nodeValue; 
 
        $label = 'N/A';
        $query = '//'.self::$ns.":label[@lang='".$gJConfig->locale."']/text()";
        $entries = $xpath->query($query); 
        if ( ! is_null($entries->item(0))) 
            $label = $entries->item(0)->nodeValue; 

        $desc = 'N/A';
        $query = '//'.self::$ns.":description[@lang='".$gJConfig->locale."']/text()";
        $entries = $xpath->query($query);
        if ( ! is_null($entries->item(0)))  
            $desc = $entries->item(0)->nodeValue; 
        
        $creators = array(); 
        $query = '//'.self::$ns.':creator'; 
         
        $entries = $xpath->query($query); 
 
        foreach ($entries as $entry) { 
            $creatorName = ''; 
            $creatorNickname = ''; 
            $creatorEmail = ''; 
            $creatorActive = ''; 
            if ($entry->hasAttribute('name')) 
                $creatorName = $entry->getAttribute('name'); 
            else { 
                die("fichier theme.xml invalide"); 
            } 
            if ($entry->hasAttribute('nickname')) 
                $creatorNickname = $entry->getAttribute('nickname'); 
            if ($entry->hasAttribute('email')) 
                $creatorEmail = $entry->getAttribute('email');

            if ($entry->hasAttribute('active')) 
                $creatorActive = $entry->getAttribute('active'); 
                 
            $creators[] = array('name'=>$creatorName, 
                                'nickname'=>$creatorNickname, 
                                'email'=>$creatorEmail, 
                                'active'=>$creatorActive); 
        } 
        
        $query = '//'.self::$ns.':notes'; 
        $entries = $xpath->query($query);
        $notes = 'N/A';
        if ( ! is_null($entries->item(0))) 
            $notes = $entries->item(0)->nodeValue; 

        $updateURL = '';
        $query = '//'.self::$ns.':updateURL/text()'; 
        $entries = $xpath->query($query);
        $updateURL = $entries->item(0)->nodeValue;
        
        $homepageURL = '';
        $query = '//'.self::$ns.':homepageURL/text()'; 
        $entries = $xpath->query($query);
        $homepageURL = $entries->item(0)->nodeValue;        

        $licence = '';
        $query = '//'.self::$ns.':licence/text()'; 
        $entries = $xpath->query($query);
        $licence = $entries->item(0)->nodeValue;

        $licenceURL = '';
        $query = '//'.self::$ns.':licence/@URL'; 
        $entries = $xpath->query($query);
        $licenceURL = $entries->item(0)->nodeValue;


        $copyright = '';
        $query = '//'.self::$ns.':copyright/text()'; 
        $entries = $xpath->query($query);
        $copyright = $entries->item(0)->nodeValue;
        
        $themesInfo = array(
                        'name'=>strtolower($themeName),
                        'id'=>$themeId, 
                        'version'=>$versionStability . ' ' . $versionNumber,
                        'dateCreate'=>$themeDateCreated,
                        'label'=>$label, 
                        'desc'=>$desc, 
                        'creators'=>$creators, 
                        'updateURL'=>$updateURL,
                        'homepageURL'=>$homepageURL,
                        'licence'=>$licence,
                        'licenceURL'=>$licenceURL,
                        'copyright'=>$copyright
                        );      
     
        return $themesInfo; 
         
    }     
}