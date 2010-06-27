#!/usr/bin/env php
<?php
error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', 1);


if ($_SERVER['argc'] < 3) {
    echo 'wrong argument count (less than 3)' . PHP_EOL;
    echo 'syntax: helper.php type name [fixture count]' . PHP_EOL . PHP_EOL;
    echo 'type can be:' . PHP_EOL;
    echo '  tf - TokenFinder' . PHP_EOL;
    echo '  a  - Action' . PHP_EOL;
    echo '  tc - TokenConstraint' . PHP_EOL;
    echo '  tm - TokenManipulator' . PHP_EOL . PHP_EOL;
    echo 'name is the name the class should have' . PHP_EOL . PHP_EOL;
    echo 'fixture count only applies to tokenFinder, ContainerConstraints and Actions and is the number of empty fixture-files which should be created' . PHP_EOL;
    exit(2);
}
$typeName = '';
$createFixturesDummy = false;
$inputOnly = false;
$type = strtolower($_SERVER['argv'][1]);

switch ($type) {
    case 'tokenfinder':
    case 'tf':
        $typeName = 'TokenFinder';
        $createFixturesDummy = true;
        $inputOnly = true;
        break;
    case 'action':
    case 'a':
        $typeName = 'Action';
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

$newFile = './PHP/Manipulator/' . $typeName . '/' . $name . '.php';
$newTestFile = './tests/Tests/PHP/Manipulator/' . $typeName . '/' . $name . 'Test.php';

if (file_exists($newTestFile) || file_exists($newFile)) {
    echo 'file already exists!!!';
    exit();
}

$fileCode = file_get_contents('./helper/' . $typeName . '.php');
$testCode = file_get_contents('./helper/' . $typeName . 'Test.php');

$newFilePath = '';
$newTestPath = '';

if ($createFixturesDummy && $_SERVER['argc'] > 3) {
    echo 'creating fixtures: ' . $_SERVER['argv'][3] . PHP_EOL;
    $path = './tests/_fixtures/' . $typeName . '/' . $name;
    @mkdir($path, 0755, true);
    $fixturesCount = $_SERVER['argv'][3];
    for ($i = 0; $i < $fixturesCount; $i++) {
        touch($path . '/input' . $i . '.php');
        if (!$inputOnly) {
            touch($path . '/output' . $i . '.php');
        }
    }
}

echo 'replacing variables' . PHP_EOL;

$vars = array(
    'classname' => $name,
    'completeclassname' => '\PHP\Manipulator\\' . $typeName . '\\' . $name,
    'path' => $typeName . '/' . $name,
);

foreach ($vars as $key => $value) {
    $fileCode = str_replace('__' . $key . '__', $value, $fileCode);
    $testCode = str_replace('__' . $key . '__', $value, $testCode);
}

echo 'Writing Files' . PHP_EOL;
file_put_contents($newFile, $fileCode);
file_put_contents($newTestFile, $testCode);
