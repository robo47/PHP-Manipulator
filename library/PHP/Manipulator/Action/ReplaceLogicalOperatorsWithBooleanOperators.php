<?php

namespace PHP\Manipulator\Action;

use PHP\Manipulator\Action;
use PHP\Manipulator\TokenContainer;
use PHP\Manipulator\Token;

class ReplaceLogicalOperatorsWithBooleanOperators
extends Action
{
    public function init()
    {
        if (!$this->hasOption('replaceAnd')) {
            $this->setOption('replaceAnd', true);
        }
        if (!$this->hasOption('replaceOr')) {
            $this->setOption('replaceOr', true);
        }
    }

    /**
     * Replace boolean and (AND)/or (OR) with logical and (&&)/or (||)
     *
     * @param \PHP\Manipulator\TokenContainer $container
     * @param mixed $params
     */
    public function run(TokenContainer $container, $params = null)
    {
        $iterator = $container->getIterator();

        $and = '&&';
        $or = '||';

        while ($iterator->valid()) {
            $token = $iterator->current();
            if ($this->_isLogicalAndAndShouldBeReplaced($token)) {
                $token->setValue($and);
                $token->setType(T_BOOLEAN_AND);
            } elseif ($this->_isLogicalOrAndShouldBeReplaced($token)) {
                $token->setValue($or);
                $token->setType(T_BOOLEAN_OR);
            }
            $iterator->next();
        }
    }

    /**
     * @param \PHP\Manipulator\Token $token
     * @return boolean
     */
    protected function _isLogicalAndAndShouldBeReplaced(Token $token)
    {
        return ($this->evaluateConstraint('IsType', $token, T_LOGICAL_AND) && $this->getOption('replaceAnd'));
    }

    /**
     * @param \PHP\Manipulator\Token $token
     * @return boolean
     */
    protected function _isLogicalOrAndShouldBeReplaced(Token $token)
    {
        return ($this->evaluateConstraint('IsType', $token, T_LOGICAL_OR) && $this->getOption('replaceOr'));
    }
}