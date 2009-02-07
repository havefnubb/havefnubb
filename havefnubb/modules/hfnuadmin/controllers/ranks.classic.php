<?php
/**
* @package   havefnubb
* @subpackage hfnuadmin
* @author    FoxMaSk
* @copyright 2008 FoxMaSk
* @link      http://forge.jelix.org/projects/havefnubb
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

class ranksCtrl extends jController {
    /**
    *
    */
    public $pluginParams = array(
        'index'    => array( 'jacl2.rights.and'=>array('hfnu.admin.rank.create',
														'hfnu.admin.rank.edit')
							),		
        'delete'    => array( 'jacl2.right'=>'hfnu.admin.rank.delete'),
    );
    
	function index() {
		$tpl = new jTpl();
		
		$form = jForms::create('hfnuadmin~ranks');
		
        $dao = jDao::get('havefnubb~ranks');
        $ranks = $dao->findAll();
		
        $tpl->assign('form', $form);
        $tpl->assign('ranks',$ranks);
				
        $rep = $this->getResponse('html');        
        $rep->body->assign('MAIN', $tpl->fetch('hfnuadmin~ranks_index'));
        return $rep;
	}


    function savecreate () {
        $form = jForms::get('hfnuadmin~ranks');
        if ($form->check()) {
            jMessage::add(jLocale::get('hfnuadmin~rank.invalid.datas'),'error');
            $rep = $this->getResponse('redirect');
            $rep->action='hfnuadmin~default:ranks';
            return $rep;
        }
               
        if ($this->param('validate') == jLocale::get('hfnuadmin~rank.saveBt')) {

            $dao = jDao::get('havefnubb~ranks');
            
            $form = jForms::fill('hfnuadmin~ranks');
            
            $record = jDao::createRecord('havefnubb~ranks');        
            $record->rank_name = $form->getData('rank_name');
            $record->rank_limit = $form->getData('rank_limit');
            
            $dao->insert($record);
            
            jMessage::add(jLocale::get('hfnuadmin~rank.rank.added'),'ok');
        }
		
		$rep = $this->getResponse('redirect');
		$rep->action='hfnuadmin~ranks:index';		
        return $rep;
	    
    }

    function saveedit () {
        $id_rank 	= $this->param('id_rank');
		$rank_name 	= $this->param('rank_name');
		$rank_limit = $this->param('rank_limit');

		if ($this->param('saveBt')== jLocale::get('hfnuadmin~rank.saveBt')) {
		
			if (count($id_rank) == 0) {
				jMessage::add(jLocale::get('hfnuadmin~rank.unknown.rank'),'error');
				$rep = $this->getResponse('redirect');
				$rep->action='hfnuadmin~ranks:index';
				return $rep;                 
			} 
	
			$dao = jDao::get('havefnubb~ranks');
			
			foreach ($id_rank as $thisId) {
				$record 			= $dao->get( (int) $id_rank[$thisId]);
				$record->rank_name 	= (string) $rank_name[$id_rank[$thisId]];
				$record->rank_limit = (int) $rank_limit[$id_rank[$thisId]];
				
				$dao->update($record);
			}
			
			jMessage::add(jLocale::get('hfnuadmin~rank.rank.modified'),'ok');
		}
		else {
			jMessage::add(jLocale::get('hfnuadmin~rank.invalid.datas'),'error');
		}
		
        $rep = $this->getResponse('redirect');
        $rep->action='hfnuadmin~ranks:index';
        return $rep;
    }

    function delete() {
		$id_rank = (integer) $this->param('id_rank');
		if ($id_rank == 0) {
			jMessage::add(jLocale::get('hfnuadmin~rank.invalid.datas'),'error');			
		} else {
			$dao = jDao::get('havefnubb~ranks');
			//@TODO : check if there is existing Member using this rank
			// if so, tell the admin he cant remove it !		
			$dao->delete($id_rank);        
			jMessage::add(jLocale::get('hfnuadmin~rank.rank.deleted'),'ok');			
		}
        
        $rep = $this->getResponse('redirect');
        $rep->action='hfnuadmin~ranks:index';
        return $rep;        
    }   
    
}
