<?php
/**
 * Date: 23.01.14
 * Time: 0:51
 */

namespace Kant\Elfinder;


use Kant\View\AssetBundle;

class AssetsCallBack extends AssetBundle{
	public $js = array(
		'elfinder.callback.js'
	);
	public $depends = array(
		'Kant\View\JqueryAsset'
	);

	public function init()
	{
		$this->sourcePath = __DIR__."/assets";
		parent::init();
	}
} 