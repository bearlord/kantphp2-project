<?php

namespace Kant\Elfinder;

use Kant\Kant;
use Kant\View\AssetBundle;
use Kant\View\JqueryAsset;

class Assets extends AssetBundle
{
	public $sourcePath = '@vendor/studio-42/elfinder';

	public $publishOptions = [
        'except' => [
            'php/',
            'files/',
        ]
	];

	public $css = array(
		'css/elfinder.min.css',
		'css/theme.css',
	);
	public $js = array(
		'js/elfinder.min.js',
		'js/extras/editors.default.min.js'
	);
	public $depends = array(
		'Kant\Elfinder\JQueryUiAssets',
	);

	/**
	 * @param string $lang
	 * @param \yii\web\View $view
	 */
	public static function addLangFile($lang, $view){
		$lang = ElFinder::getSupportedLanguage($lang);

		if ($lang !== false && $lang !== 'en'){
			$view->registerJsFile(self::getPathUrl().'/js/i18n/elfinder.' . $lang . '.js', ['depends' => [Assets::className()]]);
		}
	}

    public static function getPathUrl(){
        return Kant::$app->assetManager->getPublishedUrl("@vendor/studio-42/elfinder");
    }

    public static function getSoundPathUrl(){
        return self::getPathUrl() . "/sounds/";
    }

	/**
	 * @param \Kant\View\View $view
	 */
	public static function noConflict($view){
		list(, $path) = Kant::$app->assetManager->publish(__DIR__ . "/assets");
		$view->registerJsFile($path . '/no.conflict.js', ['depends' => [JqueryAsset::className()]]);
	}
}
