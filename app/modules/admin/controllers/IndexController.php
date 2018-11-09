<?php


namespace app\modules\admin\controllers;

use Kant\Http\Request;
use Kant\Http\Response;


class IndexController extends AdminBaseController
{
	public function indexAction()
	{
		return $this->view->render('index');
	}
}
