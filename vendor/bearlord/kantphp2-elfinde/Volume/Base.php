<?php
/**
 * Date: 22.01.14
 * Time: 10:39
 */

namespace Kant\Elfinder\Volume;

use Kant\Kant;
use Kant\Foundation\BaseObject;
use Kant\Helper\ArrayHelper;
use Kant\Helper\FileHelper;


/**
 * @property array defaults
 */
class Base extends BaseObject{

	public $driver = 'LocalFileSystem';

	public $name = 'Root';

	public $options = [];

	public $access = ['read' => '*', 'write' => '*'];

	public $tmbPath;

	public $plugin = [];

	public function getAlias(){
		if(is_array($this->name)){
			return Kant::tt($this->name['category'], $this->name['message']);
		}

		return $this->name;
	}

	public function isAvailable(){
		return $this->defaults['read'];
	}

	private $_defaults;

	public function getDefaults(){
		if($this->_defaults !== null)
			return $this->_defaults;
		$this->_defaults['read'] = false;
		$this->_defaults['write'] = false;

		if(isset($this->access['write'])){
			$this->_defaults['write'] = true;
			if($this->access['write'] != '*'){
				$this->_defaults['write'] = Kant::$app->user->can($this->access['write']);
			}
		}

		if($this->_defaults['write']){
			$this->_defaults['read'] = true;
		}elseif(isset($this->access['read'])){
			$this->_defaults['read'] = true;
			if($this->access['read'] != '*'){
				$this->_defaults['read'] = Kant::$app->user->can($this->access['read']);
			}
		}

		return $this->_defaults;
	}

    protected function optionsModifier($options){
        return $options;
    }

	public function getRoot(){
		$options['driver'] = $this->driver;
		$options['plugin'] = $this->plugin;
		$options['defaults'] = $this->getDefaults();
		$options['alias'] = $this->getAlias();

		$options['tmpPath'] = Kant::getAlias('@runtime/elFinderTmpPath');
		if(!empty($this->tmbPath)){
			$this->tmbPath = trim($this->tmbPath, '/');
			$options['tmbPath'] = Kant::getAlias('@webroot/'.$this->tmbPath);
			$options['tmbURL'] = Kant::$app->request->getHttpHost() . Kant::getAlias('@web/'.$this->tmbPath);
		}else{
			$subPath = md5($this->className().'|'.serialize($this->name));
			$options['tmbPath'] = Kant::$app->assetManager->getPublishedPath(__DIR__).DIRECTORY_SEPARATOR.$subPath;
			$options['tmbURL'] = Kant::$app->request->getHttpHost() . Kant::$app->assetManager->getPublishedUrl(__DIR__). '/'. $subPath;
		}

		FileHelper::createDirectory($options['tmbPath']);


		$options['mimeDetect'] = 'internal';
		$options['imgLib'] = 'auto';
		$options['attributes'][] = [
			'pattern' => '#.*(\.tmb|\.quarantine)$#i',
			'read' => false,
			'write' => false,
			'hidden' => true,
			'locked' => false
		];

        $options = $this->optionsModifier($options);

		return ArrayHelper::merge($options, $this->options);
	}
}