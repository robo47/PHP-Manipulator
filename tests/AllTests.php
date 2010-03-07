<?php

require_once 'TestHelper.php';
require_once 'PHPUnit/Framework/TestSuite.php';
require_once 'PHP/FormatterTest.php';
require_once 'PHP/Formatter/TokenContainerTest.php';
require_once 'PHP/Formatter/TokenTest.php';
require_once 'PHP/Formatter/UtilTest.php';

// Rules
require_once 'PHP/Formatter/Rule/InterfaceTest.php';
require_once 'PHP/Formatter/Rule/AbstractTest.php';
require_once 'PHP/Formatter/Rule/CommentOutIncludesAndRequiresTest.php';
require_once 'PHP/Formatter/Rule/RemoveCommentsTest.php';
require_once 'PHP/Formatter/Rule/RemoveIndentionTest.php';
require_once 'PHP/Formatter/Rule/RemoveMultipleEmptyLinesTest.php';
require_once 'PHP/Formatter/Rule/RemoveTrailingWhitespaceTest.php';

// TokenConstraints
require_once 'PHP/Formatter/TokenConstraint/InterfaceTest.php';
require_once 'PHP/Formatter/TokenConstraint/BeginsWithNewlineTest.php';
require_once 'PHP/Formatter/TokenConstraint/IsMultilineCommentTest.php';
require_once 'PHP/Formatter/TokenConstraint/IsSingleNewlineTest.php';

// ContainerConstraints
require_once 'PHP/Formatter/ContainerConstraint/InterfaceTest.php';

// TokenManipulators
require_once 'PHP/Formatter/TokenManipulator/InterfaceTest.php';
require_once 'PHP/Formatter/TokenManipulator/RemoveBeginNewlineTest.php';

// ContainerManipulators
require_once 'PHP/Formatter/ContainerManipulator/InterfaceTest.php';
require_once 'PHP/Formatter/ContainerManipulator/CreateMultilineCommentFromTokenToTokenTest.php';

class AllTests
{
    public static function suite()
    {
        $suite = new PHPUnit_Framework_TestSuite('PHP_Formatter Test Suite');

        $suite->addTestSuite('PHP_FormatterTest');
        $suite->addTestSuite('PHP_Formatter_TokenContainerTest');
        $suite->addTestSuite('PHP_Formatter_TokenTest');
        $suite->addTestSuite('PHP_Formatter_UtilTest');

        // Rules
        $suite->addTestSuite('PHP_Formatter_Rule_InterfaceTest');
        $suite->addTestSuite('PHP_Formatter_Rule_AbstractTest');
        $suite->addTestSuite('PHP_Formatter_Rule_CommentOutIncludesAndRequiresTest');
        $suite->addTestSuite('PHP_Formatter_Rule_RemoveCommentsTest');
        $suite->addTestSuite('PHP_Formatter_Rule_RemoveIndentionTest');
        $suite->addTestSuite('PHP_Formatter_Rule_RemoveMultipleEmptyLinesTest');
        $suite->addTestSuite('PHP_Formatter_Rule_RemoveTrailingWhitespaceTest');

        // TokenConstraints
        $suite->addTestSuite('PHP_Formatter_TokenConstraint_InterfaceTest');
        $suite->addTestSuite('PHP_Formatter_TokenConstraint_BeginsWithNewlineTest');
        $suite->addTestSuite('PHP_Formatter_TokenConstraint_IsMultilineCommentTest');
        $suite->addTestSuite('PHP_Formatter_TokenConstraint_IsSingleNewlineTest');

        // ContainerConstraints
        $suite->addTestSuite('PHP_Formatter_ContainerConstraint_InterfaceTest');

        // TokenManipulators
        $suite->addTestSuite('PHP_Formatter_TokenManipulator_InterfaceTest');
        $suite->addTestSuite('PHP_Formatter_TokenManipulator_RemoveBeginNewlineTest');

        // ContainerManipulators
        $suite->addTestSuite('PHP_Formatter_ContainerManipulator_InterfaceTest');
        $suite->addTestSuite('PHP_Formatter_ContainerManipulator_CreateMultilineCommentFromTokenToTokenTest');

        return $suite;
    }
}