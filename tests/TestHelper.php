<?php
error_reporting(E_ALL | E_STRICT);

define('BASE_PATH', realpath(dirname(__FILE__ ) . '/../'));
define('TESTS_PATH', BASE_PATH . '/tests/');

$pathes = array();
$pathes[] = BASE_PATH . '/library/';
$pathes[] = TESTS_PATH;
$pathes[] = get_include_path();

// Include path
set_include_path(implode($pathes, PATH_SEPARATOR));

require_once TESTS_PATH . '/PHPFormatterTestCase.php';

/**
 * Dumps a single token
 *
 * @param array|string $token
 */
function dumpToken($token)
{
    if (is_array($token)) {
        $string = str_replace(' ', '.', $token[1]);
        $string = str_replace("\t", '\t', $string);
        $string = str_replace("\n", '\n', $string);
        $string = str_replace("\r", '\r', $string);

        echo token_name($token[0]) . ' [Line: ' . $token[2] . PHP_EOL .
            '###' . PHP_EOL .
            $string . PHP_EOL . '###' . PHP_EOL;
    } elseif(is_string($token)) {
        echo 'TOKEN: ' . $token . PHP_EOL;
    } else {
        throw new exception (' wrong type for token: ' . gettype($token));
    }
}

/**
 * Dump a TokenContainer
 *
 * @param array $tokens
 */
function dumpTokens(PHP_Formatter_TokenContainer $tokens)
{
    foreach ($tokens as $token) {
        dumpToken($token);
    }
}
