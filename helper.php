#!/usr/bin/env php
<?php
error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', 'On');


if ($_SERVER['argc'] < 3) {
    echo 'wrong argument count (less than 3)';
    exit(2);
}
$typeName = '';
$createFixturesDummy = false;
$inputOnly = false;
$type = strtolower($_SERVER['argv'][1]);
switch($type) {
    case 'containerconstraint':
    case 'cc':
        $typeName = 'ContainerConstraint';
        $createFixturesDummy = true;
        $inputOnly = true;
        break;
    case 'containermanipulator':
    case 'cm':
        $typeName = 'ContainerManipulator';
        $createFixturesDummy = true;
        break;
    case 'rule':
    case 'r':
        $typeName = 'Rule';
        $createFixturesDummy = true;
        break;
    case 'tokenconstraint':
    case 'tc':
        $typeName = 'TokenConstraint';
        break;
    case 'tokenmanipulator':
    case 'tm':
        $typeName = 'TokenManipulator';
        break;
    default:
        echo 'unknown type: ' . $type;
        exit(2);
}

$name = $_SERVER['argv'][2];

$fileCode = file_get_contents('./helper/' . $typeName . '.php');
$testCode = file_get_contents('./helper/' . $typeName . 'Test.php');

$newFilePath = '';
$newTestPath = '';
if($createFixturesDummy && $_SERVER['argc'] > 3) {
    echo 'creating fixtures: ' . $_SERVER['argv'][3] . PHP_EOL;
    $path = './tests/_fixtures/' . $typeName . '/' . $name;
    @mkdir($path, 0755, true);
    $fixturesCount = $_SERVER['argv'][3];
    for ($i = 0; $i < $fixturesCount; $i++) {
        touch($path . '/input' . $i);
        if (!$inputOnly) {
            touch($path . '/output' . $i);
        }
    }
}
echo 'replacing variables' . PHP_EOL;
$fileCode = str_replace('__classname__', $typeName . '_' . $name, $fileCode);
$testCode = str_replace('__classname__', $typeName . '_' . $name, $testCode);
$fileCode = str_replace('__path__', $typeName . '/' . $name, $fileCode);
$testCode = str_replace('__path__', $typeName . '/' . $name, $testCode);

echo 'Writing Files' . PHP_EOL;
file_put_contents('./library/PHP/Formatter/' . $typeName . '/' . $name . '.php', $fileCode);
file_put_contents('./tests/PHP/Formatter/' . $typeName . '/' . $name . 'Test.php', $testCode);