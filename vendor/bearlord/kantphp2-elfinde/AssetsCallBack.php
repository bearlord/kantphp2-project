<?php

namespace Kant\Elfinder;

use Kant\View\AssetBundle;

class AssetsCallBack extends AssetBundle
{
	public $css = [
		'elfinder.callback.css'
	];

	public $js = [
		'elfinder.callback.js',
		
	];
	
	public $depends = [
		'Kant\View\JqueryAsset'
	];

	public function init()
	{
		$this->sourcePath = __DIR__ . "/assets";
		parent::init();
	}

}
