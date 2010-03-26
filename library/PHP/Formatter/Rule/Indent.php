<?php

require_once 'PHP/Formatter/Rule/Abstract.php';

class PHP_Formatter_Rule_Indent extends PHP_Formatter_Rule_Abstract
{
    
    public function init()
    {
        // indentions are always given in tabs!
        if (!$this->hasOption('useSpaces')) {
            $this->setOption('useSpaces', true);
        }
        if (!$this->hasOption('tabWidth')) {
            $this->setOption('tabWidth', 4);
        }
        if (!$this->hasOption('indentionWidth')) {
            $this->setOption('indentionWidth', 4);
        }
//        if (!$this->hasOption('initialIndentionWidth')) {
//            $this->setOption('initialIndentionWidth', 0);
//        }
    }

    /**
     * Unindents all Code
     *
     * @param PHP_Formatter_TokenContainer $container
     */
    public function applyRuleToTokens(PHP_Formatter_TokenContainer $container)
    {
        // RemoveIndetion first
        // for each token which is not T_WHITESPACE or T_INLINE_HTML [checking to do indention after EACH break!
        // check indention
        // dont indent T_PHP_OPEN!
        $iterator = $container->getIterator();

        while ($iterator->valid()) {
            $token = $iterator->current();
            /* @var $token PHP_Formatter_Token */
            // check if token contains break

            $iterator->next();
        }
    }

    /**
     *
     * @param integer $depth
     * @return string
     */
    public function getIndention($depth)
    {
        $useSpaces = $this->getOption('useSpaces');
        $tabWidth = $this->getOption('tabWidth');
        $indentionWidth = $this->getOption('indentionWidth');
        //$initialIndentionWidth = $this->getOption('initialIndentionWidth');

        $indentionLength = ($indentionWidth * $depth); // + $initialIndentionWidth;

        if (!$useSpaces) {
            $indention = $this->getAsTabs($indentionLength, $tabWidth);
        } else {
            $indention = str_repeat(' ', $indentionLength);
        }

        return $indention;
    }

    /**
     * Returns
     * @param integer $spaceLength
     * @param integer $tabWith
     * @return string
     */
    public function getAsTabs($spaceLength, $tabWith)
    {
        $tabCount = floor($spaceLength / "\t");
        $additionalSpaces = $spaceLength % "\t";

        return str_repeat("\t", $tabCount) . str_repeat(' ', $additionalSpaces);
    }
}