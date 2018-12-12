<?php

namespace app\modules\admin\controllers;

use Kant\Kant;
use Kant\Http\Request;
use Kant\Http\Response;
use app\models\Article;

class ArticleController extends AdminBaseController
{

	/**
	 * article lists
	 */
	public function indexAction()
	{
		$articleModel = new Article();
		
		$this->view->params['subtitle'] = Kant::t('app','Article List');
		
		return $this->view->render('index', [
			'articleModel' => $articleModel
		]);
	}

	/**
	 * add article
	 */
	public function addAction(Request $request, Response $response)
	{
		$articleModel = new Article();

		if ($request->isMethod('post')) {
			$data = $request->input();
			$articleModel->load($data);
			if ($articleModel->validate() ){
				$row = $articleModel->save();
				if ($row) {
					return Kant::$app->redirect->to('admin/article');
				}
			}
		}
		
		$articleModel->art_state = 'drafted';
		
		$articleModel->is_link = 0;
		
		$articleModel->allow_comment = 1;
		
		$this->view->params['subtitle'] = Kant::t('app','Add Article');
		return $this->view->render('add', [
					'articleModel' => $articleModel
		]);
	}

}
