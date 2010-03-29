<?php
error_reporting(E_ALL | E_STRICT);

define('BASE_PATH', realpath(dirname(__FILE__) . '/../'));
define('TESTS_PATH', BASE_PATH . '/tests/');

$pathes = array();
$pathes[] = BASE_PATH . '/library/';
$pathes[] = TESTS_PATH;
$pathes[] = get_include_path();

// Include path
set_include_path(implode($pathes, PATH_SEPARATOR));

// Register autoloader
require_once 'PHP/Formatter/Autoloader.php';
PHP_Formatter_Autoloader::register();