<?php

require_once 'PHP/Formatter/Rule/Abstract.php';

class PHP_Formatter_Rule_ReplaceLogicalOperatorsWithBooleanOperators
extends PHP_Formatter_Rule_Abstract
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
     * @param PHP_Formatter_TokenContainer $container
     */
    public function applyRuleToTokens(PHP_Formatter_TokenContainer $container)
    {
        $iterator = $container->getIterator();

        $and = '&&';
        $or = '||';

        while ($iterator->valid()) {
            $token = $iterator->current();
            /* @var $token PHP_Formatter_Token */
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
     * @param PHP_Formatter_Token $token
     * @return boolean
     */
    protected function _isLogicalAndAndShouldBeReplaced($token)
    {
        return ($this->evaluateConstraint('IsType', $token, T_LOGICAL_AND) && $this->getOption('replaceAnd'));
    }

    /**
     * @param PHP_Formatter_Token $token
     * @return boolean
     */
    protected function _isLogicalOrAndShouldBeReplaced($token)
    {
        return ($this->evaluateConstraint('IsType', $token, T_LOGICAL_OR) && $this->getOption('replaceOr'));
    }
}