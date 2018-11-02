<?php

namespace app\modules\admin\controllers;

use Kant\Kant;
use Kant\Web\Controller;
use Kant\Http\Request;
use Kant\Http\Response;
use app\modules\admin\models\SigninForm;

class ConsoleController extends Controller
{

	public $layout = 'console';

	public function indexAction(Request $request, Response $response)
	{
		$model = new SigninForm();

		$this->view->layout = 'console';
		
        if ($request->isMethod('post')) {
            $post = $request->input();
            $model->load($post);
            if ($model->validate() && $model->login()) {
                $this->redirect("/admin/index");
            }
        }

        $this->view->title = '系统登录';
        return $this->view->render('index', [
            'model' => $model
        ]);
	}

}
