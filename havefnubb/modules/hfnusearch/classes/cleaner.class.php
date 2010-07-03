<?php
/**
* @package   havefnubb
* @subpackage hfnusearch
* @author    FoxMaSk
* @copyright 2008 FoxMaSk
* @link      http://havefnubb.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/
/**
 * Class that cleans the search by removing unwanted words
 */
class Cleaner {
    /**
     * remove unuseful words for the index
     * @param array $words list of words to analyze
     * @return array without the stopwords
     */
    public static function removeStopwords($words) {
        global $gJConfig;

        $stopwords = (array) @file(dirname(__FILE__).'/'.$gJConfig->locale.'/'.'stopwords.txt');
        $stopwords = array_map('trim', $stopwords);

        return array_diff($words, $stopwords);

    }
    /**
     *
     */
    public static function stemPhrase($phrase)
    {
        // split into words
        $words = str_word_count(strtolower($phrase), 1);

        // ignore stop words
        $words = self::removeStopwords($words);

        // stem words
        $stemmedWords = array();
        foreach ($words as $word)
        {
          // ignore 1 and 2 letter words
          if (strlen($word) <= 2) 	{
            continue;
          }

          //$stem = jClasses::getService('hfnusearch~PorterStemmer');
          $stem = jClasses::getService('hfnusearch~hfnuStemmer');
          $stemmedWords[] = $stem->Stem($word, true);
        }

        return $stemmedWords;
    }


}
