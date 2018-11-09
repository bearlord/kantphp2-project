<?php


namespace app\modules\admin\controllers;

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
}
