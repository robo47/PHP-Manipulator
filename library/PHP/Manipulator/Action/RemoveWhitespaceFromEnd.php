<?php

namespace PHP\Manipulator\Action;

use PHP\Manipulator\Action;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;

class RemoveWhitespaceFromEnd
extends Action
{

    /**
     * Remove Whitespace from the end
     *
     * @param \PHP\Manipulator\TokenContainer $container
     * @param mixed $params
     */
    public function run(TokenContainer $container, $params = null)
    {
        $iterator = $container->getReverseIterator();

        while ($iterator->valid()) {
            $token = $iterator->current();
            if ($this->isType($token, T_WHITESPACE)) {
                $container->removeToken($token);
            } else if ($this->isType($token, T_INLINE_HTML)) {
                if ($this->evaluateConstraint('ContainsOnlyWhitespace', $token)) {
                    $container->removeToken($token);
                } else {
                    $token->setValue(rtrim($token->getValue()));
                    break;
                }
            } else {
                $token->setValue(rtrim($token->getValue()));
                break;
            }
            $iterator->next();
        }
        $container->retokenize();
    }
}