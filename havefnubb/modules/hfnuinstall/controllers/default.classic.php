<?php
/**
* @package   havefnubb
* @subpackage hfnuinstall
* @author    FoxMaSk
* @copyright 2008 FoxMaSk
* @link      http://forge.jelix.org/projects/havefnubb
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

class defaultCtrl extends jController {
    public $pluginParams = array(
        '*'		=>	array('auth.required'=>false)
    );
    function index() {
        global $HfnuConfig, $gJConfig;
        
        // is the install still done ?
        if ($HfnuConfig->getValue('installed') == 1) {            
            $rep = $this->getResponse('redirect');
            $rep->action = 'havefnubb~default:index';
            return $rep;            
        }
        
		$step = $this->param('step');
        
        $tpl = new jTpl();

        if ($step == '') {

            $step = 'home';
		}
            
        else {
            
            switch($step) {
				 case 'check':
                    
                    $phpSupported = false;                    
                    if ( version_compare(phpversion(),'5.0','>=') ) {
                        $phpSupported = true;
                        jMessage::add(jLocale::get('hfnuinstall~install.check.php.version.is',array(phpversion())),'ok') ;
                    }
                    else
                        jMessage::add(jLocale::get('hfnuinstall~install.check.php.version.is.requied',array(phpversion())),'error');

                    $dbSupported = false;
                    if ( function_exists('mysql_connect') ) {
                        $dbSupported = true;
                        jMessage::add(jLocale::get('hfnuinstall~install.check.module.is.present',array('MySQL')),'ok') ;
                    }
                    else
                        jMessage::add(jLocale::get('hfnuinstall~install.check.module.is.not.present',array('MySQL')),'warning') ;
                                      
                    if ( function_exists('pg_connect') ) {
                        $dbSupported = true;
                        jMessage::add(jLocale::get('hfnuinstall~install.check.module.is.present',array('PostGresql')),'ok') ;
                    }
                    else
                        jMessage::add(jLocale::get('hfnuinstall~install.check.module.is.not.present',array('PostGresql')),'warning') ;

                    if ( function_exists('sqlite_connect') ) {
                        $dbSupported = true;
                        jMessage::add(jLocale::get('hfnuinstall~install.check.module.is.present',array('SQLite')),'ok') ;
                    }
                    else
                        jMessage::add(jLocale::get('hfnuinstall~install.check.module.is.not.present',array('SQLite')),'warning') ;
                        
                    $continue = false;
                    if ($dbSupported === true and $phpSupported === true) $continue = true;
                    
                    $tpl->assign('step','check');
                    $tpl->assign('continue',$continue);
                    
                    break;
                
				 case 'config':                    
                    $submit = $this->param('validate');
                    
                    if ($submit == jLocale::get('hfnuinstall~install.config.saveConfigBt')  ) {
                        
                        $form = jForms::get('hfnuinstall~config');
                        
                        if (!$form->check()) {
                            jMessage::add('pb');
                            $rep = $this->getResponse('redirect');
                            $rep->action='hfnuinstall~default:index';
                            $rep->params = array('step'=>'config');
                            return $rep;
                        }
                        
                        $HfnuConfig->setValue('title',htmlentities($this->param('title')));
                        $HfnuConfig->setValue('description',htmlentities($this->param('description')));
                        $HfnuConfig->setValue('theme',htmlentities($this->param('theme')));
                        $HfnuConfig->setValue('rules',htmlentities($this->param('rules')));
                        $HfnuConfig->setValue('webmaster_email',htmlentities($this->param('webmaster_email')));
                        $HfnuConfig->setValue('admin_email',htmlentities($this->param('admin_email')));
                                                
                        $HfnuConfig->save();
                        jForms::destroy('hfnuinstall~config');
                        $rep = $this->getResponse('redirect');
                        $rep->action ='hfnuinstall~default:index';
                        $rep->params = array('step'=>'dbconfig');
                        return $rep;            
                    }
                    else  {
                        $form = jForms::create('hfnuinstall~config');                            
                        $form->setData('title',           stripslashes($HfnuConfig->getValue('title')));
                        $form->setData('description',     stripslashes($HfnuConfig->getValue('description')));
                        $form->setData('theme',           stripslashes($HfnuConfig->getValue('theme')));
                        $form->setData('rules',           stripslashes($HfnuConfig->getValue('rules')));
                        $form->setData('webmaster_email', stripslashes($HfnuConfig->getValue('webmaster_email')));
                        $form->setData('admin_email',     stripslashes($HfnuConfig->getValue('admin_email')));
                        $form->setData('step','config');                    
                        $tpl->assign('form',$form);                    
                    }
                    
                    break;
                
				 case 'dbconfig':                   
                    $submit = $this->param('validate');
                    
                    if ($submit == jLocale::get('hfnuinstall~install.dbconfig.saveDbConfigBt') ) {
                        
                        $form = jForms::fill('hfnuinstall~dbconfig');
               
                        if (!$form->check()) {                           
                            $rep = $this->getResponse('redirect');
                            $rep->action='hfnuinstall~default:index';
                            $rep->params = array('step'=>'dbconfig');
                            return $rep;
                        }

                        $dbProfile = new jIniFileModifier(JELIX_APP_CONFIG_PATH . $gJConfig->dbProfils);
                        
                        $dbProfile->setValue('driver',$this->param('driver'),'havefnubb');
                        $dbProfile->setValue('database',$this->param('database'),'havefnubb');
                        $dbProfile->setValue('host',$this->param('host'),'havefnubb');
                        $dbProfile->setValue('user',$this->param('user'),'havefnubb');
                        $dbProfile->setValue('password',$this->param('password'),'havefnubb');
                        $dbProfile->setValue('persistent',$this->param('persistent'),'havefnubb');
                        $dbProfile->setValue('table_prefix',$this->param('table_prefix'),'havefnubb');

                        $dbProfile->save();                        
                        
                        $db = new jDb();
                        $profile = $db->getProfile('havefnubb');

                        if ( $db->testProfile($profile) === true ) {               
                            jForms::destroy('hfnuinstall~dbconfig');                            
                            $rep = $this->getResponse('redirect');
                            $rep->action ='hfnuinstall~default:index';
                            $rep->params = array('step'=>'installdb');
                            return $rep;
                        }
                        else {
                            // reinit the config file
                            $dbProfile->setValue('driver','','havefnubb');
                            $dbProfile->setValue('database','','havefnubb');
                            $dbProfile->setValue('host','','havefnubb');
                            $dbProfile->setValue('user','','havefnubb');
                            $dbProfile->setValue('password','','havefnubb');
                            $dbProfile->setValue('persistent','','havefnubb');
                            $dbProfile->setValue('table_prefix','','havefnubb');
    
                            $dbProfile->save();

                            jMessage::add(jLocale::get('hfnuinstall~install.dbconfig.parameters.invalids'));
                            $rep = $this->getResponse('redirect');
                            $rep->action ='hfnuinstall~default:index';
                            $rep->params = array('step'=>'dbconfig');
                            return $rep;                       
                        }
                    }                    
                    else {
                        $form = jForms::create('hfnuinstall~dbconfig');                     
                        $form->setData('step','dbconfig');
                        $tpl->assign('form',$form);
                    }
                    break;
				
                case "installdb" :
                    $submit = $this->param('validate');
                    
                    if ($submit == jLocale::get('hfnuinstall~install.installdb.saveRunSqlBt') ) {
                        
                        $db 		= new jDb();
                        $profile 	= $db->getProfile('havefnubb');
                        $tools 		= jDb::getTools('havefnubb');
						
                        $tools->execSQLScript(dirname(__FILE__).'/../install/sql/install.'.$profile['driver'].'.sql');
                        
                        $rep = $this->getResponse('redirect');
                        $rep->action ='hfnuinstall~default:index';
                        $rep->params = array('step'=>'end');
                        return $rep;                        
                    }
                    else {
                        $form = jForms::create('hfnuinstall~installdb');                     
                        $form->setData('step','installdb');
                        $tpl->assign('form',$form);                        
                    }
                    break;
				
				 case 'end':
                    $HfnuConfig->setValue('installed',true);
                    $HfnuConfig->save();
                    break;				
            }            
            
        }
        
        $rep = $this->getResponse('html');		
        $tpl->assign('step',$step);
        $rep->body->assign('MAIN', $tpl->fetch('install'));
        return $rep;		
	}    
}
