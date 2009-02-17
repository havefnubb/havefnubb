<?php
/**
* @package   havefnubb
* @subpackage hfnusearch
* @author    FoxMaSk
* @copyright 2008 FoxMaSk
* @link      http://forge.jelix.org/projects/havefnubb
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

class defaultCtrl extends jController {
    /**
    *
    */

    function index() {
        $rep = $this->getResponse('html');
        $tpl = new jTpl();
        $rep->body->assign('MAIN', $tpl->fetch('hfnusearch~search'));
        return $rep;
    }
    
    function query() {
        $string = $this->param('hfnu_q');
        if ($string == '') {
            $rep = $this->getResponse('redirect');
            $rep->action = 'hfnusearch~default:index';
            return $rep;
        } else {
            $rep = $this->getResponse('redirect');
            $rep->action = 'hfnusearch~default:submit_query';
            $rep->params = array('string'=>$string);
            return $rep;            
        }
    }
    
    function submit_query() {
        $string = $this->param('string');
        
        // split phrase in words
        $words = str_word_count(strtolower($string),1);
        $nb_words = count($words);
        // cleanning the phrase of unuseful words
        $cleaner = jClasses::getService('hfnusearch~cleaner');                      
        $words = $cleaner->removeStopwords($words);
        
        $words = array_map('stripslashes',$words);
   
        
        // watch in search_words ...
        $cnx = jDb::getConnection();
		// @TODO : 'JOIN' member table to know which member did the post
        $strQuery = 'SELECT DISTINCT id_post, COUNT(*) as nb, SUM(weight) as total_weight, subject, message FROM search_words';
        $strQuery .= ' LEFT JOIN posts ON posts.id_post = search_words.id ';
        $strQuery .= ' WHERE (';
        $counter=0;
        foreach ($words as $word) {
            if ($counter > 0 ) $strQuery .= ' OR ';
            $strQuery .= " words  = '". $word."'";
            $counter++;
        }
        
        $strQuery .= ') GROUP BY id_post ';

        // AND query?
        
        $exact = false;
        if ($exact)
        {
          $strQuery .= ' HAVING nb = '.$nb_words;
        }
       
        $strQuery .= ' ORDER BY nb DESC, total_weight DESC';        
        
        $rs = $cnx->query($strQuery);
        $result='';
        $count=0;
        while($record = $rs->fetch()){
		
            $result[][$record->subject] = $record->message;
            $count++;
        }        

        $tpl = new jTpl();
        $tpl->assign('count',$count);
        $tpl->assign('datas',$result);
        $rep = $this->getResponse('html');
        $rep->body->assign('MAIN',$tpl->fetch('hfnusearch~result'));
        return $rep;
    }
}

