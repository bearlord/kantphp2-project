<?php

namespace Kant\Elfinder;

use Kant\Kant;
use Kant\Elfinder\Volume\Local;
use Kant\Helper\ArrayHelper;
use Kant\Helper\Url;

/**
 * Class PathController
 *
 * @package mihaildev\elfinder
 */
class PathController extends BaseController
{

	public $disabledCommands = ['netmount'];
	public $root = [];
	public $watermark;
	private $_options;

	public function getOptions()
	{
		if ($this->_options !== null)
			return $this->_options;

		$subPath = Kant::$app->request->query('path', '');

		$this->_options['roots'] = [];

		$root = $this->root;

		if (is_string($root)) {
			$root = ['path' => $root];
		}

		if (!isset($root['class'])) {
			$root['class'] = Local::className();
		}
		
		if (!isset($root['path'])) {
			$root['path'] = '';
		}
		
		if (!empty($subPath)) {
			if (preg_match("/\./i", $subPath)) {
				$root['path'] = rtrim($root['path'], '/');
			} else {
				$root['path'] = rtrim($root['path'], '/');
				$root['path'] .= '/' . trim($subPath, '/');
			}
		}

		$root = Kant::createObject($root);

		/** @var Local $root */
		if ($root->isAvailable())
			$this->_options['roots'][] = $root->getRoot();

		if (!empty($this->watermark)) {
			$this->_options['bind']['upload.presave'] = 'Plugin.Watermark.onUpLoadPreSave';

			if (is_string($this->watermark)) {
				$watermark = [
					'source' => $this->watermark
				];
			} else {
				$watermark = $this->watermark;
			}

			$this->_options['plugin']['Watermark'] = $watermark;
		}

		$this->_options = ArrayHelper::merge($this->_options, $this->connectOptions);

		return $this->_options;
	}

	public function getManagerOptions()
	{
		$options = parent::getManagerOptions();
		$route = Url::normalizeRoute('connect');
		$options['url'] = Url::to($route, ['path' => Kant::$app->request->query('path', '')]);
		return $options;
	}

}
