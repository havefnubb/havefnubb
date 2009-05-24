<?php
/**
* @package      jcommunity
* @subpackage   
* @author       Laurent Jouanneau <laurent@xulfr.org>
* @contributor
* @copyright    2008 Laurent Jouanneau
* @link         http://jelix.org
* @licence      http://www.gnu.org/licenses/gpl.html GNU General Public Licence, see LICENCE file
*/


class loginZone extends jZone {

    protected $_tplname='login';

    protected function _prepareTpl(){
        if(jAuth::isConnected()) {
            $this->_tpl->assign('login',jAuth::getUserSession ()->login);
        }
        else {
            $conf = $GLOBALS['gJCoord']->getPlugin('auth')->config;
            $this->_tpl->assign('persistance_ok',$conf['persistant_enable']);
            $form = jForms::get("jcommunity~login");
            if (!$form)
                $form = jForms::create("jcommunity~login");
            $this->_tpl->assign('form',$form);

            if ($_SERVER['REQUEST_METHOD'] == 'GET' && $conf['enable_after_login_override']) {
                $req = $GLOBALS['gJCoord']->request;
                //(empty($_SERVER['HTTPS'])?'http':'https').'://'.$_SERVER["HTTP_HOST"].
                $url = $req->urlScript.$req->urlPathInfo;
                if(!empty($_SERVER['QUERY_STRING']))
                    $url.='?'.$_SERVER['QUERY_STRING'];
                $this->_tpl->assign('url_return',$url);
            }else
                $this->_tpl->assign('url_return','');
        }
    }
}

?>