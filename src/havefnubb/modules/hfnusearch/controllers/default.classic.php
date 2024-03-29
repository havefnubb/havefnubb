<?php
/**
* @package   havefnubb
* @subpackage hfnusearch
* @author    FoxMaSk
* @copyright 2008-2011 FoxMaSk
* @link      https://havefnubb.jelix.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/
/**
* Controller to manage the Searching requests
*/
class defaultCtrl extends jController {
    /**
     * @var plugins to manage the behavior of the controller
     */
    public $pluginParams = array(

        'index' => array(
            'auth.required'=>false,
            'banuser.check'=>true,
            'history.add'=>true,
            //'jacl2.right' =>'hfnu.search'
        ),
        'query' => array(
            'auth.required'=>false,
            'banuser.check'=>true,
            'history.add'=>true,
            //'jacl2.right' =>'hfnu.search'
        )
    );
    /**
     * Main page of search
     */
    public function index() {
        $rep = $this->getResponse('html');
        jApp::coord()->getPlugin('history')->change('label',jLocale::get('hfnusearch~search.search.perform'));
        $rep->title = jLocale::get('hfnusearch~search.search.perform');
        $rep->body->assignZone('MAIN', 'hfnusearch~hfnusearch');
        return $rep;
    }
    /**
     * Query
     */
    public function query() {
        $string = $this->param('hfnu_q');

        $additionnalParam = '';
        if ( $this->param('param') != '' )  {
            $additionnalParam = $this->param('param');
        }

        $HfnuSearchConfig  =  parse_ini_file(jApp::appSystemPath('havefnu.search.ini.php'), true);

        // get the list of authorized function we will find in the search_in "service" below
        $authorizedSearch = explode(',', $HfnuSearchConfig['perform_search_in']);

        if (! in_array($this->param('perform_search_in'),$authorizedSearch) or $string == '' or strlen($string) < 3) {
            jMessage::add(jLocale::get('hfnusearch~search.query.too.short'),'warning');
            $rep = $this->getResponse('redirect');
            $rep->action = 'hfnusearch~default:index';
            return $rep;
        }
        // let's build the appropriate service to call
        $searchIn = 'searchIn'.ucfirst($this->param('perform_search_in'));

        $page = 0;
        if ( $this->param('page') > 0 )
            $page = (int) $this->param('page');

        if ($page < 0) $page = 0;

        $resultsPerPage = (int) $HfnuSearchConfig['results_per_page'];

        $result = jClasses::getService('hfnusearch~search_in')->$searchIn($string,$additionnalParam,$page,$resultsPerPage);

        $count = $result['total'];

        if ($count == 0 ) {
            jMessage::add(jLocale::get('hfnusearch~search.no.result'),'ok');
            $rep = $this->getResponse('redirect');
            $rep->action = 'hfnusearch~default:index';
            return $rep;
        }
        $properties = array('start-label' => '',
                      'prev-label'  => '',
                      'next-label'  => '',
                      'end-label'   => jLocale::get("hfnusearch~search.pagelinks.end"),
                      'area-size'   => 5);
        $tpl = new jTpl();
        $tpl->assign('string',$string);
        $tpl->assign('count',$count);
        $tpl->assign('datas',$result['datas']);
        $tpl->assign('page',$page);
        $tpl->assign('resultsPerPage',$resultsPerPage);
        $tpl->assign('perform_search_in',$this->param('perform_search_in'));
        $tpl->assign('properties',$properties);
        $rep = $this->getResponse('html');
        $rep->title = jLocale::get('hfnusearch~search.results.of.search');
        $rep->body->assign('MAIN',$tpl->fetch('hfnusearch~result'));
        return $rep;
    }

}
