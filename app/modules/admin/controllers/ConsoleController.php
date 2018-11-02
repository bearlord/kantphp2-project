<?php

namespace app\modules\admin\controllers;

use Kant\Kant;
use Kant\Web\Controller;
use app\modules\admin\models\SigninForm;

class ConsoleController extends Controller
{

	public $layout = 'console';

	public function indexAction()
	{
		$model = new SigninForm();

		$this->view->layout = 'console';
		
        if (Kant::$app->request->isPost) {
            $post = Kant::$app->request->post();
            $model->load($post);
            if ($model->validate() && $model->login()) {
                $this->redirect(["/wpm/index"]);
            }
        }

        $this->view->title = '系统登录';
        return $this->view->render('index', [
            'model' => $model
        ]);
	}

}
