<?php

$params = require_once __DIR__ . '/params.php';
$db = require_once __DIR__ . '/db.php';
return [
	'basePath' => dirname(dirname(__DIR__)),
	'session' => [
		'driver' => 'file',
		'table' => 'session',
		'cookie' => 'kant_session',
		'maxlifetime' => 1800,
	],
	'components' => [
		'db' => $db,
		'cache' => [
			'class' => 'Kant\Caching\FileCache',
		],
		'files' => [
			'default' => 'public'
		],
		'errorHandler' => [
			'errorAction' => 'site/error',
		],
		//新增管理验证
		'admin' => [
			'class' => \Kant\Identity\User::className(),
			'identityClass' => 'app\models\Admin',
			'enableAutoLogin' => true,
			'identityCookie' => [
				'name' => '_identity-admin',
				'httpOnly' => true
			],
			'loginUrl' => 'admin/console'
		],
	],
	'params' => $params,
	'controllerMap' => [
		'elfinder' => [
			'class' => 'Kant\Elfinder\PathController',
			'access' => ['@'],
			'user' => 'admin',
			'root' => [
				'path' => 'files',
				'name' => 'Files'
			],
			'watermark' => [
				'source' => __DIR__ . '/logo.png', // Path to Water mark image
				'marginRight' => 5, // Margin right pixel
				'marginBottom' => 5, // Margin bottom pixel
				'quality' => 95, // JPEG image save quality
				'transparency' => 70, // Water mark image transparency ( other than PNG )
				'targetType' => IMG_GIF | IMG_JPG | IMG_PNG | IMG_WBMP, // Target image formats ( bit-field )
				'targetMinPixel' => 200		 // Target image minimum pixel size
			]
		]
	],
];
?>
