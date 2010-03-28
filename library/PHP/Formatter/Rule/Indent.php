<?php

require_once 'PHP/Formatter/Rule/Abstract.php';

class PHP_Formatter_Rule_Indent extends PHP_Formatter_Rule_Abstract
{
    /**
     *
     * @var SplStack
     */
//    protected $_actionStack = null;

    /**
     * Current Level of Indention
     * 
     * @var integer
     */
    protected $_indentionLevel = 0;

    public function init()
    {
        $this->_actionStack = new SplStack();
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

            //$this->declareCurrentAction($token);

            if ($this->_isIndentionLevelIncreasment($token)) {
                $this->increasIndentionLevel();
            }
            if ($this->_isIndentionLevelDecreasement($token)) {
                $this->decreaseIndentionLevel();
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
//    const T_UNKNOWN_ACTION = -1337;
//    /**
//     *
//     * @param PHP_Formatter_Token $token
//     */
//    public function declareCurrentAction($token)
//    {
//        $type = $token->getType();
//        switch($type) {
//            case T_FUNCTION:
//            case T_CLASS:
//            case T_ARRAY:
//                $this->_actionStack->push($type);
//                break;
//            default:
//                if ($this->evaluateConstraint('IsOpeningCurlyBrace', $token)) {
//                    $top = $this->_actionStack->top();
//                    $this->_actionStack->push($top);
//                }
//
//                if ($this->evaluateConstraint('IsOpeningBrace', $token)) {
//                    $top = $this->_actionStack->top();
//                    $this->_actionStack->push($top);
//                }
//
//                if ($this->evaluateConstraint('IsClosingCurlyBrace', $token)) {
//                    $this->_actionStack->pop();
//                }
//
//                if ($this->evaluateConstraint('IsClosingBrace', $token)) {
//                    $this->_actionStack->pop();
//                }
//
//                $this->_currentAction[] = self::T_UNKNOWN_ACTION;
//        }
//    }
//
//    public function previousActionWasDeclaringAnArray()
//    {
//        end($this->_currentAction);
//        return (current($this->_currentAction) === T_ARRAY);
//    }


    protected function _isIndentionLevelIncreasment($token)
    {
        return $this->evaluateConstraint('IsOpeningCurlyBrace', $token) || $this->evaluateConstraint('IsOpeningBrace', $token);
    }

    protected function _isIndentionLevelDecreasement($token)
    {
        return $this->evaluateConstraint('IsClosingCurlyBrace', $token) || $this->evaluateConstraint('IsClosingBrace', $token);
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