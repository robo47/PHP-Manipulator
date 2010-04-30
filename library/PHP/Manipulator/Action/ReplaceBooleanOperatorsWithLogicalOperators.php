<?php

namespace PHP\Manipulator\Action;

use PHP\Manipulator\Action;
use PHP\Manipulator\TokenContainer;
use PHP\Manipulator\Token;

class ReplaceBooleanOperatorsWithLogicalOperators
extends Action
{
    public function init()
    {
        if (!$this->hasOption('uppercase')) {
            $this->setOption('uppercase', false);
        }
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
     */
    public function run(TokenContainer $container, $params = null)
    {
        $iterator = $container->getIterator();

        if ($this->getOption('uppercase')) {
            $and = 'AND';
            $or = 'OR';
        } else {
            $and = 'and';
            $or = 'or';
        }

        while ($iterator->valid()) {
            $token = $iterator->current();
            if ($this->_isBooleanAndAndShouldBeReplaced($token)) {
                $token->setValue($and);
                $token->setType(T_LOGICAL_AND);
            } elseif ($this->_isBooleanOrAndShouldBeReplaced($token)) {
                $token->setValue($or);
                $token->setType(T_LOGICAL_OR);
            }
            $iterator->next();
        }
    }

    /**
     * @param \PHP\Manipulator\Token $token
     * @return boolean
     */
    protected function _isBooleanAndAndShouldBeReplaced(Token $token)
    {
        return ($this->evaluateConstraint('IsType', $token, T_BOOLEAN_AND) && $this->getOption('replaceAnd'));
    }

    /**
     * @param \PHP\Manipulator\Token $token
     * @return boolean
     */
    protected function _isBooleanOrAndShouldBeReplaced(Token $token)
    {
        return ($this->evaluateConstraint('IsType', $token, T_BOOLEAN_OR) && $this->getOption('replaceOr'));
    }
}