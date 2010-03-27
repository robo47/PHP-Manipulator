<?php

require_once 'PHP/Formatter/Rule/Abstract.php';

class PHP_Formatter_Rule_ReplaceBooleanOperatorsWithLogicalOperators
extends PHP_Formatter_Rule_Abstract
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
     * @param PHP_Formatter_TokenContainer $container
     */
    public function applyRuleToTokens(PHP_Formatter_TokenContainer $container)
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
            /* @var $token PHP_Formatter_Token */
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
     * @param PHP_Formatter_Token $token
     * @return boolean
     */
    protected function _isBooleanAndAndShouldBeReplaced($token)
    {
        return ($this->evaluateConstraint('IsType', $token, T_BOOLEAN_AND) && $this->getOption('replaceAnd'));
    }

    /**
     * @param PHP_Formatter_Token $token
     * @return boolean
     */
    protected function _isBooleanOrAndShouldBeReplaced($token)
    {
        return ($this->evaluateConstraint('IsType', $token, T_BOOLEAN_OR) && $this->getOption('replaceOr'));
    }
}