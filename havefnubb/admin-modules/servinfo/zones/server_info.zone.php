<?php
/**
* @package   havefnubb
* @subpackage havefnubb
* @author    FoxMaSk
* @copyright 2008 FoxMaSk
* @link      http://havefnubb.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/
/**
 * Class that grabs info on the server where it runs
 */
class server_infoZone extends jZone {
    /**
     *@var string $_tplname the template name used by the zone
     */
    protected $_tplname='zone.server_info';
    /**
     * function to manage data before assigning to the template of its zone
     */
    protected function _prepareTpl(){
        $dao = jDao::get('havefnubb~member');
        $members = $dao->findAllConnected(time());
        $nbMembers = $members->rowCount();

        $srvinfos = jClasses::getService("servinfo~serverinfos");

        list($records,$size)=$srvinfos->dbSize();

        $this->_tpl->assign('LOADS_AVG',$srvinfos->loadsAvg());
        $this->_tpl->assign('CACHE_ENGINE',$srvinfos->cacheEngine());
        $this->_tpl->assign('PHP_VERSION',phpversion());
        $this->_tpl->assign('PHP_OS',PHP_OS);
        $this->_tpl->assign('DB_VERSION',$srvinfos->dbVersion());
        $this->_tpl->assign('DB_SIZE',$size);
        $this->_tpl->assign('DB_RECORDS',$records);
        $this->_tpl->assign('ONLINE_USERS',$nbMembers);
    }
}
