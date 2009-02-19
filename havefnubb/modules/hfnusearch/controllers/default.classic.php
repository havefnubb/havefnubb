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
            //@TODO : display a page that tells to the user to wait a moment
            // thus, if the request take too many time :
            // 1) we avoid to see the user submit his request once again before the end of the first one
            // 2) we avoid to display a blank page during the waiting time.
            $rep = $this->getResponse('redirect');
            $rep->action = 'hfnusearch~default:submit_query';
            $rep->params = array('string'=>$string);
            return $rep;            
        }
    }
    
    function submit_query() {
        $string = $this->param('string');

        if ($string == '') {
            $rep = $this->getResponse('redirect');
            $rep->action = 'hfnusearch~default:index';
            return $rep;            
        }
        
        $eventresp = jEvent::notify('HfnuSearchEngineRun', array('string'=>$string) );
        
        $result = array();        
        foreach($eventresp->getResponse() as $rep){
            if(!isset($rep['SearchEngineResult']) )
                return false;
            else {
                $result[] = (array) $rep['SearchEngineResult'];
            }
        }

        $count = count($result);
        
        $tpl = new jTpl();
        $tpl->assign('count',$count);
        $tpl->assign('datas',$result);
        $rep = $this->getResponse('html');
        $rep->body->assign('MAIN',$tpl->fetch('hfnusearch~result'));
        return $rep;
    }
}

