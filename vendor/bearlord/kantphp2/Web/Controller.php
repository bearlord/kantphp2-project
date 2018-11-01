<?php

/**
 * @package KantPHP
 * @author  Zhenqiang Zhang <zhenqiang.zhang@hotmail.com>
 * @copyright (c) KantPHP Studio, All rights reserved.
 * @license http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
 */

namespace Kant\Web;

class Controller extends \Kant\Controller\Controller
{

	/**
	 *
	 * @var boolean whether to enable CSRF validation for the actions in this controller.
	 *      CSRF validation is enabled only when both this property and [[\Kant\Http\Request::enableCsrfValidation]] are true.
	 */
	public $enableCsrfValidation = true;

	/**
	 *
	 * @var explicit|implicit $routerPattern
	 */
	public $routePattern;

	/**
	 *
	 * @var $dispatcher
	 */
	public $dispatcher;

	

}
