<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/

namespace jelix\forms\Captcha;
interface CaptchaValidatorInterface{
	/**
     * called by the widget to initialize some data when the form is generated
     *
     * It can returns some data that can be useful for the widget
     * @return mixed
     */
	public function initOnDisplay();
	public function validate($value,$internalData);
}
