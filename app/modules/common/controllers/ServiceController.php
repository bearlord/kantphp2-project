<?php

namespace app\modules\common\controllers;

use Kant\Web\Controller;

class ServiceController extends Controller {
	
	 public $layout = 'console';

	 /**
     * @inheritdoc
     */
    public function actions() {
        return [
            'captcha' => [
                'class' => 'Kant\Captcha\CaptchaAction',
				'minLength' => 4,
				'maxLength' => 4,
				'transparent' => true,
				'backColor' => 0x666666,
				'foreColor' => 0xffb463,
				'width' => 100,
                'fixedVerifyCode' => null,
            ],
        ];
    }

}
