<?php
/**
* @package   havefnubb
* @subpackage hfnusearch
* @author    FoxMaSk
 * @contributor Laurent Jouanneau
 * @copyright 2008-2011 FoxMaSk, 2019 Laurent Jouanneau
* @link      https://havefnubb.jelix.org
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
    public function removeStopwords($words) {

        $stopwords = (array) @file(__DIR__.'/'.jApp::config()->locale.'/'.'stopwords.txt');
        $stopwords = array_map('trim', $stopwords);

        return array_diff($words, $stopwords);

    }
    /**
     *
     */
    public function stemPhrase($phrase)
    {
        // split into words
        $words = str_word_count(strtolower($phrase), 1);

        // ignore stop words
        $words = $this->removeStopwords($words);

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
