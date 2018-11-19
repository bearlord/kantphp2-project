<?php

namespace Kant\Elfinder\Volume;

use Kant\Kant;

class UserPath extends Local
{

	public function isAvailable()
	{
		if (Kant::$app->user->isGuest) {
			return false;
		}
		return parent::isAvailable();
	}

	public function getUrl()
	{
		$path = strtr($this->path, ['{id}' => Kant::$app->user->id]);
		return Kant::getAlias($this->baseUrl . '/' . trim($path, '/'));
	}

	public function getRealPath()
	{
		$path = strtr($this->path, ['{id}' => Kant::$app->user->id]);
		$path = Kant::getAlias($this->basePath . '/' . trim($path, '/'));
		
		if (!is_dir($path)) {
			mkdir($path, 0777, true);
		}
		return $path;
	}

}
