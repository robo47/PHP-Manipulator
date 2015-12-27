<?php

namespace PHP\Manipulator\Helper;

use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer\TokenContainerIterator;

class SetWhitespaceBeforeToken extends SetWhitespaceAfterToken
{
    /**
     * @param TokenContainerIterator $iterator
     */
    protected function moveIteratorToTargetToken(TokenContainerIterator $iterator)
    {
        $iterator->previous();
    }

    /**
     * @param TokenContainerIterator $iterator
     */
    protected function moveIteratorBackFromTagetToken(TokenContainerIterator $iterator)
    {
        $iterator->next();
    }

    /**
     * @param Token                  $newToken
     * @param TokenContainerIterator $iterator
     */
    protected function insertToken(Token $newToken, TokenContainerIterator $iterator)
    {
        $this->container->insertTokenAfter($iterator->current(), $newToken);
    }
}
