<?php

namespace Tests;

use PHP\Manipulator\Autoloader;
use Symfony\Component\ClassLoader\UniversalClassLoader;

error_reporting(E_ALL | E_STRICT);

define('BASE_PATH', realpath(__DIR__ . '/../'));
define('TESTS_PATH', BASE_PATH . '/tests/');

$loader = require_once __DIR__ . '/../vendor/.composer/autoload.php';

/* @var $loader \Composer\Autoload\ClassLoader */

$loader->add('Tests', TESTS_PATH . '/');
$loader->add('Baa', TESTS_PATH . '/');
$loader->add('Foo', TESTS_PATH . '/');


if (!file_exists(TESTS_PATH . '/tmp')) {
    mkdir(TESTS_PATH . '/tmp');
}

// For all relative paths to work as expected we set current dir (cwd) to the tests paths
chdir(TESTS_PATH);