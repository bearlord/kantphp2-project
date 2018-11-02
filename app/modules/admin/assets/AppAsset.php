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
		'victory/css/style.css',		
    ];
    public $js = [
//		'/node_modules/jquery/dist/jquery.min.js',
		'/node_modules/popper.js/dist/umd/popper.min.js',
		'victory/node_modules/bootstrap/dist/js/bootstrap.min.js',
		'victory/node_modules/perfect-scrollbar/dist/js/perfect-scrollbar.jquery.min.js',
		'victory/node_modules/jquery-bar-rating/dist/jquery.barrating.min.js',
		'victory/node_modules/chart.js/dist/Chart.min.js',
		'victory/node_modules/raphael/raphael.min.js',
		'victory/node_modules/morris.js/morris.min.js',
		'victory/node_modules/jquery-sparkline/jquery.sparkline.min.js',
		'victory/js/off-canvas.js',
		'victory/js/hoverable-collapse.js',
		'victory/js/misc.js',
		'victory/js/settings.js',
		'victory/js/todolist.js',
		'victory/js/dashboard.js',
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

		if ($this->sourcePath !== null && ! isset($this->basePath, $this->baseUrl)) {
            list ($this->basePath, $this->baseUrl) = $am->publish($this->sourcePath, $this->publishOptions);
        }
        
        if (isset($this->basePath, $this->baseUrl) && ($converter = $am->getConverter()) !== null) {
            foreach ($this->js as $i => $js) {
                if (is_array($js)) {
                    $file = array_shift($js);
                    if (Url::isRelative($file)) {
                        $js = ArrayHelper::merge($this->jsOptions, $js);
                        array_unshift($js, $converter->convert($file, $this->basePath));
                        $this->js[$i] = $js;
                    }
                } elseif (Url::isRelative($js)) {
                    $this->js[$i] = $converter->convert($js, $this->basePath);
                }
            }
            foreach ($this->css as $i => $css) {
                if (is_array($css)) {
                    $file = array_shift($css);
                    if (Url::isRelative($file)) {
                        $css = ArrayHelper::merge($this->cssOptions, $css);
                        array_unshift($css, $converter->convert($file, $this->basePath));
                        $this->css[$i] = $css;
                    }
                } elseif (Url::isRelative($css)) {
                    $this->css[$i] = $converter->convert($css, $this->basePath);
                }
            }
        }
	}
}
