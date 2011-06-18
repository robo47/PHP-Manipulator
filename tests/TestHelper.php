<?php

namespace Tests;

use PHP\Manipulator\Autoloader;
use Symfony\Component\ClassLoader\UniversalClassLoader;

error_reporting(E_ALL | E_STRICT);

define('BASE_PATH', realpath(dirname(__FILE__) . '/../'));
define('TESTS_PATH', BASE_PATH . '/tests/');
define('SYMFONY_PATH', BASE_PATH . '/symfony/src/');

$paths = array();
$paths[] = BASE_PATH . '/';
$paths[] = TESTS_PATH;
$paths[] = get_include_path();
$paths[] = SYMFONY_PATH;

// Include path
set_include_path(implode($paths, PATH_SEPARATOR));

require_once 'Symfony/Component/ClassLoader/UniversalClassLoader.php';

$classLoader = new UniversalClassLoader();
$classLoader->registerNamespaces(array(
    'Symfony' => SYMFONY_PATH,
    'PHP' => BASE_PATH . '/',
    'Tests' => TESTS_PATH . '/',
    'Baa' => TESTS_PATH . '/',
    'Foo' => TESTS_PATH . '/',
));
$classLoader->register();

if (!file_exists(TESTS_PATH . '/tmp')) {
    mkdir(TESTS_PATH . '/tmp');
}

// For all relative paths to work as expected we set current dir (cwd) to the tests paths
chdir(TESTS_PATH);
