<?php

namespace Kant\Elfinder;

use Kant\Kant;
use Kant\Helper\Html;
use Kant\Helper\Json;
use Kant\Widget\InputWidget;

class InputFile extends InputWidget
{

	public $language;
	public $filter;
	public $buttonTag = 'button';
	public $buttonName = 'Browse';
	public $buttonOptions = [];
	protected $_managerOptions;
	public $width = 'auto';
	public $height = 'auto';
	public $template = '{input}{button}{preview}';
	public $controller = 'elfinder';
	public $path; // work with PathController
	public $multiple = true;
	public $startPath;

	public function init()
	{
		parent::init();

		if (empty($this->language)) {
			$this->language = ElFinder::getSupportedLanguage(Kant::$app->language);
		}

		if (empty($this->buttonOptions['id'])) {
			$this->buttonOptions['id'] = $this->options['id'] . '_button';
		}
		$this->buttonOptions['type'] = 'button';

		$managerOptions = [];
		if (!empty($this->filter)) {
			$managerOptions['filter'] = $this->filter;
		}

		$managerOptions['callback'] = $this->options['id'];

		if (!empty($this->language)) {
			$managerOptions['lang'] = $this->language;
		}

		if (!empty($this->multiple)) {
			$managerOptions['multiple'] = $this->multiple;
		}

		if (!empty($this->path)) {
			$managerOptions['path'] = $this->path;
		}
		$params = $managerOptions;
		if (!empty($this->startPath)) {
			$params['#'] = ElFinder::genPathHash($this->startPath);
		}

		$this->_managerOptions['url'] = ElFinder::getManagerUrl($this->controller, $params);
		$this->_managerOptions['width'] = $this->width;
		$this->_managerOptions['height'] = $this->height;
		$this->_managerOptions['id'] = $this->options['id'];
	}

	/**
	 * Runs the widget.
	 */
	public function run()
	{
		if ($this->hasModel()) {
			$replace['{input}'] = Html::activeTextInput($this->model, $this->attribute, $this->options);
		} else {
			$replace['{input}'] = Html::textInput($this->name, $this->value, $this->options);
		}

		$replace['{button}'] = Html::tag($this->buttonTag, $this->buttonName, $this->buttonOptions);

		$replace['{preview}'] = $this->renderPreview($this->model, $this->attribute);

		echo strtr($this->template, $replace);

		AssetsCallBack::register($this->getView());

		if (!empty($this->multiple))
			$this->getView()->registerJs("mihaildev.elFinder.register(" . Json::encode($this->options['id']) . ", function(files, id){ var _f = []; for (var i in files) { _f.push(files[i].url); } \$('#' + id).val(_f.join(', ')).trigger('change', [files, id]); return true;}); $(document).on('click','#" . $this->buttonOptions['id'] . "', function(){mihaildev.elFinder.openManager(" . Json::encode($this->_managerOptions) . ");});");
		else {
			$this->getView()->registerJs("mihaildev.elFinder.register(" . Json::encode($this->options['id']) . ", function(file, id){ \$('#' + id).val(file.url).trigger('change', [file, id]);; return true;}); $(document).on('click', '#" . $this->buttonOptions['id'] . "', function(){mihaildev.elFinder.openManager(" . Json::encode($this->_managerOptions) . ");});");
		}
	}

	protected function renderPreview($model, $attribute)
	{
		$src = 'aa';
		$eles = [];
		if (($value = $model->$attribute)) {
			$src = $this->validateUrl($value) ? $value : Yii::$app->params['domain'] . $value;
		}

		$eles[] = Html::img($src, ['class' => 'img-responsive img-thumbnail cus-img']);
		$eles[] = Html::tag('em', 'x', ['class' => 'close delImage', 'title' => '删除这张图片']);

		return Html::tag('div', implode("\n", $eles), ['class' => 'input-group', 'style' => 'margin-top:.5em;']);
	}

	/**
	 * validate `$value` is url
	 */
	private function validateUrl($value)
	{
		$pattern = '/^{schemes}:\/\/(([A-Z0-9][A-Z0-9_-]*)(\.[A-Z0-9][A-Z0-9_-]*)+)(?::\d{1,5})?(?:$|[?\/#])/i';
		$validSchemes = ['http', 'https'];
		$pattern = str_replace('{schemes}', '(' . implode('|', $validSchemes) . ')', $pattern);
		if (!preg_match($pattern, $value)) {
			return false;
		}
		return true;
	}

}
