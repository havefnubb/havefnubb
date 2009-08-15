<?php
/**
* @package   havefnubb
* @subpackage hfnusearch
* @author    FoxMaSk
* @copyright 2008 FoxMaSk
* @link      http://havefnubb.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

class defaultCtrl extends jController {
    /**
    *
    */
    public $pluginParams = array(
        'index' => array( 'jacl2.right' =>'hfnu.search'),
        'query' => array( 'jacl2.right' =>'hfnu.search'),
    
        '*'		=>	array('auth.required'=>false,
						  'hfnu.check.installed'=>true,
						  'banuser.check'=>true,
						  'history.add'=>true,
					),        
    );

    public function index() {
        $rep = $this->getResponse('html');	
		$rep->title = jLocale::get('hfnusearch~search.search.perform');		
        $rep->body->assignZone('MAIN', 'hfnusearch~hfnusearch');
        return $rep;
    }
    
    public function query() {
        $string = $this->param('hfnu_q');
        
        $additionnalParam = '';
        if ( $this->param('param') != '' )  {
            $additionnalParam = $this->param('param');
        }
        
        $HfnuSearchConfig  =  new jIniFileModifier(JELIX_APP_CONFIG_PATH.'havefnu.search.ini.php');
        
        // get the list of authorized function we will find in the search_in "service" below        
        $authorizedSearch = explode(',', $HfnuSearchConfig->getValue('perform_search_in'));
        
        if (! in_array($this->param('perform_search_in'),$authorizedSearch) or $string == '' or strlen($string) < 3) {
            jMessage::add(jLocale::get('hfnusearch~search.query.too.short'),'warning');
            $rep = $this->getResponse('redirect');
            $rep->action = 'hfnusearch~default:index';
            return $rep;
        }
        // let's build the appropriate service to call 
        $function = 'searchIn'.ucfirst($this->param('perform_search_in'));        
        
        $perform = jClasses::getService('hfnusearch~search_in');
        
        $result = $perform->$function($string,$additionnalParam);

        $count = count($result);
        
        if ($count == 0 ) {
            jMessage::add(jLocale::get('hfnusearch~search.no.result'),'ok');
            $rep = $this->getResponse('redirect');
            $rep->action = 'hfnusearch~default:index';
            return $rep;            
        }
        
        $tpl = new jTpl();
        $tpl->assign('count',$count);
        $tpl->assign('datas',$result);
        $rep = $this->getResponse('html');
		$rep->title = jLocale::get('hfnusearch~search.results.of.search');
        $rep->body->assign('MAIN',$tpl->fetch('hfnusearch~result'));
        return $rep;
    }
	
	public function queryajax () {
		$string = (string) $this->param('q');
        if ($string == '') return;
        $perform = jClasses::getService('hfnusearch~search_in');
        
        $result = $perform->searchInWords($string,$additionnalParam);

        $rep = $this->getResponse('htmlfragment');
		for ($i = 0 ; $i < count($result) ; $i++ ) {
			echo $result[$i]['subject']."\n";
		}

        return $rep;
	}
    
}

