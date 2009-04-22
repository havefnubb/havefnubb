<?php
/**
* @package   havefnubb
* @subpackage hfnuadmin
* @author    FoxMaSk
* @copyright 2008 FoxMaSk
* @link      http://forge.jelix.org/projects/havefnubb
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/
// Class to parse the module.xml file
class modulexml {
    public static $ns     =  'jelixmodule';
    public static $nsURL  = 'http://jelix.org/ns/module/1.0'; 
    
    public static function parse($file) { 
        $moduleInfos = array(); 
                 
        $doc = new DOMDocument; 
        $doc->Load($file); 

        $xpath  = new DOMXPath($doc); 
        $xpath->registerNamespace(self::$ns,self::$nsURL); 
 
        $query = '//'.self::$ns.':info/@id'; 
        $entries = $xpath->query($query); 
         
        $moduleId =  $entries->item(0)->nodeValue; 
         
        $query = '//'.self::$ns.':info/@name'; 
        $entries = $xpath->query($query); 
         
        $moduleName =  $entries->item(0)->nodeValue; 
        
        $query = '//'.self::$ns.':version/@stability'; 
        $entries = $xpath->query($query); 
 
        $versionStability = $entries->item(0)->nodeValue; 
 
        $query = '//'.self::$ns.':version/text()'; 
        $entries = $xpath->query($query); 
         
        $versionNumber = $entries->item(0)->nodeValue; 
 
        $query = '//'.self::$ns.':label/text()'; 
        $entries = $xpath->query($query); 
         
        $label = $entries->item(0)->nodeValue; 
 
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
                die("fichier module.xml invalide"); 
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
        
        $notes = ''; 
        if ($entries->item(0) !== null)
            $notes = $entries->item(0)->nodeValue; 
             
        //@TODO : extend the properties of the array to all the 
                // existing nodes of the module.xml schema, eg: dependencies 
        $moduleInfos = array( 
                        'id'=>$moduleId, 
                        'version'=>$versionStability . ' ' . $versionNumber, 
                        'desc'=>$label, 
                        'creators'=>$creators, 
                        'notes'=>$notes); 
     
     
        return $moduleInfos; 
         
    }         
    
    public static function moduleInfo($file,$info) {

        self::$ns     =  'jelixmodule'; 
        self::$nsURL  = 'http://jelix.org/ns/module/1.0'; 
        
        $doc = new DOMDocument; 
        $doc->Load($file); 
 		                 
        $xpath  = new DOMXPath($doc); 
        $xpath->registerNamespace($ns,$nsURL); 
 		            
        $query = '//'.$ns.':'.$info; 
        $entries = $xpath->query($query); 
 		$value = $entries->item(0)->nodeValue; 
 		                 
 		return $value; 
    }
}