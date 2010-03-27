<?php

require_once 'PHP/Formatter/Rule/Abstract.php';

class PHP_Formatter_Rule_StripNonPhp extends PHP_Formatter_Rule_Abstract
{

    public function init()
    {
    }

    /**
     * @param PHP_Formatter_TokenContainer $container
     */
    public function applyRuleToTokens(PHP_Formatter_TokenContainer $container)
    {
        $iterator = $container->getIterator();

        $open = false;
        $deleteTokens = array();
        while ($iterator->valid()) {
            $token = $iterator->current();
            /* @var $token PHP_Formatter_Token */
            
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
     * @param PHP_Formatter_Token $token
     * @return boolean
     */
    protected function _isOpenTag($token)
    {
        return ($this->evaluateConstraint('IsType', $token, T_OPEN_TAG) ||
                $this->evaluateConstraint('IsType', $token, T_OPEN_TAG_WITH_ECHO));
    }

    /**
     * @param PHP_Formatter_Token $token
     * @return boolean
     */
    protected function _isCloseTag($token)
    {
        return ($this->evaluateConstraint('IsType', $token, T_CLOSE_TAG));
    }
}