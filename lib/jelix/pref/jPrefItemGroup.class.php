<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package   jelix
* @subpackage pref
* @author    Florian Lonqueu-Brochard
* @copyright 2012 Florian Lonqueu-Brochard
* @link      http://jelix.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/
class jPrefItemGroup{
	public $id;
	public $locale;
	public $order;
	public $prefs=array();
	public function setFromIniNode($node_key,$node){
		$this->id=substr($node_key,6);
		if(!empty($node['locale']))
			$this->locale=$node['locale'];
		if(!empty($node['order']))
			$this->order=$node['order'];
	}
}
