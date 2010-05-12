#!/usr/bin/env php
<?php

use PHP\Manipulator\Cli;
use Symfony\Foundation\UniversalClassLoader;

define('BASE_PATH', realpath(dirname(__FILE__) . '/../'));

// Include path
$pathes = array();
$pathes[] = BASE_PATH . '/library/';
$pathes[] = get_include_path();

set_include_path(implode($pathes, PATH_SEPARATOR));

// Autoloader
require_once 'Symfony/Foundation/UniversalClassLoader.php';
$classLoader = new UniversalClassLoader();
$classLoader->registerNamespace('Symfony', BASE_PATH . '/library/');
$classLoader->registerNamespace('PHP', BASE_PATH . '/library/');
$classLoader->register();

// Cli
require 'PHP/Manipulator/Cli.php';
$cli = new Cli();
$cli->run();