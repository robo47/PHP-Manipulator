<?php

namespace PHP\Manipulator\Actionset;

use PHP\Manipulator\Actionset;
use PHP\Manipulator\Action\ChangeLineEndings;
use PHP\Manipulator\Action\RemoveMultipleEmptyLines;
use PHP\Manipulator\Action\Indent;

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
            'maxEmptyLines' => 2,
            'defaultBreak' => "\n",
        );

        $changelineEndingsOptions = array(
            'newline' => "\n",
        );

        $actions = array();

        $actions[] = new RemoveMultipleEmptyLines($emptyLinesOptions);
        $actions[] = new Indent($indentOptions);
        $actions[] = new ChangeLineEndings($changelineEndingsOptions);

        return $actions;
    }
}