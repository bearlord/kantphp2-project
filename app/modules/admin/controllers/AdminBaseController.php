<?php

namespace app\modules\admin\controllers;

use Kant\Kant;

class AdminBaseController extends \Kant\Web\Controller
{

	public $layout = 'main';
	
	public $subtitle;

	public function beforeActions($action)
	{
		$this->view->title = Kant::t('app', 'Kant System');
		$this->view->subtitle = $this->subtitle;

		if (Kant::$app->admin->isGuest) {
			return $this->redirect('/admin/console');
		}
		return parent::beforeActions($action);
	}

}
