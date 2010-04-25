<?php
namespace Tests;

use PHP\Manipulator\Autoloader;

error_reporting(E_ALL | E_STRICT);

if(!defined('BASE_PATH')) {
    define('BASE_PATH', realpath(dirname(__FILE__ ) . '/../'));
    define('TESTS_PATH', BASE_PATH . '/tests/');
    $paths = array();
    $paths[] = BASE_PATH . '/library/';
    $paths[] = TESTS_PATH;
    $paths[] = get_include_path();

    // Include path
    set_include_path(implode($paths, PATH_SEPARATOR));
}

// Register autoloader
require_once 'PHP/Manipulator/Autoloader.php';
Autoloader::register();