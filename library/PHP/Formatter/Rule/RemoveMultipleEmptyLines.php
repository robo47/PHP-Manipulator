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
        if (!$this->hasOption('defaultBreak')) {
            $this->setOption('defaultBreak', "\n");
        }
    }

    /**
     *
     * @param PHP_Formatter_TokenContainer $container
     */
    public function applyRuleToTokens(PHP_Formatter_TokenContainer $container)
    {
        $iterator = $container->getIterator();
        $maxEmptyLines = $this->getOption('maxEmptyLines');
        $defaultBreak = $this->getOption('defaultBreak');

        $pattern = '~(((\r\n|\r|\n)[\t| ]{0,}){' . ($maxEmptyLines + 1) . ',})~';
        $replace = str_repeat($defaultBreak, $maxEmptyLines);

        while ($iterator->valid()) {
            $token = $iterator->current();
            /* @var $token PHP_Formatter_Token */
            if ($token->isType(T_WHITESPACE)) {
                $value = preg_replace($pattern, $replace, $token->getValue());
                $token->setValue($value);
            }
            $iterator->next();
        }
    }
}