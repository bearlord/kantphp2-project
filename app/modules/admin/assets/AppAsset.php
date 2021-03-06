<?php

namespace app\modules\admin\Assets;

use Kant\Kant;
use Kant\View\AssetBundle;
use Kant\Helper\Url;

/**
 * Main frontend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'victory/node_modules/mdi/css/materialdesignicons.min.css',
		'victory/node_modules/simple-line-icons/css/simple-line-icons.css',
		'victory/node_modules/flag-icon-css/css/flag-icon.min.css',
		'victory/node_modules/perfect-scrollbar/dist/css/perfect-scrollbar.min.css',
		'victory/node_modules/font-awesome/css/font-awesome.min.css',
		'victory/node_modules/jquery-bar-rating/dist/themes/fontawesome-stars.css',
		'victory/node_modules/icheck/skins/all.css',
		'victory/node_modules/select2/dist/css/select2.min.css',
		'victory/css/style.css',
		'victory/css/fix.css',
    ];
    public $js = [
//		'/node_modules/jquery/dist/jquery.min.js',
		'victory/node_modules/popper.js/dist/umd/popper.min.js',
		'victory/node_modules/bootstrap/dist/js/bootstrap.min.js',
		'victory/node_modules/perfect-scrollbar/dist/js/perfect-scrollbar.jquery.min.js',
		'victory/node_modules/jquery-bar-rating/dist/jquery.barrating.min.js',
		'victory/node_modules/chart.js/dist/Chart.min.js',
		'victory/node_modules/raphael/raphael.min.js',
		'victory/node_modules/morris.js/morris.min.js',
		'victory/node_modules/jquery-sparkline/jquery.sparkline.min.js',
		'victory/node_modules/icheck/icheck.min.js',
		'victory/node_modules/select2/dist/js/select2.min.js',
		'victory/js/off-canvas.js',
		'victory/js/hoverable-collapse.js',
		'victory/js/misc.js',
		'victory/js/settings.js',
		'victory/js/todolist.js',
		'victory/js/dashboard.js',
		'victory/js/iCheck.js',
		'victory/js/select2.js',
    ];
    public $depends = [
        'Kant\View\KantAsset',
//        'Kant\Bootstrap\BootstrapAsset',
//        'Kant\Bootstrap\BootstrapPluginAsset',
    ];

	public function publish($am)
	{
		$this->sourcePath = Kant::getAlias('@app') . '/assets';
		$this->basePath = null;
		$this->baseUrl = null;
		parent::publish($am);
	}
}
