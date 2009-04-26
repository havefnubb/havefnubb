<?php
/**
* @package      downloads
* @subpackage
* @author       foxmask
* @contributor foxmask
* @copyright    2008 foxmask
* @link
* @licence  http://www.gnu.org/licenses/gpl.html GNU General Public Licence, see LICENCE file
*/

class listZone extends jZone {

    protected $_tplname='public_list';

    protected function _prepareTpl(){

        $lastest = '';
        $popular = '';
        $total = 0;
        $enableDownloads = '';
        
        $offset = $this->getParam('offset', false);
        $dir = $this->getParam('dir', false);
        if (!$dir) return;

        jClasses::inc('download_config');
        $config = downloadConfig::getConfig();
        $limit = $config->getValue('number.downloads.on.home');
        
        if ($limit <= 0 ) $limit = 10;

        $dao = jDao::get('downloads~downloads');        
        // for RSS Feeds
        if ($config->getValue('most.popular.downloads.on.home') == 1)
            $popular = $dao->findPopular($dir,$limit);
        
        if ($config->getValue('last.downloads.on.home') == 1)        
            $lastest = $dao->findLastest($dir,$limit);
        
        if ($config->getValue('most.popular.downloads.on.home') == 0  and
            $config->getValue('last.downloads.on.home') == 0) {
            $enableDownloads = $dao->findEnabled($dir,$limit,$offset);            

        }
        $total = $dao->count($dir);

        $message = jMessage::get('public_msg');
        $nb_msg = count($message);
        jMessage::clearAll();
        
        $this->_tpl->assign('message',$message);
        $this->_tpl->assign('nb_msg',$nb_msg);
        
        $this->_tpl->assign('path',$dir);
        $this->_tpl->assign('populars',$popular);
        $this->_tpl->assign('popular',$config->getValue('most.popular.downloads.on.home'));
        $this->_tpl->assign('lastest',$config->getValue('last.downloads.on.home'));
        $this->_tpl->assign('lastests',$lastest);
        $this->_tpl->assign('downloads',$enableDownloads);
        
        $this->_tpl->assign('offset',$offset);
        $this->_tpl->assign('nbPerPage',$limit);
        $this->_tpl->assign('total',$total);
    }
}