<?php

/**
* check a jelix installation
*
* @package     jelix
* @subpackage  core
* @author      Laurent Jouanneau
* @copyright   2007-2010 Laurent Jouanneau
* @link        http://jelix.org
* @licence     GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
* @since       1.0b2
*/

/**
 *
 */

/**
* @package     jelix
* @subpackage  installer
* @author      Laurent Jouanneau
* @contributor 
* @copyright   2008-2009 Laurent Jouanneau
* @link        http://jelix.org
* @licence     GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
*/

/**
* interface for classes used as reporter for installation or check etc...
* This classes are responsible to show informations to the user
* @package     jelix
* @subpackage  installer
* @since 1.2
*/
interface jIInstallReporter {

    /**
     * start the process
     */
    function start();

    /**
     * displays a message
     * @param string $message the message to display
     * @param string $type the type of the message : 'error', 'notice', 'warning', ''
     */
    function message($message, $type='');

    /**
     * called when the installation is finished
     * @param array $results an array which contains, for each type of message,
     * the number of messages
     */
    function end($results);

}



/**
* 
* @package  jelix
* @subpackage core
* @author   Laurent Jouanneau
* @contributor Bastien Jaillot
* @copyright 2007-2009 Laurent Jouanneau, 2008 Bastien Jaillot
* @link     http://www.jelix.org
* @licence  GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
* @since 1.0b2
*/

/**
 * message provider for jInstallCheck and jInstaller
 * @package  jelix
 * @subpackage core
 * @since 1.0b2
 */
class jInstallerMessageProvider {
    protected $currentLang;

    protected $messages = array(
        'fr'=>array(
     'checker.title'=>'Vérification de votre serveur pour Jelix 1.2pre.1529',
        'number.errors'         =>' erreurs.',
        'number.error'          =>' erreur.',
        'number.warnings'       =>' avertissements.',
        'number.warning'        =>' avertissement.',
        'number.notices'        =>' remarques.',
        'number.notice'         =>' remarque.',
    'conclusion.error'      =>'Vous devez corriger l\'erreur pour faire fonctionner correctement une application Jelix 1.2pre.1529.',
    'conclusion.errors'     =>'Vous devez corriger les erreurs pour faire fonctionner correctement une application Jelix 1.2pre.1529.',
    'conclusion.warning'    =>'Une application Jelix 1.2pre.1529 peut à priori fonctionner, mais il est préférable de corriger l\'avertissement pour être sûr.',
    'conclusion.warnings'   =>'Une application Jelix 1.2pre.1529 peut à priori fonctionner, mais il est préférable de corriger les avertissements pour être sûr.',
    'conclusion.notice'     =>'Aucun problème pour installer une application pour Jelix  1.2pre.1529 malgré la remarque.',
    'conclusion.notices'    =>'Aucun problème pour installer une application pour Jelix  1.2pre.1529 malgré les remarques.',
    'conclusion.ok'         =>'Vous pouvez installer une application avec Jelix 1.2pre.1529',
        'cannot.continue'       =>'Les vérifications ne peuvent continuer : ',
        'extension.dom'         =>'L\'extension DOM n\'est pas installée',
        'extension.spl'         =>'L\'extension spl  n\'est pas installée',
        'extension.simplexml'   =>'L\'extension simplexml n\'est pas installée',
        'extension.pcre'        =>'L\'extension pcre n\'est pas installée',
        'extension.session'     =>'L\'extension session n\'est pas installée',
        'extension.tokenizer'   =>'L\'extension tokenizer n\'est pas installée',
        'extension.iconv'       =>'L\'extension iconv n\'est pas installée',
        'extensions.required.ok'=>'Toutes les extensions obligatoires sont installées',
        'extension.filter'      =>'Cette édition de Jelix a besoin de l\'extension filter',
        'extension.json'        =>'Cette édition de Jelix a besoin de l\'extension json',
        'extension.xmlrpc'      =>'Cette édition de Jelix a besoin de l\'extension xmlrpc',
        'extension.jelix'       =>'Cette édition de Jelix a besoin de l\'extension jelix',
        'extension.opcode.cache'=>'Cette édition de Jelix a besoin d\'une extension de cache d\'opcode (apc, eaccelerator...)',
        'path.core'             =>'Le fichier init.php  de jelix ou le fichier application.ini.php de votre application n\'est pas chargé',
        'path.temp'             =>'Le repertoire temporaire n\'est pas accessible en écriture ou alors JELIX_APP_TEMP_PATH n\'est pas configurée comme il faut',
        'path.log'              =>'Le repertoire var/log dans votre application n\'est pas accessible en écriture ou alors JELIX_APP_LOG_PATH n\'est pas configurée comme il faut',
        'path.var'              =>'JELIX_APP_VAR_PATH n\'est pas configuré correctement : ce répertoire n\'existe pas',
        'path.config'           =>'JELIX_APP_CONFIG_PATH n\'est pas configuré correctement : ce répertoire n\'existe pas',
        'path.www'              =>'JELIX_APP_WWW_PATH n\'est pas configuré correctement : ce répertoire n\'existe pas',
        'php.bad.version'       =>'Mauvaise version de PHP',
        'php.version.current'   =>'Version courante :',
        'php.version.required'  =>'Cette édition de Jelix nécessite au moins PHP ',
        'too.critical.error'    =>'Trop d\'erreurs critiques sont apparues. Corrigez les.',
        'config.file'           =>'La variable $config_file n\'existe pas ou le fichier qu\'elle indique n\'existe pas',
        'paths.ok'              =>'Les répertoires temp, log, var, config et www sont ok',
        'ini.magic_quotes_gpc_with_plugin'=>'php.ini : le plugin magicquotes est activé mais vous devriez mettre magic_quotes_gpc à off',
        'ini.magicquotes_plugin_without_php'=>'php.ini : le plugin magicquotes est activé alors que magic_quotes_gpc est déjà à off, désactivez le plugin',
        'ini.magic_quotes_gpc'  =>'php.ini : l\'activation des magicquotes n\'est pas recommandée pour jelix. Vous devez les désactiver ou activer le plugin magicquotes si ce n\'est pas fait',
        'ini.magic_quotes_runtime'=>'php.ini : magic_quotes_runtime doit être à off',
        'ini.session.auto_start'=>'php.ini : session.auto_start doit être à off',
        'ini.safe_mode'         =>'php.ini : le safe_mode n\'est pas recommandé pour jelix.',
        'ini.register_globals'  =>'php.ini : il faut désactiver register_globals, pour des raisons de sécurité et parce que Jelix n\'en a pas besoin.',
        'ini.asp_tags'          =>'php.ini :  il est conseillé de désactiver asp_tags. Jelix n\'en a pas besoin.',
        'ini.short_open_tag'    =>'php.ini :  il est conseillé de désactiver short_open_tag. Jelix n\'en a pas besoin.',
        'ini.ok'                =>'Les paramètres de php sont ok',

        'module.unknown'        =>'Module inconnu',
        'module.circular.dependency'=>"Dépendance circulaire ! le composant %s ne peut être installé",
        'module.needed'         =>'Pour installer le module %s, ces modules doivent être présent : %s',
        'module.bad.jelix.version'=>'Le module %s necessite une autre version de jelix (%s - %s)',
        'module.bad.dependency.version'=>'Le module %s necessite une autre version du module %s (%s - %s)',
        'module.installer.class.not.found'=>'La classe d\'installation %s pour le module %s n\'existe pas',
        'module.upgrader.class.not.found'=>'La classe de mise à jour %s pour le module %s n\'existe pas',
        
        'install.entrypoint.start'  =>'Installation pour le point d\'entrée %s',
        'install.entrypoint.end'    =>'Tout les modules sont installés ou mise à jour pour le point d\'entrée %s',
        'install.entrypoint.bad.end'=>'Installation interrompue pour cause d\'erreurs pour le point d\'entrée %s',
        
        'install.dependencies.ok'   =>'Toutes les dépendances des modules sont valides',
        'install.bad.dependencies'  =>'Il y a des erreurs dans les dépendances. Installation annulée.',
        'install.invalid.xml.file'  =>'Le fichier identité %s est invalide ou inexistant',
        
        'install.module.already.installed'  =>'Le module %s déjà installé',
        'install.module.installed'          =>'Le module %s est installé',
        'install.module.error'              =>'Une erreur est survenue durant l\'installation du module %s: %s',
        'install.module.check.dependency'   =>'Vérifie les dépendances du module %s',
        'install.module.upgraded'           =>'Le module %s est mis à jour à la version %s',


        ),

        'en'=>array(
  'checker.title'   =>'Check your configuration server for Jelix 1.2pre.1529',
        'number.errors'     =>' errors.',
        'number.error'      =>' error.',
        'number.warnings'   =>' warnings.',
        'number.warning'    =>' warning.',
        'number.notices'    =>' notices.',
        'number.notice'     =>' notice.',
      'conclusion.error'    =>'You must fix the error in order to run an application correctly with Jelix 1.2pre.1529.',
      'conclusion.errors'   =>'You must fix errors in order to run an application correctly with Jelix 1.2pre.1529.',
      'conclusion.warning'  =>'Your application for Jelix 1.2pre.1529 may run without problems, but it is recommanded to fix the warning.',
      'conclusion.warnings' =>'Your application for Jelix 1.2pre.1529 may run without problems, but it is recommanded to fix warnings.',
      'conclusion.notice'   =>'You can install an application for Jelix 1.2pre.1529, although there is a notice.',
      'conclusion.notices'  =>'You can install an application for Jelix 1.2pre.1529, although there are notices.',
      'conclusion.ok'       =>'You can install an application for Jelix 1.2pre.1529.',
        'cannot.continue'       =>'Cannot continue the checking: ',
        'extension.dom'         =>'DOM extension is not installed',
        'extension.spl'         =>'SPL extension is not installed',
        'extension.simplexml'   =>'simplexml extension is not installed',
        'extension.pcre'        =>'pcre extension is not installed',
        'extension.session'     =>'session extension is not installed',
        'extension.tokenizer'   =>'tokenizer extension is not installed',
        'extension.iconv'       =>'iconv extension is not installed',
        'extensions.required.ok'=>'All needed PHP extensions are installed',
        'extension.filter'      =>'This Jelix edition require the filter extension',
        'extension.json'        =>'This Jelix edition require the json extension',
        'extension.xmlrpc'      =>'This Jelix edition require the xmlrpc extension',
        'extension.jelix'       =>'This Jelix edition require the jelix extension',
        'extension.opcode.cache'=>'This Jelix edition require an extension for opcode cache (apc, eaccelerator...)',
        'path.core'             =>'jelix init.php file or application.ini.php file is not loaded',
        'path.temp'             =>'temp/yourApp directory is not writable or JELIX_APP_TEMP_PATH is not correctly set !',
        'path.log'              =>'var/log directory (in the directory of your application) is not writable or JELIX_APP_LOG_PATH is not correctly set!',
        'path.var'              =>'JELIX_APP_VAR_PATH is not correctly set: var directory  doesn\'t exist!',
        'path.config'           =>'JELIX_APP_CONFIG_PATH is not correctly set: config directory  doesn\'t exist!',
        'path.www'              =>'JELIX_APP_WWW_PATH is not correctly set: www directory  doesn\'t exist!',
        'php.bad.version'       =>'Bad PHP version',
        'php.version.current'   =>'Current version:',
        'php.version.required'  =>'This edition of Jelix require at least PHP ',
        'too.critical.error'    =>'Too much critical errors. Fix them.',
        'config.file'           =>'$config_file variable does not exist or doesn\'t contain a correct application config file name',
        'paths.ok'              =>'temp, log, var, config and www directory are ok',
        'ini.magic_quotes_gpc_with_plugin'=>'php.ini : the magicquotes plugin is actived but you should set magic_quotes_gpc to off',
        'ini.magicquotes_plugin_without_php'=>'php.ini : the magicquotes plugin is actived whereas magic_quotes_gpc is already off, you should disable the plugin',
        'ini.magic_quotes_gpc'  =>'php.ini : magicquotes are not recommended for Jelix. You should deactivate it or activate the magicquote jelix plugin',
        'ini.magic_quotes_runtime'=>'php.ini : magic_quotes_runtime must be off',
        'ini.session.auto_start'=>'php.ini : session.auto_start must be off',
        'ini.safe_mode'         =>'php.ini : safe_mode is not recommended.',
        'ini.register_globals'  =>'php.ini : you must deactivate register_globals, for security reasons, and because Jelix doesn\'t need it.',
        'ini.asp_tags'          =>'php.ini :  you should deactivate  asp_tags. Jelix doesn\'t need it.',
        'ini.short_open_tag'    =>'php.ini :  you should deactivate short_open_tag. Jelix doesn\'t need it.',
        'ini.ok'                =>'php settings are ok',

        'module.unknown'        =>'Unknown module %s',
        'module.circular.dependency'=>"Circular dependency ! Cannot install the component %s",
        'module.needed'         =>'To install %s these modules are needed: %s',
        'module.bad.jelix.version'=>'The module %s needs another jelix version (%s - %s)',
        'module.bad.dependency.version'=>'The module %s needs another version of the module %s (%s - %s)',
        'module.installer.class.not.found'=>'The installation class %s for the module %s doesn\'t exist',
        'module.upgrader.class.not.found'=>'The upgrade class %s for the module %s doesn\'t exist',

        'install.entrypoint.start'  =>'Installation starts for the entry point %s',
        'install.entrypoint.end'    =>'All modules are installed or upgraded for the entry point %s',
        'install.entrypoint.bad.end'=>'Installation/upgrade is aborted for the entry point %s',

        'install.dependencies.ok'   =>'All modules dependencies are ok',
        'install.bad.dependencies'  =>'Error in dependencies. Installation cancelled.',
        'install.invalid.xml.file'  =>'The identity file  %s is invalid or not found',

        'install.module.already.installed'=>'Module %s is already installed',
        'install.module.installed'      =>'Module %s installed',
        'install.module.error'          =>'An error occured during the installation of the module %s: %s',
        'install.module.check.dependency'=>'Check dependencies of the module %s',
        'install.module.upgraded'       =>'Module %s upgraded to the version %s',

        ),
    );

    function __construct($lang=''){
        if($lang == '' && isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])){
            $languages = explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE']);
            foreach($languages as $bl){
                // pour les user-agents qui livrent un code internationnal
                if(preg_match("/^([a-zA-Z]{2})(?:[-_]([a-zA-Z]{2}))?(;q=[0-9]\\.[0-9])?$/",$bl,$match)){
                    $lang = strtolower($match[1]);
                    break;
                }
            }
        }elseif(preg_match("/^([a-zA-Z]{2})(?:[-_]([a-zA-Z]{2}))?$/",$lang,$match)){
            $lang = strtolower($match[1]);
        }
        if($lang == '' || !isset($this->messages[$lang])){
            $lang = 'en';
        }
        $this->currentLang = $lang;
    }

    function get($key, $params = null){
        if(isset($this->messages[$this->currentLang][$key])){
            $msg = $this->messages[$this->currentLang][$key];
        }else{
            throw new Exception ("Error : don't find error message '$key'");
        }
        
        if ($params !== null) {
            $msg = call_user_func_array('sprintf', array_merge (array ($msg), is_array ($params) ? $params : array ($params)));
        }
        return $msg;
    }

    function getLang(){
        return $this->currentLang;
    }
}


/**
* check a jelix installation
*
* @package  jelix
* @subpackage core
* @author   Laurent Jouanneau
* @contributor Bastien Jaillot
* @contributor Olivier Demah, Brice Tence
* @copyright 2007-2009 Laurent Jouanneau, 2008 Bastien Jaillot, 2009 Olivier Demah, 2010 Brice Tence
* @link     http://www.jelix.org
* @licence  GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
* @since 1.0b2
*/

/**
 * check an installation of a jelix application
 * @package  jelix
 * @subpackage core
 * @since 1.0b2
 */
class jInstallCheck {

    /**
     * the object responsible of the results output
     * @var jIInstallReporter
     */
    protected $reporter;

    /**
     * @var jInstallerMessageProvider
     */
    public $messages;

    public $nbError = 0;
    public $nbOk = 0;
    public $nbWarning = 0;
    public $nbNotice = 0;

    protected $buildProperties;

    function __construct ($reporter, $lang=''){
        $this->reporter = $reporter;
        $this->messages = new jInstallerMessageProvider($lang);
        $this->buildProperties = array(
   'PHP_VERSION_TARGET'=>'5.2', 
   'ENABLE_PHP_FILTER' =>'1', 
   'ENABLE_PHP_JSON'   =>'1', 
   'ENABLE_PHP_JELIX'  =>'', 
   'WITH_BYTECODE_CACHE'=>'auto',
        );
    }

    function run(){
        $this->nbError = 0;
        $this->nbOk = 0;
        $this->nbWarning = 0;
        $this->nbNotice = 0;
        $this->reporter->start();
        try {
            $this->checkPhpExtensions();
            $this->checkPhpSettings();
        }catch(Exception $e){
            $this->error('cannot.continue',$e->getMessage());
        }
        $results = array('error'=>$this->nbError, 'warning'=>$this->nbWarning, 'ok'=>$this->nbOk,'notice'=>$this->nbNotice);
        $this->reporter->end($results);
    }

    protected function error($msg, $extraMsg=''){
        if($this->reporter)
            $this->reporter->message($this->messages->get($msg).$extraMsg, 'error');
        $this->nbError ++;
    }

    protected function ok($msg){
        if($this->reporter)
            $this->reporter->message($this->messages->get($msg), 'ok');
        $this->nbOk ++;
    }
    /**
     * generate a warning
     * @param string $msg  the key of the message to display
     */
    protected function warning($msg){
        if($this->reporter)
            $this->reporter->message($this->messages->get($msg), 'warning');
        $this->nbWarning ++;
    }

    protected function notice($msg){
        if($this->reporter) {
            $this->reporter->message($this->messages->get($msg), 'notice');
        }
        $this->nbNotice ++;
    }

    function checkPhpExtensions(){
        $ok=true;
        if(!version_compare($this->buildProperties['PHP_VERSION_TARGET'], phpversion(), '<=')){
            $this->error('php.bad.version');
            $notice = $this->messages->get('php.version.required')
                     .$this->buildProperties['PHP_VERSION_TARGET'];
            $notice.= '. '.$this->messages->get('php.version.current').phpversion();
            $this->reporter->showNotice($notice);
            $ok=false;
        }
        if(!class_exists('DOMDocument',false)){
            $this->error('extension.dom');
            $ok=false;
        }
        if(!class_exists('DirectoryIterator',false)){
            $this->error('extension.spl');
            $ok=false;
        }

        $funcs=array(
            'simplexml_load_file'=>'simplexml',
            'preg_match'=>'pcre',
            'session_start'=>'session',
            'token_get_all'=>'tokenizer',
            'iconv_set_encoding'=>'iconv',
        );
        foreach($funcs as $f=>$name){
            if(!function_exists($f)){
                $this->error('extension.'.$name);
                $ok=false;
            }
        }
        if($this->buildProperties['ENABLE_PHP_FILTER'] == '1' && !extension_loaded ('filter')) {
            $this->error('extension.filter');
            $ok=false;
        }
        if($this->buildProperties['ENABLE_PHP_JSON'] == '1' && !extension_loaded ('json')) {
            $this->error('extension.json');
            $ok=false;
        }
        /*if($this->buildProperties['ENABLE_PHP_XMLRPC'] == '1' && !extension_loaded ('xmlrpc')) {
            $this->error('extension.xmlrpc');
            $ok=false;
        }*/
        if($this->buildProperties['ENABLE_PHP_JELIX'] == '1' && !extension_loaded ('jelix')) {
            $this->error('extension.jelix');
            $ok=false;
        }
        if($this->buildProperties['WITH_BYTECODE_CACHE'] != 'auto' &&
           $this->buildProperties['WITH_BYTECODE_CACHE'] != '') {
            if(!extension_loaded ('apc') && !extension_loaded ('eaccelerator') && !extension_loaded ('xcache')) {
                $this->error('extension.opcode.cache');
                $ok=false;
            }
        }
        if($ok)
            $this->ok('extensions.required.ok');

        return $ok;
    }

    function checkPhpSettings(){
        $ok = true;
            if(ini_get('magic_quotes_gpc') == 1){
                $this->warning('ini.magic_quotes_gpc');
                $ok=false;
            }
        if(ini_get('magic_quotes_runtime') == 1){
            $this->error('ini.magic_quotes_runtime');
            $ok=false;
        }

        if(ini_get('session.auto_start') == 1){
            $this->error('ini.session.auto_start');
            $ok=false;
        }

        if(ini_get('safe_mode') == 1){
            $this->warning('safe_mode');
            $ok=false;
        }

        if(ini_get('register_globals') == 1){
            $this->warning('ini.register_globals');
            $ok=false;
        }

        if(ini_get('asp_tags') == 1){
            $this->notice('ini.asp_tags');
        }
        if(ini_get('short_open_tag') == 1){
            $this->notice('ini.short_open_tag');
        }
        if($ok){
            $this->ok('ini.ok');
        }
        return $ok;
    }
}

/**
 * an HTML reporter for jInstallChecker
 * @package jelix
 */
class jHtmlInstallChecker implements jIInstallReporter {

    function start(){
        echo '<ul class="checkresults">';
    }

    function message($message, $type=''){
        echo '<li class="'.$type.'">'.htmlspecialchars($message).'</li>';
    }
    
    function end($results){
        echo '</ul>';
        
        $nbError = $results['error'];
        $nbWarning = $results['warning'];
        $nbNotice = $results['notice'];

        echo '<div class="results">';
        if ($nbError) {
            echo ' '.$nbError. $this->messageProvider->get( ($nbError > 1?'number.errors':'number.error'));
        }
        if ($nbWarning) {
            echo ' '.$nbWarning. $this->messageProvider->get(($nbWarning > 1?'number.warnings':'number.warning'));
        }
        if ($nbNotice) {
            echo ' '.$nbNotice. $this->messageProvider->get(($nbNotice > 1?'number.notices':'number.notice'));
        }

        if($nbError){
            echo '<p>'.$this->messageProvider->get(($nbError > 1?'conclusion.errors':'conclusion.error')).'</p>';
        }else if($nbWarning){
            echo '<p>'.$this->messageProvider->get(($nbWarning > 1?'conclusion.warnings':'conclusion.warning')).'</p>';
        }else if($nbNotice){
            echo '<p>'.$this->messageProvider->get(($nbNotice > 1?'conclusion.notices':'conclusion.notice')).'</p>';
        }else{
            echo '<p>'.$this->messageProvider->get('conclusion.ok').'</p>';
        }
        echo "</div>";
    }
}

$reporter = new jHtmlInstallChecker();
$check = new jInstallCheck($reporter);
$reporter->messageProvider = $check->messages;

header("Content-type:text/html;charset=UTF-8");

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $check->messages->getLang(); ?>" lang="<?php echo $check->messages->getLang(); ?>">
<head>
    <meta content="text/html; charset=UTF-8" http-equiv="content-type"/>
    <title><?php echo htmlspecialchars($check->messages->get('checker.title')); ?></title>

    <style type="text/css">

body {  
  font-family: Verdana, Arial, Sans; 
  font-size:0.8em;
  margin:0;
  background-color:#eff4f6;
  color : #002830;
  padding:0 1em;
}
a { color:#3f6f7a; text-decoration:underline; }
a:visited { color : #002830;}
a:hover { color: #0f82af; background-color: #d7e7eb; }
h1.apptitle {
    font-size: 1.7em;
    background:-moz-linear-gradient(top, #2b4c53,#27474E 40%, #244148 60%, #002830);
    background-color:#002830;
    color:white;
    margin: 32px auto 1em auto;
    padding: 0.5em;
    width: 600px;
    -moz-border-radius:5px;
    -webkit-border-radius:5px; 
    -o-border-radius:5px;
    border-radius:5px ;
    z-index:100;
    -moz-box-shadow: #999 3px 3px 8px 0px;
    -webkit-box-shadow: #999  3px 3px 8px;
    -o-box-shadow: #999 3px 3px 8px 0px;
    box-shadow: #999 3px 3px 8px 0px;
}

h1.apptitle span.welcome { font-size:0.8em; font-style:italic; }
ul.checkresults { border:3px solid black; margin: 2em; padding:1em; list-style-type:none; }
ul.checkresults li { margin:0; padding:5px; border-top:1px solid black; }
ul.checkresults li:first-child {border-top:0px}
li.error, p.error  { background-color:#ff6666;}
li.ok, p.ok      { background-color:#a4ffa9;}
li.warning { background-color:#ffbc8f;}
li.notice { background-color:#DBF0FF;}
.logo { margin:6px 0; text-align:right;}
.nocss { display: none; }
#page { margin: 0 auto; width: 924px; }
div.block h2 {
  color:white;
  vertical-align:bottom;
  margin:0;
  padding:10px 0px 5px 10px;
  background:-moz-linear-gradient(top, #87B2C3,#5595AF, #3c90af);
  background-color:#3c90af;
  background-position:center left;
  background-repeat:no-repeat;
  -moz-border-radius:15px 15px 0px  0px ;
  -o-border-radius:15px 15px 0px  0px ;
  -webkit-border-top-right-radius: 15px;
  -webkit-border-top-left-radius: 15px; 
  border-radius:15px 15px 0px  0px ;
  -moz-box-shadow: #999 3px 3px 8px 0px;
  -webkit-box-shadow: #999 3px 3px 8px;
  -o-box-shadow: #999 3px 3px 8px 0px;
  box-shadow: #999 3px 3px 8px 0px;
  z-index:50;
}
div.block h3 {
  color:#C03033;
}

div.block .blockcontent {
  background: white;
  padding: 1em 2em;
  margin-bottom: 20px;
  -moz-box-shadow: #999 3px 3px 8px 0px;
  -webkit-box-shadow: #999  3px 3px 8px;
  -o-box-shadow: #999 3px 3px 8px 0px;
  box-shadow: #999 3px 3px 8px 0px;
  -moz-border-radius:0px 0px 15px 15px;
  -webkit-border-bottom-left-radius: 15px; 
  -webkit-border-bottom-right-radius: 15px; 
  -o-border-radius:0px 0px 15px 15px;
  border-radius:0px 0px 15px 15px;
}

div#jelixpowered {
    text-align:center;
    margin: 0 auto;
}

</style>

</head><body >
    <h1 class="apptitle"><?php echo htmlspecialchars($check->messages->get('checker.title')); ?></h1>

<?php $check->run(); ?>
</body>
</html>
