<?php

namespace PHP\Manipulator\Actionset;

use PHP\Manipulator\Actionset;
use PHP\Manipulator\Action\ChangeLineEndings;
use PHP\Manipulator\Action\RemoveMultipleEmptyLines;
use PHP\Manipulator\Action\Indent;
use PHP\Manipulator\Action\ElseIfToElseAndIf;
use PHP\Manipulator\Action\StripUnneededPhpCloseTag;
use PHP\Manipulator\Action\RemoveTrailingWhitespace;

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

        $actions = array();

        $actions[] = new ChangeLineEndings($changelineEndingsOptions);
        $actions[] = new RemoveMultipleEmptyLines($emptyLinesOptions);
        $actions[] = new StripUnneededPhpCloseTag();
        $actions[] = new ElseIfToElseAndIf();
        $actions[] = new Indent($indentOptions);
        $actions[] = new RemoveTrailingWhitespace();

        return $actions;
    }
}