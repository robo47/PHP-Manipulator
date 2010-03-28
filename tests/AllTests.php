<?php

require_once 'TestHelper.php';
require_once 'PHPUnit/Framework/TestSuite.php';
require_once 'PHP/FormatterTest.php';
require_once 'PHP/Formatter/AbstractHelperTest.php';
require_once 'PHP/Formatter/TokenContainerTest.php';
require_once 'PHP/Formatter/TokenTest.php';
require_once 'PHP/Formatter/UtilTest.php';

// Rules
require_once 'PHP/Formatter/Rule/InterfaceTest.php';
require_once 'PHP/Formatter/Rule/AsptagsToLongTagsTest.php';
require_once 'PHP/Formatter/Rule/CommentOutIncludesAndRequiresTest.php';
require_once 'PHP/Formatter/Rule/ChangeLineEndingsTest.php';
require_once 'PHP/Formatter/Rule/FormatOperatorsTest.php';
require_once 'PHP/Formatter/Rule/IndentTest.php';
require_once 'PHP/Formatter/Rule/RemoveCommentsTest.php';
require_once 'PHP/Formatter/Rule/RemoveIndentionTest.php';
require_once 'PHP/Formatter/Rule/RemoveMultipleEmptyLinesTest.php';
require_once 'PHP/Formatter/Rule/RemoveTrailingWhitespaceTest.php';
require_once 'PHP/Formatter/Rule/ShorttagsToLongTagsTest.php';
require_once 'PHP/Formatter/Rule/StripPhpTest.php';
require_once 'PHP/Formatter/Rule/StripNonPhpTest.php';
require_once 'PHP/Formatter/Rule/ReplaceBooleanOperatorsWithLogicalOperatorsTest.php';
require_once 'PHP/Formatter/Rule/ReplaceLogicalOperatorsWithBooleanOperatorsTest.php';

// TokenConstraints
require_once 'PHP/Formatter/TokenConstraint/InterfaceTest.php';
require_once 'PHP/Formatter/TokenConstraint/BeginsWithNewlineTest.php';
require_once 'PHP/Formatter/TokenConstraint/EndsWithNewlineTest.php';
require_once 'PHP/Formatter/TokenConstraint/IsMultilineCommentTest.php';
require_once 'PHP/Formatter/TokenConstraint/IsSingleNewlineTest.php';
require_once 'PHP/Formatter/TokenConstraint/IsTypeTest.php';
require_once 'PHP/Formatter/TokenConstraint/IsClosingCurlyBraceTest.php';
require_once 'PHP/Formatter/TokenConstraint/IsOpeningCurlyBraceTest.php';
require_once 'PHP/Formatter/TokenConstraint/IsClosingBraceTest.php';
require_once 'PHP/Formatter/TokenConstraint/IsOpeningBraceTest.php';
require_once 'PHP/Formatter/TokenConstraint/IsOperatorTest.php';
require_once 'PHP/Formatter/TokenConstraint/MockTest.php';

// ContainerConstraints
require_once 'PHP/Formatter/ContainerConstraint/InterfaceTest.php';
require_once 'PHP/Formatter/ContainerConstraint/MockTest.php';

// TokenManipulators
require_once 'PHP/Formatter/TokenManipulator/InterfaceTest.php';
require_once 'PHP/Formatter/TokenManipulator/MockTest.php';
require_once 'PHP/Formatter/TokenManipulator/RemoveBeginNewlineTest.php';

// ContainerManipulators
require_once 'PHP/Formatter/ContainerManipulator/InterfaceTest.php';
require_once 'PHP/Formatter/ContainerManipulator/MockTest.php';
require_once 'PHP/Formatter/ContainerManipulator/CreateMultilineCommentFromTokenToTokenTest.php';
require_once 'PHP/Formatter/ContainerManipulator/UnifyCastsTest.php';
require_once 'PHP/Formatter/ContainerManipulator/SetWhitespaceAfterTokenTest.php';
require_once 'PHP/Formatter/ContainerManipulator/SetWhitespaceBeforeTokenTest.php';


class AllTests
{
    public static function suite()
    {
        $suite = new PHPUnit_Framework_TestSuite('PHP_Formatter Test Suite');

        $suite->addTestSuite('PHP_FormatterTest');
        $suite->addTestSuite('PHP_Formatter_AbstractHelperTest');
        $suite->addTestSuite('PHP_Formatter_TokenContainerTest');
        $suite->addTestSuite('PHP_Formatter_TokenTest');
        $suite->addTestSuite('PHP_Formatter_UtilTest');

        // Rules
        $suite->addTestSuite('PHP_Formatter_Rule_InterfaceTest');
        $suite->addTestSuite('PHP_Formatter_Rule_AsptagsToLongTagsTest');
        $suite->addTestSuite('PHP_Formatter_Rule_CommentOutIncludesAndRequiresTest');
        $suite->addTestSuite('PHP_Formatter_Rule_ChangeLineEndingsTest');
        $suite->addTestSuite('PHP_Formatter_Rule_FormatOperatorsTest');
        $suite->addTestSuite('PHP_Formatter_Rule_IndentTest');
        $suite->addTestSuite('PHP_Formatter_Rule_RemoveCommentsTest');
        $suite->addTestSuite('PHP_Formatter_Rule_RemoveIndentionTest');
        $suite->addTestSuite('PHP_Formatter_Rule_RemoveMultipleEmptyLinesTest');
        $suite->addTestSuite('PHP_Formatter_Rule_RemoveTrailingWhitespaceTest');
        $suite->addTestSuite('PHP_Formatter_Rule_ShorttagsToLongTagsTest');
        $suite->addTestSuite('PHP_Formatter_Rule_StripPhpTest');
        $suite->addTestSuite('PHP_Formatter_Rule_StripNonPhpTest');
        $suite->addTestSuite('PHP_Formatter_Rule_ReplaceBooleanOperatorsWithLogicalOperatorsTest');
        $suite->addTestSuite('PHP_Formatter_Rule_ReplaceLogicalOperatorsWithBooleanOperatorsTest');

        // TokenConstraints
        $suite->addTestSuite('PHP_Formatter_TokenConstraint_InterfaceTest');
        $suite->addTestSuite('PHP_Formatter_TokenConstraint_BeginsWithNewlineTest');
        $suite->addTestSuite('PHP_Formatter_TokenConstraint_EndsWithNewlineTest');
        $suite->addTestSuite('PHP_Formatter_TokenConstraint_IsMultilineCommentTest');
        $suite->addTestSuite('PHP_Formatter_TokenConstraint_IsSingleNewlineTest');
        $suite->addTestSuite('PHP_Formatter_TokenConstraint_IsOperatorTest');
        $suite->addTestSuite('PHP_Formatter_TokenConstraint_IsTypeTest');
        $suite->addTestSuite('PHP_Formatter_TokenConstraint_IsClosingCurlyBraceTest');
        $suite->addTestSuite('PHP_Formatter_TokenConstraint_IsOpeningCurlyBraceTest');
        $suite->addTestSuite('PHP_Formatter_TokenConstraint_IsClosingBraceTest');
        $suite->addTestSuite('PHP_Formatter_TokenConstraint_IsOpeningBraceTest');
        $suite->addTestSuite('PHP_Formatter_TokenConstraint_MockTest');

        // ContainerConstraints
        $suite->addTestSuite('PHP_Formatter_ContainerConstraint_InterfaceTest');
        $suite->addTestSuite('PHP_Formatter_ContainerConstraint_MockTest');

        // TokenManipulators
        $suite->addTestSuite('PHP_Formatter_TokenManipulator_InterfaceTest');
        $suite->addTestSuite('PHP_Formatter_TokenManipulator_MockTest');
        $suite->addTestSuite('PHP_Formatter_TokenManipulator_RemoveBeginNewlineTest');

        // ContainerManipulators
        $suite->addTestSuite('PHP_Formatter_ContainerManipulator_CreateMultilineCommentFromTokenToTokenTest');
        $suite->addTestSuite('PHP_Formatter_ContainerManipulator_InterfaceTest');        
        $suite->addTestSuite('PHP_Formatter_ContainerManipulator_MockTest');
        $suite->addTestSuite('PHP_Formatter_ContainerManipulator_SetWhitespaceAfterTokenTest');
        $suite->addTestSuite('PHP_Formatter_ContainerManipulator_SetWhitespaceBeforeTokenTest');
        $suite->addTestSuite('PHP_Formatter_ContainerManipulator_UnifyCastsTest');

        return $suite;
    }
}