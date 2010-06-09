<?php
namespace Tests;

use PHP\Manipulator\Autoloader;
use Symfony\Foundation\UniversalClassLoader;

error_reporting(E_ALL | E_STRICT);

define('BASE_PATH', realpath(dirname(__FILE__ ) . '/../'));
define('TESTS_PATH', BASE_PATH . '/tests/');
$paths = array();
$paths[] = BASE_PATH . '/';
$paths[] = TESTS_PATH;
$paths[] = get_include_path();

// Include path
set_include_path(implode($paths, PATH_SEPARATOR));

require_once 'Symfony/Foundation/UniversalClassLoader.php';

$classLoader = new UniversalClassLoader();
$classLoader->registerNamespace('Symfony', BASE_PATH . '/');
$classLoader->registerNamespace('PHP', BASE_PATH . '/');
$classLoader->registerNamespace('Tests', TESTS_PATH . '/');
$classLoader->registerNamespace('Baa', TESTS_PATH . '/');
$classLoader->registerNamespace('Foo', TESTS_PATH . '/');
$classLoader->register();
