<?php
/**
 * @package   havefnubb
 * @subpackage havefnubb
 * @author    FoxMaSk
 * @copyright 2008-2011 FoxMaSk
 * @link      https://havefnubb.jelix.org
 * @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
 */
/**
 * class to manage menus.xml file and display the content inside of the other menu item
 */
/*
 * Menus class to build nav bar on public part
 * example of file
<?xml version="1.0" encoding="utf-8"?>
<menus>
    <menu>
        <name lang="fr_FR">Télécharger</name>
        <name lang="en_US">Download</name>
        <itemName>downloads</itemName>
        <url>/downloads</url>
        <order>1</order>
    </menu>
    <menu>
        <name lang="fr_FR">Aide</name>
        <name lang="en_US">Help</name>
        <itemName>help</itemName>
        <url>http://mydomain.com/help.php</url>
        <order>39</order>
    </menu>
</menus>
 */
class hfnumenusbar {
    /**
     * get the menus to be added inside the ohers menu item
     * @return $menus array of menus
     */
    public function getMenus() {
        $menus = array();

        if (file_exists(jApp::configPath().'/havefnubb/hfnumenus.xml')) {
            $doc = new DOMDocument();
            $doc->load(realpath(jApp::configPath()).'/havefnubb/hfnumenus.xml');
            $xpath  = new DOMXPath($doc);


            $query = '/menus/menu';

            $entries = $xpath->query($query);

            $gJConfig = jApp::config();
            foreach ($entries as $idx => $menu) {

                $queryName = '//name[@lang="'.$gJConfig->locale.'"]';
                $items = $xpath->query($queryName);
                $name =  $items->item($idx)->nodeValue;

                $queryItemName = '//menu/itemName';
                $items = $xpath->query($queryItemName);
                $itemName = $items->item($idx)->nodeValue;

                $queryUrl = '//menu/url';
                $items = $xpath->query($queryUrl);
                $url = $items->item($idx)->nodeValue;

                $queryOrder = '//menu/order';
                $items = $xpath->query($queryOrder);
                $order = $items->item($idx)->nodeValue;

                $menus[] = array('itemName'=>$itemName,
                                 'name' =>$name,
                                 'url'  =>$url,
                                 'order'=>$order);
            }
        }

        return $menus;
    }
}
