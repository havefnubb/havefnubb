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
* Controller to manage the Searching requests
*/
class defaultCtrl extends jController {
	/**
	 * @var plugins to manage the behavior of the controller
	 */
	public $pluginParams = array(
		'index' => array( 'jacl2.right' =>'hfnu.search'),
		'query' => array( 'jacl2.right' =>'hfnu.search'),

		'index'	=>	array('auth.required'=>false,
						  'hfnu.check.installed'=>true,
						  'banuser.check'=>true,
						  'history.add'=>true,
					),
		'query'	=>	array('auth.required'=>false,
						  'hfnu.check.installed'=>true,
						  'banuser.check'=>true,
						  'history.add'=>true,
					),

	);
	/**
	 * Main page of search
	 */
	public function index() {
		$rep = $this->getResponse('html');
		$GLOBALS['gJCoord']->getPlugin('history')->change('label',jLocale::get('hfnusearch~search.search.perform'));
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

		$page = 0;
		if ( $this->param('page') > 0 )
			$page = (int) $this->param('page');

		if ($page < 0) $page = 0;

		$resultsPerPage = (int) $HfnuSearchConfig->getValue('results_per_page');

		$perform = jClasses::getService('hfnusearch~search_in');

		$result = $perform->$function($string,$additionnalParam,$page,$resultsPerPage);

		$count = $result['total'];

		if ($count == 0 ) {
			jMessage::add(jLocale::get('hfnusearch~search.no.result'),'ok');
			$rep = $this->getResponse('redirect');
			$rep->action = 'hfnusearch~default:index';
			return $rep;
		}
		$properties = array('start-label' => ' ',
					  'prev-label'  => '',
					  'next-label'  => '',
					  'end-label'   => jLocale::get("havefnubb~main.common.pagelinks.end"),
					  'area-size'   => 5);
		$tpl = new jTpl();
		$tpl->assign('string',$string);
		$tpl->assign('count',$count);
		$tpl->assign('datas',$result['datas']);
		$tpl->assign('page',$page);
		$tpl->assign('resultsPerPage',$resultsPerPage);
		$tpl->assign('properties',$properties);
		$rep = $this->getResponse('html');
		$rep->title = jLocale::get('hfnusearch~search.results.of.search');
		$rep->body->assign('MAIN',$tpl->fetch('hfnusearch~result'));
		return $rep;
	}
	/**
	 * Autocomplete Query
	 */

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
