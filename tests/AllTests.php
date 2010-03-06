<?php

require_once 'TestHelper.php';
require_once 'PHPUnit/Framework/TestSuite.php';
require_once 'PHP/FormatterTest.php';
require_once 'PHP/Formatter/TokenContainerTest.php';
require_once 'PHP/Formatter/TokenTest.php';
require_once 'PHP/Formatter/Rule/InterfaceTest.php';
require_once 'PHP/Formatter/Rule/AbstractTest.php';
require_once 'PHP/Formatter/Rule/RemoveCommentsTest.php';
require_once 'PHP/Formatter/Rule/RemoveTrailingWhitespaceTest.php';


class AllTests
{
    public static function suite()
    {
        $suite = new PHPUnit_Framework_TestSuite('PHP_Formatter Test Suite');

        $suite->addTestSuite('PHP_FormatterTest');
        $suite->addTestSuite('PHP_Formatter_TokenContainerTest');
        $suite->addTestSuite('PHP_Formatter_TokenTest');
        $suite->addTestSuite('PHP_Formatter_Rule_InterfaceTest');
        $suite->addTestSuite('PHP_Formatter_Rule_AbstractTest');
        $suite->addTestSuite('PHP_Formatter_Rule_RemoveCommentsTest');
        $suite->addTestSuite('PHP_Formatter_Rule_RemoveTrailingWhitespaceTest');

        return $suite;
    }
}