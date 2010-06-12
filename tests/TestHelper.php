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

// @todo use http://de3.php.net/manual/en/function.stream-resolve-include-path.php ? would make dependency 5.3.2!
function findPearIncludePath()
{
    $includePaths = explode(PATH_SEPARATOR, get_include_path());
    $pearPath = null;
    foreach($includePaths as $path) {
        if (file_exists($path . DIRECTORY_SEPARATOR . 'Symfony/Foundation/UniversalClassLoader.php')) {
            $pearPath = $path;
            break;
        }
    }
    return $pearPath;
}

$pearPath = findPearIncludePath();
if ($pearPath === null) {
    echo 'ERROR: PEAR-Path for Symonfy not found!';
    exit(1);
}

require_once 'Symfony/Foundation/UniversalClassLoader.php';
$classLoader = new UniversalClassLoader();
$classLoader->registerNamespace('Symfony', $pearPath);
$classLoader->registerNamespace('PHP', BASE_PATH . '/');
$classLoader->registerNamespace('Tests', TESTS_PATH . '/');
$classLoader->registerNamespace('Baa', TESTS_PATH . '/');
$classLoader->registerNamespace('Foo', TESTS_PATH . '/');
$classLoader->register();

if (!file_exists(TESTS_PATH . '/tmp'))
{
    mkdir(TESTS_PATH . '/tmp');
}