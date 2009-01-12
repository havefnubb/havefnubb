<?php
/**
* @package      jcommunity
* @subpackage   jmessenger
* @author       Bastien Jaillot <bastnicj@gmail.com>
* @contributor
* @copyright    2008 Bastien Jaillot
* @link         http://forge.jelix.org/projects/jcommunity
* @licence      http://www.gnu.org/licenses/gpl.html GNU General Public Licence, see LICENCE file
*/


class outboxZone extends jZone {

    protected $_tplname = "jmessenger~listmsg";
    protected $dao = "jmessenger~jmessenger";
    // protected $_useCache = true;
    protected $_tplOuputType = "html";


    protected function _prepareTpl(){
        $id = $this->getParam("id", jAuth::getUserSession()->id);
        $title = jLocale::get("jmessenger~message.msg.outbox");
        
        $dao = jDao::get($this->dao);
        $msg = $dao->getSend($id);
        $send = true;

        $this->_tpl->assign(compact('msg', 'id', 'title', 'send'));
    }
}
?>
