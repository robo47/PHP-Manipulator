<?php

namespace PHP\Manipulator\Helper;

use PHP\Manipulator\AHelper;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;
use PHP\Manipulator\TokenContainer\Iterator;

class SetWhitespaceBeforeToken
extends SetWhitespaceAfterToken
{

    /**
     * @param Iterator $iterator
     */
    protected function _moveIteratorToTargetToken(Iterator $iterator)
    {
        $iterator->previous();
    }

    /**
     * @param Iterator $iterator
     */
    protected function _moveIteratorBackFromTagetToken(Iterator $iterator)
    {
        $iterator->next();
    }

    /**
     * @param \PHP\Manipulator\TokenContainer $container
     * @param \PHP\Manipulator\Token $targetToken
     * @param \PHP\Manipulator\Token $newToken
     */
    protected function _insertToken(Token $newToken, Iterator $iterator)
    {
        $this->_container->insertTokenAfter($iterator->current(), $newToken);
    }
}