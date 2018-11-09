<?php

namespace app\modules\admin\controllers;

use Kant\Kant;

class AdminBaseController extends \Kant\Web\Controller
{

	public $layout = 'main';

	public function beforeActions($action)
	{
		$this->view->title = Kant::t('app', 'Kant System');

		if (Kant::$app->admin->isGuest) {
			return $this->redirect('/admin/console');
		}
		return parent::beforeActions($action);
	}

}
