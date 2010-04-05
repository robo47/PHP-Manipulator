#!/usr/bin/env php
<?php
error_reporting(E_ALL | E_STRICT);

define('BASE_PATH', realpath(dirname(__FILE__) . '/'));

$pathes = array();
$pathes[] = BASE_PATH . '/library/';
$pathes[] = get_include_path();

// Include path
set_include_path(implode($pathes, PATH_SEPARATOR));

// Register autoloader
require_once 'PHP/Formatter/Autoloader.php';
PHP_Formatter_Autoloader::register();

$code = file_get_contents($_SERVER['argv'][1]);
$c = PHP_Formatter_TokenContainer::createFromCode($code);

function showTokenValue($token)
{
    $val = str_replace("\n", '\n'."\n", $token->getValue());
    $val = str_replace(" ", '.', $val);
    return $val;
}

foreach($c as $token) {
    echo showTokenValue($token);
    echo "\n####\n";
}