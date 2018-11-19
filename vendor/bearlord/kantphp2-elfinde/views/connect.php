<?php
/**
 * @var array $options
 * @var array $plugin
 */

define('ELFINDER_IMG_PARENT_URL', Kant\Elfinder\Assets::getPathUrl());

// run elFinder
$connector = new elFinderConnector(new Kant\Elfinder\elFinderApi($options, $plugin));
$connector->run();