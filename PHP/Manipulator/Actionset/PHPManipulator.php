<?php

namespace PHP\Manipulator\Actionset;

use PHP\Manipulator\Actionset;
use PHP\Manipulator\Action\ChangeLineEndings;
use PHP\Manipulator\Action\RemoveMultipleEmptyLines;
use PHP\Manipulator\Action\Indent;
use PHP\Manipulator\Action\ElseIfToElseAndIf;
use PHP\Manipulator\Action\StripUnneededPhpCloseTag;
use PHP\Manipulator\Action\RemoveTrailingWhitespace;
use PHP\Manipulator\Action\FormatIfElseifElse;
use PHP\Manipulator\Action\FormatSwitch;
use PHP\Manipulator\Action\RemoveLeadingAndTrailingEmptyLinesInPhpdoc;

class PHPManipulator extends Actionset
{

    /**
     * @return array
     */
    public function getActions()
    {
        $indentOptions = array(
            'useSpaces' => true,
            'tabWidth' => 4,
            'indentionWidth' => 4,
            'initialIndentionWidth' => 0
        );

        $emptyLinesOptions = array(
            'maxEmptyLines' => 3,
            'defaultBreak' => "\n",
        );

        $changelineEndingsOptions = array(
            'newline' => "\n",
        );

        $removeTrailingWhitespaceOptions = array(
            'stripWhitespaceFromEnd' => true
        );

        $formatIfElseifElseOptions = array(
            'spaceAfterIf' => true,
            'spaceAfterElseif' => true,
            'spaceAfterElse' => true,
            'spaceBeforeIf' => true,
            'spaceBeforeElseif' => true,
            'spaceBeforeElse' => true,
            'breakAfterCurlyBraceOfIf' => true,
            'breakAfterCurlyBraceOfElse' => true,
            'breakAfterCurlyBraceOfElseif' => true,
            'spaceBeforeIfExpression' => false,
            'spaceAfterIfExpression' => false,
            'spaceBeforeElseifExpression' => false,
            'spaceAfterElseifExpression' => false,
        );

        $formatSwitchOptions = array(
            'spaceAfterSwitch' => true,
            'spaceAfterSwitchVariable' => true,
            'breakBeforeCurlyBrace' => false,
        );

        $actions = array();

        $actions[] = new RemoveLeadingAndTrailingEmptyLinesInPhpdoc();
        $actions[] = new RemoveMultipleEmptyLines($emptyLinesOptions);
        $actions[] = new StripUnneededPhpCloseTag();
        $actions[] = new ElseIfToElseAndIf();
        $actions[] = new FormatIfElseifElse($formatIfElseifElseOptions);
        $actions[] = new FormatSwitch($formatSwitchOptions);
        $actions[] = new Indent($indentOptions);
        $actions[] = new RemoveTrailingWhitespace($removeTrailingWhitespaceOptions);
        $actions[] = new ChangeLineEndings($changelineEndingsOptions);

        return $actions;
    }
}