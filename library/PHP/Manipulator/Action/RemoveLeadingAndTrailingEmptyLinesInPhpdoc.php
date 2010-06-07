<?php

namespace PHP\Manipulator\Action;

use PHP\Manipulator\Action;
use PHP\Manipulator\TokenContainer;

class RemoveLeadingAndTrailingEmptyLinesInPhpdoc
extends Action
{

    /**
     * Run Action
     *
     * @param \PHP\Manipulator\TokenContainer $container
     */
    public function run(TokenContainer $container, $params = null)
    {
        $iterator = $container->getIterator();

        while ($iterator->valid()) {
            $token = $iterator->current();
            if ($this->isType($token, T_DOC_COMMENT)) {
                $this->manipulateToken('RemoveLeadingAndTrailingEmptyLinesInPhpdoc', $token);
            }
            $iterator->next();
        }
        $container->retokenize();
    }
}