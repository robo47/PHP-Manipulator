#!/usr/bin/env php
<?php
/**
 * based on Fabien Potenciers create_pear_package.php from Twig:
 *
 * http://github.com/fabpot/Twig/blob/master/bin/create_pear_package.php
 */

use Symfony\Foundation\UniversalClassLoader;
use Symfony\Components\Finder\Finder;

if (!isset($argv[1]))
{
    die('You must provide the version (1.0.0)');
}

if (!isset($argv[2]))
{
    die('You must provide the stability (alpha, beta, or stable)');
}

if (!isset($argv[3]))
{
    die('You must provide the path to the directory from which the package should be created');
}

if (!isset($argv[4]))
{
    die('You must provide the path to the package.xml-template');
}

$context = array(
    'date'          => date('Y-m-d'),
    'time'          => date('H:m:00'),
    'version'       => $argv[1],
    'api_version'   => $argv[1],
    'stability'     => $argv[2],
    'api_stability' => $argv[2],
);

$context['files'] = '';
$path = realpath($argv[3]);

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

$symfonyPath = findIncludePathForFile('Symfony/Foundation/UniversalClassLoader.php');
if ($symfonyPath === null) {
    echo 'ERROR: PEAR-Path for Symonfy not found!';
    exit(1);
}

// Autoloader
require_once 'Symfony/Foundation/UniversalClassLoader.php';
$classLoader = new UniversalClassLoader();
$classLoader->registerNamespace('Symfony', $symfonyPath);
$classLoader->register();

$phpFilesFinder = new Finder();
$phpFilesFinder->files()
               ->in($path . '/PHP/');

// php-files
foreach ($phpFilesFinder as $phpFile)
{
    $name = str_replace($path.'/', '', $phpFile);
    $context['files'] .= '        <file install-as="'.$name.'" name="'.$name.'" role="php" />'."\n";
}

// docs
$docs = array(
    'LICENSE',
    'TODO',
    'README'
);

foreach($docs as $doc) {
    $context['files'] .= '        <file name="'.$doc.'" role="doc" />'."\n";
}

// tests
$testsFilesFinder = new Finder();
$testsFilesFinder->files()
                 ->in($path . '/tests/');

foreach($testsFilesFinder as $testFile) {
    $name = str_replace($path.'/', '', $testFile);
    $context['files'] .= '        <file name="'.$name.'" role="test" />'."\n";
}

// scripts
$scripts = array(
    'phpmanipulator.php',
);

foreach($scripts as $script) {
    $context['files'] .= '        <file name="'.$script.'" role="script" />'."\n";
}

$replaceParameters = function ($matches) use ($context)
{
    return isset($context[$matches[1]]) ? $context[$matches[1]] : null;
};

$template = file_get_contents($argv[4]);
$content = preg_replace_callback('/\{\{\s*([a-zA-Z0-9_]+)\s*\}\}/', $replaceParameters, $template);

file_put_contents($path . '/package.xml', $content);