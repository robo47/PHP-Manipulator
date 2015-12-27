<?php

namespace PHP\Manipulator\Action;

use PHP\Manipulator\Action;
use PHP\Manipulator\TokenContainer;
use PHP\Manipulator\TokenManipulator\RemoveLeadingAndTrailingEmptyLinesInPhpdoc as RemoveLeadingAndTrailingEmptyLinesInPhpdocTokenManipulator;

class RemoveLeadingAndTrailingEmptyLinesInPhpdoc extends Action
{
    public function run(TokenContainer $container, $params = null)
    {
        $iterator = $container->getIterator();

        while ($iterator->valid()) {
            $token = $iterator->current();
            if ($token->isDocComment()) {
                $this->manipulateToken(RemoveLeadingAndTrailingEmptyLinesInPhpdocTokenManipulator::class, $token);
            }
            $iterator->next();
        }
        $container->retokenize();
    }
}
