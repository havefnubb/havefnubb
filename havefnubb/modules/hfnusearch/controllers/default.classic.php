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

        $cleaner = jClasses::getService('hfnusearch~cleaner');
                      
        $string = $cleaner->removeStopwords($string);

        $dao = jDao::get('havefnubb~posts');
        $conditions = jDao::createConditions();                
        $conditions->startGroup('OR');
        
        $words = str_word_count($string,1);
        foreach ( $words as $val ) {            
            $conditions->addCondition('subject','like','%'.$val.'%');
            $conditions->addCondition('message','like','%'.$val.'%');                            
        }
        
        $conditions->endGroup();
        
        $datas = $dao->findBy($conditions);
        $count = $datas->rowCount();
        
        $tpl = new jTpl();
        $tpl->assign('count',$count);
        $tpl->assign('datas',$datas);
        $rep = $this->getResponse('html');
        $rep->body->assign('MAIN',$tpl->fetch('hfnusearch~result'));
        return $rep;
    }
}

