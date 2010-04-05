<?php

namespace PHP\Manipulator\Rule;

use PHP\Manipulator\Rule;
use PHP\Manipulator\TokenContainer;
use PHP\Manipulator\Token;

class ReplaceLogicalOperatorsWithBooleanOperators
extends Rule
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
     * @param PHP\Manipulator\TokenContainer $container
     */
    public function applyRuleToTokens(TokenContainer $container)
    {
        $iterator = $container->getIterator();

        $and = '&&';
        $or = '||';

        while ($iterator->valid()) {
            $token = $iterator->current();
            /* @var $token PHP\Manipulator\Token */
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
     * @param PHP\Manipulator\Token $token
     * @return boolean
     */
    protected function _isLogicalAndAndShouldBeReplaced(Token $token)
    {
        return ($this->evaluateConstraint('IsType', $token, T_LOGICAL_AND) && $this->getOption('replaceAnd'));
    }

    /**
     * @param PHP\Manipulator\Token $token
     * @return boolean
     */
    protected function _isLogicalOrAndShouldBeReplaced(Token $token)
    {
        return ($this->evaluateConstraint('IsType', $token, T_LOGICAL_OR) && $this->getOption('replaceOr'));
    }
}