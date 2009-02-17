<?php
/**
* @package   havefnubb
* @subpackage hfnusearch
* @author    FoxMaSk
* @copyright 2008 FoxMaSk
* @link      http://forge.jelix.org/projects/havefnubb
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

class Cleaner {

	function removeStopwords($words) {
		global $gJConfig;

		$stopwords = (array) @file(dirname(__FILE__).'/'.$gJConfig->locale.'/'.'stopwords.txt');
		$stopwords = array_map('trim', $stopwords);
		
		return array_diff($words, $stopwords);
		

	}
	
}