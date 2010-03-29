<?php

class AllTests
{
    public static function suite()
    {
        $suite = new PHPUnit_Framework_TestSuite('PHP_Formatter Test Suite');

        $suite->addTestSuite('PHP_FormatterTest');
        $suite->addTestSuite('PHP_Formatter_AutoloaderTest');
        $suite->addTestSuite('PHP_Formatter_AbstractHelperTest');
        $suite->addTestSuite('PHP_Formatter_TokenContainerTest');
        $suite->addTestSuite('PHP_Formatter_TokenContainer_IteratorTest');
        $suite->addTestSuite('PHP_Formatter_TokenContainer_ReverseIteratorTest');
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
        $suite->addTestSuite('PHP_Formatter_TokenConstraint_IsSinglelineCommentTest');
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
        $suite->addTestSuite('PHP_Formatter_ContainerConstraint_ContainsClassTest');

        // TokenManipulators
        $suite->addTestSuite('PHP_Formatter_TokenManipulator_InterfaceTest');
        $suite->addTestSuite('PHP_Formatter_TokenManipulator_MockTest');
        $suite->addTestSuite('PHP_Formatter_TokenManipulator_RemoveBeginNewlineTest');
        $suite->addTestSuite('PHP_Formatter_TokenManipulator_LowercaseTokenValueTest');
        $suite->addTestSuite('PHP_Formatter_TokenManipulator_UppercaseTokenValueTest');

        // ContainerManipulators
        $suite->addTestSuite('PHP_Formatter_ContainerManipulator_CreateMultilineCommentFromTokenToTokenTest');
        $suite->addTestSuite('PHP_Formatter_ContainerManipulator_InterfaceTest');        
        $suite->addTestSuite('PHP_Formatter_ContainerManipulator_MockTest');
        $suite->addTestSuite('PHP_Formatter_ContainerManipulator_SetWhitespaceAfterTokenTest');
        $suite->addTestSuite('PHP_Formatter_ContainerManipulator_SetWhitespaceBeforeTokenTest');
        $suite->addTestSuite('PHP_Formatter_ContainerManipulator_UnifyCastsTest');
        $suite->addTestSuite('PHP_Formatter_ContainerManipulator_RemoveWhitespaceFromEndTest');

        return $suite;
    }
}