<?php

namespace PHP\Manipulator\Rule;

use PHP\Manipulator\Rule;
use PHP\Manipulator\TokenContainer;
use PHP\Manipulator\Token;

class StripNonPhp
extends Rule
{

    /**
     * @param \PHP\Manipulator\TokenContainer $container
     */
    public function apply(TokenContainer $container)
    {
        $iterator = $container->getIterator();

        $open = false;
        $deleteTokens = array();
        while ($iterator->valid()) {
            $token = $iterator->current();

            if ($this->_isOpenTag($token)) {
                $open = true;
            }
            if (!$open) {
                $deleteTokens[] = $token;
            }
            if ($this->_isCloseTag($token)) {
                $open = false;
            }
            $iterator->next();
        }
        $container->removeTokens($deleteTokens);
        $container->retokenize();
    }

    /**
     * @param \PHP\Manipulator\Token $token
     * @return boolean
     */
    protected function _isOpenTag(Token $token)
    {
        return ($this->evaluateConstraint('IsType', $token, T_OPEN_TAG) ||
                $this->evaluateConstraint('IsType', $token, T_OPEN_TAG_WITH_ECHO));
    }

    /**
     * @param \PHP\Manipulator\Token $token
     * @return boolean
     */
    protected function _isCloseTag(Token $token)
    {
        return ($this->evaluateConstraint('IsType', $token, T_CLOSE_TAG));
    }
}