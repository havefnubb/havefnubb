<?php
/**
* @package   havefnubb
* @subpackage havefnubb
* @author    FoxMaSk
* @copyright 2008 FoxMaSk
* @link      http://forge.jelix.org/projects/havefnubb
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

class server_infoZone extends jZone {
    protected $_tplname='zone.server_info';

    protected function _prepareTpl(){
		
		$srvinfos = jClasses::getService("hfnuadmin~serverinfos");

		$this->_tpl->assign('LOADS_AVG',$srvinfos->loadsAvg());
		$this->_tpl->assign('CACHE_ENGINE',$srvinfos->cacheEngine());
		$this->_tpl->assign('PHP_VERSION',phpversion());
		$this->_tpl->assign('PHP_OS',PHP_OS);
		$this->_tpl->assign('DB_VERSION',$srvinfos->dbVersion());
		$this->_tpl->assign('DB_SIZE',$srvinfos->dbSize());
		$this->_tpl->assign('DB_RECORDS',$srvinfos->dbRecords());

    }
}