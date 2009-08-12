<?php
/**
* @package   havefnubb
* @subpackage hfnuinstall
* @author    FoxMaSk
* @copyright 2008 FoxMaSk
* @link      http://forge.jelix.org/projects/havefnubb
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

// Installation controller
class defaultCtrl extends jController {
    public $pluginParams = array(
        '*'		=>	array('auth.required'=>false,
						  'hfnu.timeout.do.not.check'=>true)
    );
    
    /**
     * Main method which manage all the installation process
     * step 1 : check the prerequisite
     * step 2 : define the config of the forum
     * step 3 : define the config access
     * step 4 : install the database
     * step 5 : define the admin account
     * step 6 : end
     */
    function index() {
        global $HfnuConfig, $gJConfig;
        
        // is the install still done ?
        if ($HfnuConfig->getValue('installed','main') == 1) {            
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
					
					$d = jClasses::getService('hfnuinstall~supported_drivers');
					
					$dbSupported = false;
					$dbSupported = $d->check();
                        
                    $continue = false;
                    if ($dbSupported === true and $phpSupported === true) $continue = true;
                    
                    $tpl->assign('step','check');
                    $tpl->assign('continue',$continue);
                    
                    break;
                
				 case 'config':                    
                    $submit = $this->param('validate');
                    $mainConfig = new jIniFileModifier(JELIX_APP_CONFIG_PATH . 'defaultconfig.ini.php');
                    
                    if ($submit == jLocale::get('hfnuinstall~install.config.saveConfigBt')  ) {
                        
                        $form = jForms::fill('hfnuinstall~config');
                        
                        if (!$form->check()) {
                            jMessage::add(jLocale::get('hfnuinstall~install.config.check.your.config'),'warning') ;
                            $rep = $this->getResponse('redirect');
                            $rep->action='hfnuinstall~default:index';
                            $rep->params = array('step'=>'config');
                            return $rep;
                        }
                        
                        $HfnuConfig->setValue('title',      htmlentities($this->param('title')),'main');
                        $HfnuConfig->setValue('description',htmlentities($this->param('description')),'main');                        
                        $HfnuConfig->setValue('rules',      htmlentities($this->param('rules')),'main');
                                                
                        $HfnuConfig->save();                        
                        
						
						$mainConfig->setValue('theme',      htmlentities($this->param('theme')));
                        $mainConfig->setValue('webmasterEmail', htmlentities($this->param('webmasterEmail')),'mailer');
                        $mainConfig->setValue('webmasterName',  htmlentities($this->param('webmasterName')),'mailer');
                        $mainConfig->setValue('mailerType',     htmlentities($this->param('mailerType')),'mailer');
                        $mainConfig->setValue('hostname',       htmlentities($this->param('hostname')),'mailer');
                        $mainConfig->setValue('sendmailPath',   htmlentities($this->param('sendmailPath')),'mailer');
                        $mainConfig->setValue('smtpHost',       htmlentities($this->param('smtpHost')),'mailer');
                        $mainConfig->setValue('smtpPort',       htmlentities($this->param('smtpPort')),'mailer');
                        $mainConfig->setValue('smtpAuth',       htmlentities($this->param('smtpAuth')),'mailer');
                        $mainConfig->setValue('smtpUsername',   htmlentities($this->param('smtpUsername')),'mailer');
                        $mainConfig->setValue('smtpPassword',   htmlentities($this->param('smtpPassword')),'mailer');
                        $mainConfig->setValue('smtpTimeout',    htmlentities($this->param('smtpTimeout')),'mailer');                        
                        $mainConfig->save();                                                
                        
                        jForms::destroy('hfnuinstall~config');
                        $rep = $this->getResponse('redirect');
                        $rep->action ='hfnuinstall~default:index';
                        $rep->params = array('step'=>'dbconfig');
                        return $rep;            
                    }
                    else  {
                        $form = jForms::create('hfnuinstall~config');                            
                        $form->setData('title',         stripslashes($HfnuConfig->getValue('title','main')));
                        $form->setData('description',   stripslashes($HfnuConfig->getValue('description','main')));
                        $form->setData('theme',         stripslashes($mainConfig->getValue('theme')));
                        $form->setData('rules',         stripslashes($HfnuConfig->getValue('rules')));
                        $form->setData('webmasterEmail',stripslashes($mainConfig->getValue('webmasterEmail','mailer')));
                        $form->setData('webmasterName', stripslashes($mainConfig->getValue('webmasterName','mailer')));
                        $form->setData('mailerType',    stripslashes($mainConfig->getValue('mailerType','mailer')));
                        $form->setData('hostname',      stripslashes($mainConfig->getValue('hostname','mailer')));
                        $form->setData('sendmailPath',  stripslashes($mainConfig->getValue('sendmailPath','mailer')));
                        $form->setData('smtpHost',      stripslashes($mainConfig->getValue('smtpHost','mailer')));
                        $form->setData('smtpPort',      stripslashes($mainConfig->getValue('smtpPort','mailer')));
                        $form->setData('smtpAuth',      stripslashes($mainConfig->getValue('smtpAuth','mailer')));
                        $form->setData('smtpUsername',  stripslashes($mainConfig->getValue('smtpUsername','mailer')));
                        $form->setData('smtpPassword',  stripslashes($mainConfig->getValue('smtpPassword','mailer')));
                        $form->setData('smtpTimeout',   stripslashes($mainConfig->getValue('smtpTimeout','mailer')));                        
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
                        
                        $dbProfile->setValue('driver',      $this->param('driver'),'havefnubb');
                        $dbProfile->setValue('database',    $this->param('database'),'havefnubb');
                        $dbProfile->setValue('host',        $this->param('host'),'havefnubb');
                        $dbProfile->setValue('user',        $this->param('user'),'havefnubb');
                        $dbProfile->setValue('password',    $this->param('password'),'havefnubb');
                        $dbProfile->setValue('persistent',  $this->param('persistent'),'havefnubb');
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
						
						$file = dirname(__FILE__).'/../install/sql/install.'.$profile['driver'].'.sql';
                        
                        //default fake prefix uses in the filename if no prefix table are filled
						$tablePrefix = 'null_';
                        
						$dbProfile = new jIniFileModifier(JELIX_APP_CONFIG_PATH . $gJConfig->dbProfils);
                        
						if ($dbProfile->getValue('table_prefix','havefnubb') != '' ) {						
							$tablePrefix = $dbProfile->getValue('table_prefix','havefnubb') ;							
                        }
                        
                        $fileDest = dirname(__FILE__).'/../install/sql/'.$tablePrefix.'install.'.$profile['driver'].'.sql';
                        
                        $sources = file($file);
                        $newSource = '';
                        
                        $pattern = '/(DROP TABLE IF EXISTS|CREATE TABLE IF NOT EXISTS|INSERT INTO) `(hf_)(.*)/';
                        
                        foreach ((array)$sources as $key=>$line) {
                            if (preg_match($pattern,$line,$match)) {
                                if ($tablePrefix != 'null_')
                                    $newSource .= $match[1] .' `'.$tablePrefix . $match[3];
                                else
                                    $newSource .= $match[1] .' `'. $match[3];
                            }
                            else {
                                $newSource .= $line;
                            }
                        }							

                        $fh = fopen($fileDest,'w+');
                        fwrite($fh,$newSource);
                        fclose($fh);
                        $file = dirname(__FILE__).'/../install/sql/'.$tablePrefix.'install.'.$profile['driver'].'.sql';
                        
                        $tools->execSQLScript($file);
                        @unlink($file);							

                        $rep = $this->getResponse('redirect');
                        $rep->action ='hfnuinstall~default:index';
                        $rep->params = array('step'=>'adminaccount');
                        return $rep;                        
                    }
                    else {
                        $form = jForms::create('hfnuinstall~installdb');                     
                        $form->setData('step','installdb');
                        $tpl->assign('form',$form);                        
                    }
                    break;
                
                case "adminaccount" :
                    $submit = $this->param('validate');
                    
                    if ($submit == jLocale::get('hfnuinstall~install.adminaccount.saveBt') ) {

                        $form = jForms::fill('hfnuinstall~adminaccount');
               
                        if (!$form->check()) {                           
                            $rep = $this->getResponse('redirect');
                            $rep->action='hfnuinstall~default:index';
                            $rep->params = array('step'=>'adminaccount');
                            return $rep;
                        }
                        
						$HfnuConfig->setValue('admin_email',htmlentities($this->param('admin_email')),'main');
                        $HfnuConfig->save();                          
                                                
                        // let's create an Admin account !
                        // 1) get data !
                        $login  = $form->getData('login');
                        $pass   = $form->getData('password');
                        // 2) create User Object
                        $user   = jAuth::createUserObject($login,$pass);
                        // 3) set properties
                        $user->email    = $form->getData('admin_email');
                        $user->nickname = $login;
                        $user->status   = 1;
						$user->member_created = date('Y-m-d H:i:s');
                        $user->request_date = date('Y-m-d H:i:s');
                        // $user->keyactivate = $key;
                        // 4) save the user !
                        // this will add the user to the "default group" which is "users"                        
                        jAuth::saveNewUser($user);
                        // 5) add this user to the group "admins" number 1
                        jAcl2DbUserGroup::addUserToGroup($login, 1);
                        // 6) remove the user to the groups "users" 2 (the default one)
                        jAcl2DbUserGroup::removeUserFromGroup($login, 2 );
                        // DONE : we have created an admin account !
                        
                        // let's display the random password to the last page
                        $rep = $this->getResponse('redirect');
                        $rep->action ='hfnuinstall~default:index';
                        $rep->params = array('step'=>'end');
                        return $rep;                        
                    }
                    else {
                        $form = jForms::create('hfnuinstall~adminaccount');                     
                        $form->setData('step','adminaccount');
                        $tpl->assign('form',$form);
                    }
                    break;				
				 case 'end':
                    $HfnuConfig->setValue('installed',true,'main');
                    $HfnuConfig->save();
                    break;				
            }            
            
        }
        
        $rep = $this->getResponse('html');		
        $tpl->assign('step',$step);
        $rep->body->assign('MAIN', $tpl->fetch('install'));
        return $rep;		
	}    



    /**
     * Method to update from rc2 to rc3
     */	
	function update_rc2_to_rc3() {
		global $HfnuConfig, $gJConfig;
		
        if ($HfnuConfig->getValue('installed','main') == 0) {            
            $rep = $this->getResponse('redirect');
            $rep->action = 'hfnuinstall~default:index';
            return $rep;            
        }
		
		if ($HfnuConfig->getValue('version','main') == '1.0.0RC2') {
		
            self::_update_to_rc3();	
				
			$rep = $this->getResponse('html');
			$tpl = new jTpl();
			jMessage::add(jLocale::get('hfnuinstall~install.havefnubb.updated'),'ok');
			$rep->body->assign('MAIN', $tpl->fetch('hfnuinstall~update'));			
			return $rep;				
		}
		else {
			$rep = $this->getResponse('html');
			$tpl = new jTpl();
			jMessage::add(jLocale::get('hfnuinstall~install.havefnubb.still.uptodate'),'error');
			$rep->body->assign('MAIN', $tpl->fetch('hfnuinstall~update'));
			return $rep;		
		}
	}
    /**
     * Method to update to 1.0.0
     */	
	function update_to_1() {
		global $HfnuConfig, $gJConfig;
        
		$version = $HfnuConfig->getValue('version','main');
        
        if ($HfnuConfig->getValue('installed','main') == 0) {            
            $rep = $this->getResponse('redirect');
            $rep->action = 'hfnuinstall~default:index';
            return $rep;            
        }
        $updated == '';
		if ($version == '1.0.0RC2') {
            self::_update_to_rc3();
            $updated = 'ok';
        }
		if ($version == '1.0.0RC3') {
			self::_update_to_1();
            $updated = 'ok';
        }

        if ($updated == 'ok') {
			$rep = $this->getResponse('html');
			$tpl = new jTpl();
			jMessage::add(jLocale::get('hfnuinstall~install.havefnubb.updated'),'ok');
			$rep->body->assign('MAIN', $tpl->fetch('hfnuinstall~update'));			
			return $rep;
		}
		else {
			$rep = $this->getResponse('html');
			$tpl = new jTpl();
			jMessage::add(jLocale::get('hfnuinstall~install.havefnubb.still.uptodate'),'error');
			$rep->body->assign('MAIN', $tpl->fetch('hfnuinstall~update'));
			return $rep;		
		}
    }
    /**
     * Method to update to 1.0.1
     */	
	function update_to_1_0_1() {
		global $HfnuConfig, $gJConfig;
        
		$version = $HfnuConfig->getValue('version','main');
        
        if ($HfnuConfig->getValue('installed','main') == 0) {            
            $rep = $this->getResponse('redirect');
            $rep->action = 'hfnuinstall~default:index';
            return $rep;            
        }
        $updated == '';
		if ($version == '1.0.0RC2') {
            self::_update_to_rc3();
            $updated = 'ok';
        }
		if ($version == '1.0.0RC3') {
			self::_update_to_1();
            $updated = 'ok';
        }
		if ($version == '1.0.0') {
			self::_update_to_1_0_1();
            $updated = 'ok';
        }
        if ($updated == 'ok') {
			$rep = $this->getResponse('html');
			$tpl = new jTpl();
			jMessage::add(jLocale::get('hfnuinstall~install.havefnubb.updated'),'ok');
			$rep->body->assign('MAIN', $tpl->fetch('hfnuinstall~update'));			
			return $rep;
		}
		else {
			$rep = $this->getResponse('html');
			$tpl = new jTpl();
			jMessage::add(jLocale::get('hfnuinstall~install.havefnubb.still.uptodate'),'error');
			$rep->body->assign('MAIN', $tpl->fetch('hfnuinstall~update'));
			return $rep;		
		}
    }
        
    private  static function _update_to_rc3() {
        global $HfnuConfig, $gJConfig;
        
        $mainConfig = new jIniFileModifier(JELIX_APP_CONFIG_PATH . 'defaultconfig.ini.php');
        $hfnuadminEntriesPoint =  $mainConfig->getValue('hfnuadmin','simple_urlengine_entrypoints');		
        $hfnuadminEntriesPoint .= ', hfnucontact~*@classic';
        $mainConfig->setValue('hfnuadmin', $hfnuadminEntriesPoint,'simple_urlengine_entrypoints');
        $mainConfig->save();
        
        $db 		= new jDb();
        $profile 	= $db->getProfile('havefnubb');
        $tools 		= jDb::getTools('havefnubb');
        
        $file = dirname(__FILE__).'/../install/update/1.0.0RC3/install.'.$profile['driver'].'.sql';
        
        //default fake prefix uses in the filename if no prefix table are filled
        $tablePrefix = 'null_';
        
        $dbProfile = new jIniFileModifier(JELIX_APP_CONFIG_PATH . $gJConfig->dbProfils);
        
        
        if ($dbProfile->getValue('table_prefix','havefnubb') != '' ) {            
            $tablePrefix = $dbProfile->getValue('table_prefix','havefnubb') ;
        }            
        $fileDest = dirname(__FILE__).'/../install/update/1.0.0RC3/'.$tablePrefix.'install.'.$profile['driver'].'.sql';
        $sources = file($file);
        $newSource = '';
        
        $pattern = '/(DROP TABLE IF EXISTS|CREATE TABLE IF NOT EXISTS|INSERT INTO|UPDATE|ALTER TABLE) `(hf_)(.*)/';
        
        foreach ((array)$sources as $key=>$line) {
            if (preg_match($pattern,$line,$match)) {
                if ($tablePrefix != 'null_')
                    $newSource .= $match[1] .' `'.$tablePrefix . $match[3];
                else
                    $newSource .= $match[1] .' `'. $match[3];
            }
            else {
                $newSource .= $line;
            }
        }							

        $fh = fopen($fileDest,'w+');
        fwrite($fh,$newSource);
        fclose($fh);
        $file = dirname(__FILE__).'/../install/update/1.0.0RC3/'.$tablePrefix.'install.'.$profile['driver'].'.sql';
        
        $tools->execSQLScript($file);
        @unlink($file);							

        $HfnuConfig->setValue('version','1.0.0RC3','main');
        $HfnuConfig->save();

        jFile::removeDir(JELIX_APP_TEMP_PATH, false);
    }

    private  static function _update_to_1() {
        global $HfnuConfig, $gJConfig;
        $db 		= new jDb();
        $profile 	= $db->getProfile('havefnubb');
        $tools 		= jDb::getTools('havefnubb');
        
        $file = dirname(__FILE__).'/../install/update/1.0.0/install.'.$profile['driver'].'.sql';
        
        //default fake prefix uses in the filename if no prefix table are filled
        $tablePrefix = 'null_';
        
        $dbProfile = new jIniFileModifier(JELIX_APP_CONFIG_PATH . $gJConfig->dbProfils);
        
        
        if ($dbProfile->getValue('table_prefix','havefnubb') != '' ) {            
            $tablePrefix = $dbProfile->getValue('table_prefix','havefnubb') ;
        }            
        $fileDest = dirname(__FILE__).'/../install/update/1.0.0/'.$tablePrefix.'install.'.$profile['driver'].'.sql';
        $sources = file($file);
        $newSource = '';
        
        $pattern = '/(DROP TABLE IF EXISTS|CREATE TABLE IF NOT EXISTS|INSERT INTO|UPDATE|ALTER TABLE) `(hf_)(.*)/';
        
        foreach ((array)$sources as $key=>$line) {
            if (preg_match($pattern,$line,$match)) {
                if ($tablePrefix != 'null_')
                    $newSource .= $match[1] .' `'.$tablePrefix . $match[3];
                else
                    $newSource .= $match[1] .' `'. $match[3];
            }
            else {
                $newSource .= $line;
            }
        }							

        $fh = fopen($fileDest,'w+');
        fwrite($fh,$newSource);
        fclose($fh);
        $file = dirname(__FILE__).'/../install/update/1.0.0/'.$tablePrefix.'install.'.$profile['driver'].'.sql';
        
        $tools->execSQLScript($file);
        @unlink($file);							

		
        $HfnuConfig->setValue('version','1.0.0','main');
        $HfnuConfig->save();
        jFile::removeDir(JELIX_APP_TEMP_PATH, false);
    }
	
    private  static function _update_to_1_0_1() {
        global $HfnuConfig;
		
        $HfnuConfig->setValue('version','1.0.1','main');
        $HfnuConfig->save();
        jFile::removeDir(JELIX_APP_TEMP_PATH, false);
    }
    
}
