<?php

namespace app\modules\admin\controllers;

use Kant\Kant;
use Kant\Http\Request;
use Kant\Http\Response;
use app\models\Article;

class ArticleController extends AdminBaseController
{

	/**
	 * Index
	 */
	public function indexAction()
	{
		return $this->view->render('index', [
		]);
	}

	/**
	 * Add
	 */
	public function addAction(Request $request, Response $response)
	{
		$articleModel = new Article();

		if ($request->isMethod('post')) {
			$data = $request->input();
			$articleModel->load($data);
			if ($articleModel->validate() ){
				$articleModel->save();
			}
		}
		return $this->view->render('add', [
					'articleModel' => $articleModel
		]);
	}

}
