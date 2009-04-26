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

class mgrCtrl extends jController {
    /**
    *
    */
	// forms
    private $configForm = 'downloads~config';
    private $editForm   = 'downloads~edit';
	// dao
    private $adminDao   = 'downloads~downloads';
	private $adminUserDao = 'downloads~downloads_users';
	// acl 
    public $pluginParams = array(
        'index'			=>array('jacl2.rights.and'=>array('downloads.index')),
		'dls'			=>array('jacl2.rights.and'=>array('downloads.list')),
		'config'		=>array('jacl2.rights.and'=>array('downloads.config')),
		'manage'	    =>array('jacl2.rights.and'=>array('downloads.edit')),
        'delete'	    =>array('jacl2.rights.and'=>array('downloads.delete')),
    ); 

    /*
     * DOWNLOAD PART
     */
    // Admin Index
    function index() {
        $rep = $this->getResponse('html');
        $rep->title = jLocale::get('index.admin.title.page');
        $tpl = new jTpl();            
        $rep->body->assign('ADMINTITLE','- Administration'); 
        $rep->body->assignZone("MAIN", 'admin'); 
        return $rep;        
    }
    
    // Admin Index
    function dls() {
        $rep = $this->getResponse('html');
        $rep->title = jLocale::get('index.admin.title.page');
        $tpl = new jTpl();            
        $rep->body->assign('ADMINTITLE','- Administration');  
        $rep->body->assignZone("MAIN", 'admin_list'); 
        return $rep;
    }
    
    //Admin Config
    function config() {
        // load the config
        jClasses::inc('download_config');
        $config = downloadConfig::getConfig();
        // we submit config
        if ($this->param('_submit') == 'Ok') {
            
            $form = jForms::fill($this->configForm);
            // change the value
            $config->setValue('allow.guest',$this->param('guest'));
            $config->setValue('allow.external.links',$this->param('external_links'));
            $config->setValue('number.downloads.on.home',$this->param('number_downloads_on_home'));
            $config->setValue('last.downloads.on.home',$this->param('last_downloads_on_home'));
            $config->setValue('most.popular.downloads.on.home',$this->param('most_popular_downloads_on_home'));
            // save config file
            $config->save();
            // message for the user
            jMessage::add(jLocale::get('downloads~common.configuration.saved'),'admin_msg');
            $rep = $this->getResponse('redirect');
            $rep->action='downloads~mgr:config';
            return $rep;
            
        }
        // display the config page
        else {
            
            $form = jForms::get($this->configForm);
            if($form == null) 
                $form = jForms::create($this->configForm);
            
            $form->setData('guest',$config->getValue('allow.guest'));
            $form->setData('external_links',$config->getValue('allow.external.links'));
            $form->setData('number_downloads_on_home',$config->getValue('number.downloads.on.home'));
            $form->setData('last_downloads_on_home',$config->getValue('last.downloads.on.home'));
            $form->setData('most_popular_downloads_on_home',$config->getValue('most.popular.downloads.on.home'));
        }
        
        $rep = $this->getResponse('html');      
        $rep->title = jLocale::get('config.title.page');
        $tpl = new jTpl();            
        $rep->body->assign('ADMINTITLE','- Administration');  
        $rep->body->assign("MAIN", jZone::get('downloads~adminconfig', array('form'=>$form)));
        return $rep;
    }

    // Admin : Add/Edit a download
    function manage() {
		
		$tpl = new jTpl();
		
		jClasses::inc('download_config');
		$config = downloadConfig::getConfig();    
		jClasses::inc('download_files');
		
        $id = (integer) $this->param('id');        
        
		$mode = '';
		// add or edit ?
		if ($id == 0 ) {
			// ... add
			$titlePage = jLocale::get('common.add.title.page');
			$mode = 'add';

		} else {
			// ... edit
			$titlePage = jLocale::get('common.edit.title.page');
			$tpl->assign('id',$id);
			$mode = 'edit';
		}

        $filename = '';
        // we are editing the download
        if ($this->param('validate') == '') {
			if ($mode == 'edit' ) {
				$form = jForms::get($this->editForm,$id);
				if ($form === null)
					$form = jForms::create($this->editForm,$id);
				$form->initFromDao($this->adminDao);
				
				$tpl->assign('preview',0);
		
				$filename = (string) $form->getData('dl_filename');
	
				if ($filename != '')  {
					$tpl->assign('filename','[x] '.$filename);
					$form->deactivate('dl_filename');
				}
				else  {
					$tpl->assign('filename','');
					$form->deactivate('dl_filename',false);
				}
			}
			else {
				$form = jForms::create($this->editForm);            
				$tpl->assign('preview',0);
				$tpl->assign('filename','');				
			}
        }
        else {
			// ... and we want to preview the page first
            if ($this->param('validate') == jLocale::get('common.previewBt')) {
                $tpl->assign('preview',1);
				if ($mode == 'edit' )
					$form = jForms::fill($this->editForm,$id);
				else
					$form = jForms::fill($this->editForm);

				// if url is empty, make one automatically
				if ($form->getData('dl_url') == '')
					$url = jUrl::escape($form->getData('dl_name'),true);
				else
				// else display it "as is"
					$url = $form->getData('dl_url');
										
				// assign local var from the form
				$tpl->assign('name',$form->getData('dl_name'));
				$tpl->assign('url',$url);
				$tpl->assign('filename',$form->getData('dl_filename'));
				$tpl->assign('description',$form->getData('dl_desc'));
				$tpl->assign('date',$form->getData('dl_date'));
				$tpl->assign('counter',$form->getData('dl_count'));
				$tpl->assign('filesize',downloadFiles::getFileSize($this->param('dl_filename'),$this->param('dl_path')));

					
                // check ok ?
                // ... no : return to the Admin Index page                
                if (!$form->check()) {

					
                }

            }
            // ... Storing Process
            elseif ($this->param('validate') == jLocale::get('common.saveBt')) { 
                $tpl->assign('preview',0);
				
				if ($mode == 'edit')
					$form = jForms::fill($this->editForm,$id);
				else
					$form = jForms::fill($this->editForm);
				
                // check ok ?
                // ... no : loop on adding process
                if (!$form->check()) {
					// reset the filename
					$tpl->assign('filename',$form->getData('dl_filename'));
                }
                // ... yes 
                else {
				
                    // if url is empty, make one automatically
                    if ($form->getData('dl_url') == '')
                        $url = jUrl::escape($form->getData('dl_name'),true);
                    else
                    // else store it "as is"
                        $url = $form->getData('dl_url');

					$login = jAuth::getUserSession ()->login;
					$dao = jDao::get($this->adminUserDao);
					$userDir = $dao->getByUserDir($login,$form->getData('dl_path'));
					
					// if no record found : big issue !
					if ( $userDir === false) {
						jMessage::add(jLocale::get('downloads~fatal.your.account.is.not.properly.define.unknow.directory'),'admin_msg');
						$rep = $this->getResponse('redirect');
						$rep->action='downloads~mgr:dls';
						return $rep;						
					}
                    $saved = false;
					if ($form->getData('dl_filename') <> '' )					
						$saved = $form->saveFile('dl_filename', $config->getValue('commons.upload.dir').
										DIRECTORY_SEPARATOR.$form->getData('dl_path'));
					if ($saved === false) {
                        jMessage::add(jLocale::get('downloads~common.filesize.too.big'),'admin_msg');
                    }
                    else {
                        $form->setData('login',$login);
                        $form->setData('dl_url',$url);
                        
                        if ($mode == 'edit') {
                            $form->saveToDao($this->adminDao,$id);
                            jMessage::add(jLocale::get('downloads~common.modifications.saved'),'admin_msg');
                        }
                        else {
                            $form->saveToDao($this->adminDao);
                            jMessage::add(jLocale::get('downloads~common.adding.download.success'),'admin_msg');
                        }
					}              
                    $rep = $this->getResponse('redirect');
                    $rep->action='downloads~mgr:dls';
                    return $rep;
                    
                }
            }
        }        
        $tpl->assign('form', $form);
		$tpl->assign('mode', $mode);

        $rep = $this->getResponse('html');        
        $rep->title = $titlePage;
		
		if ($mode == 'edit' and $filename != '') {
			$url = jUrl::get('downloads~mgr:trashx');
			
			$jsCode =
			// 'onLoad' unobstructive way ;)
			'$(document).ready(function(){ ' . "\n"."\t".
				  '$("#newfilename").hide();'."\n"."\t".
				  '$("#trash").click(function() {'."\n"."\t"."\t".      
					'trash();'."\n"."\t".
				  '});'."\n"."\n".
				'});'."\n".
			"\n".
			
			// call the trash function to remove the file from the
			// current download definition
			'function trash() {'."\n".
			   '$.ajax({'."\n"."\t".
			   'type: "POST",'."\n"."\t".
			   'url: "'.$url.'",'."\n"."\t".
			   'data: "id='.$id.'",'."\n"."\t".
			   "dataType: 'html',"."\n"."\t".
			   'success: update'."\n"."\t".
			'})'."\n".
			'}'."\n".
			
			// display the input file
			'function update(response) {'."\n".
			'  $("#trash").slideUp("slow"); '. "\n"."\t".
			'  $("#newfilename").html(response);'."\n"."\t".
			'  $("#newfilename").slideDown("slow");'."\n"."\t".		   
			'};';
			
			$rep->addJSCode( $jsCode );			
		}		
            
        $rep->body->assign('ADMINTITLE','- Administration');
        $rep->body->assign("MAIN", $tpl->fetch('downloads~admin_edit'));
        return $rep;
    }


    // Admin : delete process
    function delete() {
        $id = (integer) $this->param('id');
        $dao = jDao::get($this->adminDao);
		$dl = $dao->get($id);
		
		// does this download owns a file ?
		if ($dl->dl_filename != '') {
			jClasses::inc('download_config');
			$config = downloadConfig::getConfig();
			$file = $config->getValue('commons.upload.dir').DIRECTORY_SEPARATOR.$dl->dl_path.DIRECTORY_SEPARATOR.$dl->dl_filename;
			$file = realpath($file);
			@unlink($file);
		}
		// delete the download from the database
        $dao->delete($id);

        jMessage::add(jLocale::get('downloads~common.download.deleted'),'admin_msg');
        $rep = $this->getResponse('redirect');
        $rep->action='downloads~mgr:dls';
        return $rep;
    }

    // Admin : remove the file from the list page
    function trash() {
		
        $id = (integer) $this->param('id');
		
		$dao = jDao::get($this->adminDao);
		$dl = $dao->get($id);
		
		jClasses::inc('download_config');
		$config = downloadConfig::getConfig();
		$file = $config->getValue('commons.upload.dir').DIRECTORY_SEPARATOR.$dl->dl_path.DIRECTORY_SEPARATOR.$dl->dl_filename;
		$file = realpath($file);
		@unlink($file);		
		
		$dao->trash($id);		
		
        jMessage::add(jLocale::get('downloads~common.download.removed'),'admin_msg');
        $rep = $this->getResponse('redirect');
        $rep->action='downloads~mgr:dls';
        return $rep;
    }

	// Admin : remove the file from the edit page : AJAX
    function trashx() { 
        
        $id = (integer) $this->param('id');
		
        $form = jForms::get('downloads~edit',$id);
		if ($form === null)
			$form = jForms::create('downloads~edit',$id);
		$form->initFromDao($this->adminDao);		
		$form->deactivate('dl_filename',false);
		
		$dao = jDao::get($this->adminDao);		
		$dl = $dao->get($id);
		
		jClasses::inc('download_config');
		$config = downloadConfig::getConfig();
		$file = $config->getValue('commons.upload.dir').DIRECTORY_SEPARATOR.$dl->dl_path.DIRECTORY_SEPARATOR.$dl->dl_filename;
		$file = realpath($file);
		@unlink($file);
		
		$dao->trash($id);
		
        $rep = $this->getResponse('htmlfragment');
		$rep->tpl->assign('message',jLocale::get('downloads~common.download.removed'));
        $rep->tplname='downloads~admin_upload_ajax'; 
        return $rep;
    }

    
    // Admin : Toggle the download on the block
    function dl_on_block() {
        $id = (integer) $this->param('id');
        $dao = jDao::get($this->adminDao);
        $dao->toggleBlock($id,1);       
        $rep = $this->getResponse('redirect');
        $rep->action='downloads~mgr:dls';
        return $rep;
    }
    
    // Admin : Toggle the download off the block
    function dl_not_on_block() {
        $id = (integer) $this->param('id');
        $dao = jDao::get($this->adminDao);
        $dao->toggleBlock($id,0); 
        $rep = $this->getResponse('redirect');
        $rep->action='downloads~mgr:dls';
        return $rep;        
    }
    
    // Admin : Toggle the download enable
    function dl_enable() {
        $id = (integer) $this->param('id');
        $dao = jDao::get($this->adminDao);
        $dao->toggleStatus($id,1);       
        $rep = $this->getResponse('redirect');
        $rep->action='downloads~mgr:dls';
        return $rep;
    }

    // Admin : Toggle the download disable
    function dl_disable() {
        $id = (integer) $this->param('id');
        $dao = jDao::get($this->adminDao);
        $dao->toggleStatus($id,0); 
        $rep = $this->getResponse('redirect');
        $rep->action='downloads~mgr:dls';
        return $rep;        
    }
    
}

