<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
 * @package     jelix
 * @subpackage  forms
 * @author      Laurent Jouanneau
 * @copyright   2017 Laurent Jouanneau
 * @link        http://www.jelix.org
 * @licence     http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
 */
namespace jelix\forms\Captcha;
require(LIB_PATH.'recaptcha/src/autoload.php');
class ReCaptchaValidator implements CaptchaValidatorInterface{
	public function initOnDisplay(){
		return null;
	}
	public function validate($value,$internalData){
		$config=\jApp::config()->recaptcha;
		if(!isset($config['secret'])||$config['secret']==''){
			\jLog::log("secret for recaptcha is missing from the configuration","warning");
			return \jForms::ERRDATA_INVALID;
		}
		if(!isset($_POST['g-recaptcha-response'])){
			return \jForms::ERRDATA_REQUIRED;
		}
		$recaptcha=new \ReCaptcha\ReCaptcha($config['secret']);
		$resp=$recaptcha->verify($_POST['g-recaptcha-response'],$_SERVER['REMOTE_ADDR']);
		if($resp->isSuccess()){
			return null;
		}
		foreach($resp->getErrorCodes()as $code){
			if($code=='missing-input-secret'){
				\jLog::log("secret for recaptcha is missing from the google request","warning");
			}
			else if($code=='invalid-input-secret'){
				\jLog::log("secret for recaptcha is invalid","warning");
			}
		}
		return \jForms::ERRDATA_INVALID;
	}
}
