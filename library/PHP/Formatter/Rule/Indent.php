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
//        if (!$this->hasOption('useAlignment')) {
//            $this->setOption('useAlignment', true);
//        }
        if (!$this->hasOption('tabWidth')) {
            $this->setOption('tabWidth', 4);
        }
        if (!$this->hasOption('indentionWidth')) {
            $this->setOption('indentionWidth', 4);
        }
        if (!$this->hasOption('initialIndentionWidth')) {
            $this->setOption('initialIndentionWidth', 0);
        }
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
        echo PHP_EOL . '###### START #####' . PHP_EOL;
        $removeIndention = new PHP_Formatter_Rule_RemoveIndention();
        $removeIndention->applyRuleToTokens($container);

        $iterator = $container->getIterator();

        while ($iterator->valid()) {
            $token = $iterator->current();
            if ($this->_isIndentionLevelIncreasment($token)) {
                $this->increasIndentionLevel();
            }
            if ($this->_isIndentionLevelDecreasement($token)) {
                $this->decreasIndentionLevel();
            }
            if ($this->evaluateConstraint('IsType', $token, T_WHITESPACE) && false !== strpos($token->getValue(), "\n")) {
                $whitespace = $token;
                $iterator->next();
                $nextToken = $iterator->current();
                if ($this->_isIndentionLevelDecreasement($nextToken)) {
                    $this->decreaseIndentionLevel();
                }
                echo 'indent: ' .$iterator->key() .') ' . $this->getIndentionLevel() . PHP_EOL;
                $newValue = $whitespace->getValue() . $this->getIndention($this->getIndentionLevel());
                $whitespace->setValue($newValue);
            }
            $iterator->next();
        }
        echo PHP_EOL . '###### END #####' . PHP_EOL;
    }
    
    protected $_indentionLevel = 0;

    public function increasIndentionLevel()
    {
        $this->_indentionLevel++;
        echo 'increase: ' ;
        echo $this->_indentionLevel . PHP_EOL;
    }

    public function decreaseIndentionLevel()
    {
        $this->_indentionLevel--;
        echo 'decrease: ' ;
        echo $this->_indentionLevel . PHP_EOL;
    }

    public function getIndentionLevel()
    {
        return $this->_indentionLevel;
    }

//    protected $_currentAction = array();
//
//    protected function _wtfAreWeCurrentlyDoing()
//    {
//        return $this->_currentAction;
//    }

    protected function _isIndentionLevelIncreasment($token)
    {
        return $this->evaluateConstraint('IsOpeningCurlyBrace', $token);
    }

    protected function _isIndentionLevelDecreasement($token)
    {
        return $this->evaluateConstraint('IsClosingCurlyBrace', $token);
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