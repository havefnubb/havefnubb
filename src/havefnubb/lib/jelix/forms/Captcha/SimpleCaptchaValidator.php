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
class SimpleCaptchaValidator implements CaptchaValidatorInterface{
	public function initOnDisplay(){
		$numbers=\jLocale::get('jelix~captcha.number');
		$id=rand(1,intval($numbers));
		return array(
			'question'=>\jLocale::get('jelix~captcha.question.'.$id),
			'expectedresponse'=>\jLocale::get('jelix~captcha.response.'.$id)
		);
	}
	public function validate($value,$internalData){
		if(trim($value)==''){
			return \jForms::ERRDATA_REQUIRED;
		}elseif(!$internalData||
				!is_array($internalData)||
				! isset($internalData['expectedresponse'])||
				$value!=$internalData['expectedresponse']){
			return \jForms::ERRDATA_INVALID;
		}
		return null;
	}
}
