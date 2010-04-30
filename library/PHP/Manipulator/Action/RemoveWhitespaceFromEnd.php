<?php

namespace PHP\Manipulator\Action;

use PHP\Manipulator\Action;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;

class RemoveWhitespaceFromEnd
extends Action
{

    /**
     * Manipulate
     *
     * @param \PHP\Manipulator\TokenContainer $container
     * @param mixed $params
     */
    public function run(TokenContainer $container, $params = null)
    {
        $iterator = $container->getReverseIterator();

        while ($iterator->valid()) {
            $token = $iterator->current();
            if ($this->evaluateConstraint('IsType', $token, T_WHITESPACE)) {
                $container->removeToken($token);
            } elseif($this->evaluateConstraint('IsType', $token, T_INLINE_HTML)) {
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