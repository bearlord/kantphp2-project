<?php

/**
 * Date: 22.01.14
 * Time: 23:44
 */

namespace Kant\Elfinder;

use Kant\Kant;
use Kant\Widget\Widget as BaseWidjet;
use Kant\Helper\ArrayHelper;
use Kant\Helper\Html;
use Kant\Helper\Json;
use Kant\Helper\Url;

/**
 * Class Widget
 * @package mihaildev\elfinder
 */
class ElFinder extends BaseWidjet
{

	public $language;
	public $filter;
	public $callbackFunction;
	public $multiple = false;
	public $path; // work with PathController
	public $startPath;
	public $containerOptions = [];
	public $frameOptions = [];
	public $controller = 'elfinder';
	
	public $roots;
	public $bind;
	public $plugin;

	public static function genPathHash($path)
	{
		if (DIRECTORY_SEPARATOR != '/') {
			$path = str_replace('/', DIRECTORY_SEPARATOR, $path);
		}

		if (preg_match('/^@(\d+)/', $path, $match)) {
			$volume = $match[1];
			$path = ltrim(substr($path, strlen($match[0])), DIRECTORY_SEPARATOR);
			if (empty($path)) {
				$path = DIRECTORY_SEPARATOR;
			}
		} else {
			$volume = 1;
		}
		$hash = rtrim(strtr(base64_encode($path), '+/=', '-_.'), '.');
		return 'elf_fls' . $volume . '_' . $hash;
	}

	public static function getManagerUrl($controller, $params = [])
	{
		$_base = '/' . $controller . "/manager";
		return Url::to($_base, $params);
	}

	public static function ckeditorOptions($controller, $options = [])
	{

		if (is_array($controller)) {
			$id = $controller[0];
			unset($controller[0]);
			$params = $controller;
		} else {
			$id = $controller;
			$params = [];
		}

		if (isset($params['startPath'])) {
			$params['#'] = ElFinder::genPathHash($params['startPath']);
			unset($params['startPath']);
		}

		return ArrayHelper::merge([
					'filebrowserBrowseUrl' => self::getManagerUrl($id, $params),
					'filebrowserImageBrowseUrl' => self::getManagerUrl($id, ArrayHelper::merge($params, ['filter' => 'image'])),
					'filebrowserFlashBrowseUrl' => self::getManagerUrl($id, ArrayHelper::merge($params, ['filter' => 'flash'])),
						], $options);
	}

	public function init()
	{
		if (empty($this->language))
			$this->language = self::getSupportedLanguage(Kant::$app->language);

		$managerOptions = [];
		if (!empty($this->filter))
			$managerOptions['filter'] = $this->filter;

		if (!empty($this->callbackFunction))
			$managerOptions['callback'] = $this->id;

		if (!empty($this->language))
			$managerOptions['lang'] = $this->language;

		if (!empty($this->path))
			$managerOptions['path'] = $this->path;

		if (!empty($this->startPath))
			$managerOptions['#'] = ElFinder::genPathHash($this->startPath);

		if ($this->multiple)
			$managerOptions['multiple'] = $this->multiple;

		$this->frameOptions['src'] = $this->getManagerUrl($this->controller, $managerOptions);

		if (!isset($this->frameOptions['style'])) {
			$this->frameOptions['style'] = "width: 100%; height: 100%; border: 0;";
		}
	}

	static function getSupportedLanguage($language)
	{
		$supportedLanguages = array('bg', 'jp', 'sk', 'cs', 'ko', 'th', 'de', 'lv', 'tr', 'el', 'nl', 'uk', 'he',
			'es', 'no', 'vi', 'fr', 'pl', 'zh_CN', 'hr', 'pt_BR', 'zh_TW', 'hu', 'ro', 'it', 'ru', 'en', 'id');

		if (!in_array($language, $supportedLanguages)) {
			if (strpos($language, '-')) {
				$language = str_replace('-', '_', $language);
				if (!in_array($language, $supportedLanguages)) {
					$language = substr($language, 0, strpos($language, '_'));
					if (!in_array($language, $supportedLanguages))
						$language = false;
				}
			} else {
				$language = false;
			}
		}

		return $language;
	}

	public function run()
	{
		$container = 'div';
		if (isset($this->containerOptions['tag'])) {
			$container = $this->containerOptions['tag'];
			unset($this->containerOptions['tag']);
		}

		echo Html::tag($container, Html::tag('iframe', '', $this->frameOptions), $this->containerOptions);

		if (!empty($this->callbackFunction)) {
			AssetsCallBack::register($this->getView());
			$this->getView()->registerJs("mihaildev.elFinder.register(" . Json::encode($this->id) . "," . Json::encode($this->callbackFunction) . ");");
		}
	}

}
