<?php
/**
* @package   havefnubb
* @subpackage havefnubb
* @author    FoxMaSk
* @copyright 2008-2011 FoxMaSk
* @link      http://havefnubb.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

class serverinfoData {
    public $id = '';
    public $label = '';
    public $content = '';
    function __construct($id, $label, $content) {
        $this->id = $id;
        $this->label = $label;
        $this->content = $content;
    }
}


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

        $srvinfos = jClasses::getService("servinfo~serverinfos");

        list($records,$size) = $srvinfos->dbSize();

        $this->_tpl->assign('LOADS_AVG',$srvinfos->loadsAvg());
        $this->_tpl->assign('CACHE_ENGINE',$srvinfos->cacheEngine());
        $this->_tpl->assign('PHP_VERSION',phpversion());
        $this->_tpl->assign('PHP_OS',PHP_OS);
        $this->_tpl->assign('DB_VERSION',$srvinfos->dbVersion());
        $this->_tpl->assign('DB_SIZE',$size);
        $this->_tpl->assign('DB_RECORDS',$records);

        $this->_tpl->assign('otherInfos',jEvent::notify('servinfoGetInfo')->getResponse());
    }
}
