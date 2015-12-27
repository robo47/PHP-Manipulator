<?php

namespace Tests;

error_reporting(E_ALL | E_STRICT);

require_once __DIR__ . '/../vendor/autoload.php';

define('BASE_PATH', realpath(__DIR__.'/../'));
define('TESTS_PATH', BASE_PATH.'/tests/');

if (!file_exists(TESTS_PATH.'/tmp')) {
    mkdir(TESTS_PATH.'/tmp');
}

// For all relative paths to work as expected we set current dir (cwd) to the tests paths
chdir(TESTS_PATH);
