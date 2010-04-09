#!/usr/bin/env php
<?php

use PHP\Manipulator\Cli;
use PHP\Manipulator\Autoloader;

define('BASE_PATH', realpath(dirname(__FILE__) . '/../'));

// Include path
$pathes = array();
$pathes[] = BASE_PATH . '/library/';
$pathes[] = get_include_path();

set_include_path(implode($pathes, PATH_SEPARATOR));

// Autoloader
require 'PHP/Manipulator/Autoloader.php';
Autoloader::register();

// Cli
require 'PHP/Manipulator/Cli.php';
$cli = new Cli($_SERVER['argv']);
$cli->run();