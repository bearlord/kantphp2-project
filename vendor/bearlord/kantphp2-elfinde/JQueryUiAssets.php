<?php

namespace Kant\Elfinder;

use Kant\Kant;
use Kant\View\AssetBundle;

class JQueryUiAssets extends AssetBundle
{

	/**
	 * {@inheritdoc}
	 */
	public $sourcePath = '@bower/jquery-ui';

	/**
	 * {@inheritdoc}
	 */
	public $js = [
		'jquery-ui.js',
	];

	/**
	 * {@inheritdoc}
	 */
	public $css = [
		'themes/smoothness/jquery-ui.css',
	];

	/**
	 * {@inheritdoc}
	 */
	public $depends = [
		'Kant\View\JqueryAsset',
	];

}
