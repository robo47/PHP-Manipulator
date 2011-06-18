#!/usr/bin/env php
<?php

use PHP\Manipulator\Cli;
use Symfony\Component\ClassLoader\UniversalClassLoader;

// fallback to symfony if not exists via PEAR
set_include_path(implode(array(get_include_path(), __DIR__ . '/symfony/src/'), PATH_SEPARATOR));

// @todo use http://de3.php.net/manual/en/function.stream-resolve-include-path.php ? would make dependency 5.3.2!
function findIncludePathForFile($file)
{
    $includePaths = explode(PATH_SEPARATOR, get_include_path());
    $pearPath = null;
    foreach($includePaths as $path) {
        if (file_exists($path . DIRECTORY_SEPARATOR . $file)) {
            $pearPath = $path;
            break;
        }
    }
    return $pearPath;
}

$symfonyPath = findIncludePathForFile('Symfony/Component/ClassLoader/UniversalClassLoader.php');
if ($symfonyPath === null) {
    echo 'ERROR: PEAR-Path for Symfony not found!';
    exit(1);
}

$manipulatorPath = findIncludePathForFile('PHP/Manipulator.php');
if ($symfonyPath === null) {
    echo 'ERROR: PEAR-Path for PHP\Manipulator not found!';
    exit(1);
}

// Autoloader
require_once 'Symfony/Component/ClassLoader/UniversalClassLoader.php';
$classLoader = new UniversalClassLoader();
$classLoader->registerNamespace('Symfony', $symfonyPath);
$classLoader->registerNamespace('PHP', $manipulatorPath);
$classLoader->register();

// Cli
require 'PHP/Manipulator/Cli.php';
$cli = new Cli();
$cli->run();