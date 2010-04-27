<?php

namespace PHP\Manipulator\Ruleset;

use PHP\Manipulator\Ruleset;
use PHP\Manipulator\Rule\ChangeLineEndings;
use PHP\Manipulator\Rule\RemoveMultipleEmptyLines;
use PHP\Manipulator\Rule\Indent;

class PHPManipulator extends Ruleset
{

    /**
     * @return array
     */
    public function getRules()
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

        $rules = array();

        $rules[] = new RemoveMultipleEmptyLines($emptyLinesOptions);
        $rules[] = new Indent($indentOptions);
        $rules[] = new ChangeLineEndings($changelineEndingsOptions);

        return $rules;
    }
}