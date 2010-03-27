<?php

require_once 'PHP/Formatter/Rule/Abstract.php';

class PHP_Formatter_Rule_ChangeLineEndings extends PHP_Formatter_Rule_Abstract
{
    
    public function init()
    {
        if (!$this->hasOption('newline')) {
            $this->setOption('newline', "\n");
        }
    }

    /**
     *
     * @param PHP_Formatter_TokenContainer $container
     */
    public function applyRuleToTokens(PHP_Formatter_TokenContainer $container)
    {
        $newline = $this->getOption('newline');

        $code = $container->__toString();
        $code = preg_replace('~(\r\n|\n|\r)~', $newline, $code);
        $container->setContainer(PHP_Formatter_TokenContainer::createTokenArrayFromCode($code));
    }
}