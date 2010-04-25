<?php

namespace PHP\Manipulator\ContainerManipulator;

use PHP\Manipulator\ContainerManipulator;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;

class UppercaseConstants
extends ContainerManipulator
{

    protected $_isConstant = false;

    /**
     * Manipulate
     *
     * @param PHP\Manipulator\TokenContainer $container
     * @param mixed $params
     */
    public function manipulate(TokenContainer $container, $params = null)
    {
        $iterator = $container->getIterator();

        while ($iterator->valid()) {
            $token = $iterator->current();

            if($this->evaluateConstraint('IsType', $token,T_CONST)) {
                $this->_isConstant = true;
            }

            if (true === $this->_isConstant && ';' === $token->getValue()) {
                $this->_isConstant = false;
            }

            if (true === $this->_isConstant && $this->evaluateConstraint('IsType', $token, T_STRING)) {
                $token->setValue(strtoupper($token->getValue()));
            } else {
                $previous = $container->getPreviousToken($token);
                $next = $container->getNextToken($token);
                // @todo what about whitespace
                if (null !== $previous && $this->evaluateConstraint('IsType', $previous, T_DOUBLE_COLON)) {
                    if (null !== $next && $this->evaluateConstraint('IsType', $next, null) && '(' !== $next->getValue()) {
                        $token->setValue(strtoupper($token->getValue()));
                    }

                }
            }

            $iterator->next();
        }
        $container->retokenize();
    }
}
