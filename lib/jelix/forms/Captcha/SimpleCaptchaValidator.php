<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/

namespace jelix\forms\Captcha;
class SimpleCaptchaValidator implements CaptchaValidatorInterface{
	/**
     * called by the widget to initialize some data when the form is generated
     *
     * It can returns some data that can be useful for the widget, and which will
     * be passed to validate() method ($internalData)
     * @return mixed
     */
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
