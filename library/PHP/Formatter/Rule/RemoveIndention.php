<?php

require_once 'PHP/Formatter/Rule/Abstract.php';

class PHP_Formatter_Rule_RemoveIndention extends PHP_Formatter_Rule_Abstract
{
    
    public function init()
    {
        if (!$this->hasOption('defaultBreak')) {
            $this->setOption('defaultBreak', "\n");
        }
    }

    /**
     * Unindents all Code
     * 
     * @param PHP_Formatter_TokenContainer $container
     */
    public function applyRuleToTokens(PHP_Formatter_TokenContainer $container)
    {
        $code = $container->toString();
        // @todo ideas on how to remove all indention: all T_WHITESPACE -> check for \t and <space> and remove all expect 1 <space> and keep brakes, no need for \t or <space> between brakes
        $defaultBreak = $this->getOption('defaultBreak');
        $code = preg_split('~(\n|\r\n|\r)~', $code, - 1);
        $code = array_map('ltrim', $code);
        $code = implode($defaultBreak, $code);

        // @todo seems like a expensive task, with all type-checking and stuff like that ?
        $tokenArrayContainer = PHP_Formatter_TokenContainer::createFromCode($code)
            ->getContainer();

        $container->setContainer($tokenArrayContainer);
    }
}