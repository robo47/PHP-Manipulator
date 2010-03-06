<?php

require_once 'PHP/Formatter/Rule/Abstract.php';

class PHP_Formatter_Rule_RemoveTrailingWhitespace extends PHP_Formatter_Rule_Abstract
{
    public function init()
    {
        if (!isset($this->_options['removeEmptyLinesAtFileEnd'])) {
            $this->_options['removeEmptyLinesAtFileEnd'] = true;
        }
    }

    /**
     *
     * @param array $tokens
     * @todo always outputs linux-line-endings!
     * @todo possible without tokens2code2tokens ?
     */
    public function applyRuleToTokens(PHP_Formatter_TokenContainer $tokens)
    {
        $code = $tokens->toString();

        $code = preg_split('~[\n|\r\n|\r]~', $code, -1);
        $code = array_map('rtrim', $code);
        $code = implode("\n", $code);

        if ($this->getOption('removeEmptyLinesAtFileEnd')) {
            $code = rtrim($code);
        }

        // @todo seems like a expensive task, with all type-checking and stuff like that ?
        $tokenArrayContainer = PHP_Formatter_TokenContainer::createTokenArrayFromCode($code)
                                              ->getContainer();

        $tokens->setContainer($tokenArrayContainer);
    }
}