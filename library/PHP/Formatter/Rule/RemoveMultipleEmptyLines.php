<?php

require_once 'PHP/Formatter/Rule/Abstract.php';

class PHP_Formatter_Rule_RemoveMultipleEmptyLines
extends PHP_Formatter_Rule_Abstract
{
    public function init()
    {
        if (!$this->hasOption('maxEmptyLines')) {
            $this->setOption('maxEmptyLines', 2);
        }
    }

    /**
     *
     * @param array $tokens
     */
    public function applyRuleToTokens(PHP_Formatter_TokenContainer $tokens)
    {
        $iterator = $tokens->getIterator();
        $maxEmptyLines = $this->getOption('maxEmptyLines');

        $pattern = '~([\n]{' . ($maxEmptyLines + 1) . ',})~';
        $replace = str_repeat("\n", $maxEmptyLines);

        while($iterator->valid()) {
            $token = $iterator->current();
            /* @var $token PHP_Formatter_Token */
            if($token->isType(T_WHITESPACE)) {
                $value = preg_replace($pattern, $replace, $token->getValue());
                $token->setValue($value);
            }
            $iterator->next();
        }
    }
}