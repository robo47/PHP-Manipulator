<?php

class PHP_Manipulator_Rule_Indent extends PHP_Manipulator_Rule_Abstract
{

    /**
     * Current Level of Indention
     *
     * @var integer
     */
    protected $_indentionLevel = 0;

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
        if (!$this->hasOption('initialIndentionWidth')) {
            $this->setOption('initialIndentionWidth', 0);
        }
    }

    /**
     * Unindents all Code and then indent it right
     *
     * @param PHP_Manipulator_TokenContainer $container
     */
    public function applyRuleToTokens(PHP_Manipulator_TokenContainer $container)
    {
        $removeIndention = new PHP_Manipulator_Rule_RemoveIndention();
        $removeIndention->applyRuleToTokens($container);

        $iterator = $container->getIterator();

        while ($iterator->valid()) {
            $token = $iterator->current();

            $this->checkAndChangeIndentionLevel($token);

            if ($this->_isWhitespaceWithBreak($token)) {
                $iterator->next();
                $nextToken = $iterator->current();

                $this->checkAndChangeIndentionLevelDecreasment($nextToken);
                $this->indentWhitespace($token);
            }
            $iterator->next();
        }
    }

    /**
     * @param PHP_Manipulator_Token $whitespaceToken
     */
    public function indentWhitespace(PHP_Manipulator_Token $whitespaceToken)
    {
        $newValue = $whitespaceToken->getValue() .
            $this->getIndention($this->getIndentionLevel());
        $whitespaceToken->setValue($newValue);
    }

    /**
     * @param PHP_Manipulator_Token $token
     * @return boolean
     */
    protected function _isWhitespaceWithBreak(PHP_Manipulator_Token $token)
    {
        return $this->evaluateConstraint('IsType', $token, T_WHITESPACE)
            && (false !== strpos($token->getValue(), "\n")
                || false !== strpos($token->getValue(), "\r"));
    }

    public function checkAndChangeIndentionLevel(PHP_Manipulator_Token $token)
    {
        $this->checkAndChangeIndentionLevelDecreasment($token);
        $this->checkAndChangeIndentionLevelIncreasment($token);
    }

    public function checkAndChangeIndentionLevelIncreasment(PHP_Manipulator_Token $token)
    {
        if ($this->_isIndentionLevelIncreasment($token)) {
            $this->increasIndentionLevel();
        }
    }

    public function checkAndChangeIndentionLevelDecreasment(PHP_Manipulator_Token $token)
    {
        if ($this->_isIndentionLevelDecreasement($token)) {
            $this->decreaseIndentionLevel();
        }
    }

    public function increasIndentionLevel()
    {
        $this->_indentionLevel++;
    }

    public function decreaseIndentionLevel()
    {
        $this->_indentionLevel--;
    }

    /**
     * @return integer
     */
    public function getIndentionLevel()
    {
        return $this->_indentionLevel;
    }

    /**
     * @param PHP_Manipulator_Token $token
     * @return boolean
     */
    protected function _isIndentionLevelIncreasment(PHP_Manipulator_Token $token)
    {
        return $this->evaluateConstraint('IsOpeningCurlyBrace', $token)
            || $this->evaluateConstraint('IsOpeningBrace', $token);
    }

    /**
     * @param PHP_Manipulator_Token $token
     * @return boolean
     */
    protected function _isIndentionLevelDecreasement(PHP_Manipulator_Token $token)
    {
        return $this->evaluateConstraint('IsClosingCurlyBrace', $token)
            || $this->evaluateConstraint('IsClosingBrace', $token);
    }

    /**
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
     * @param integer $spaceLength
     * @param integer $tabWith
     * @return string
     */
    public function getAsTabs($spaceLength, $tabWidth)
    {
        $tabCount = floor($spaceLength / $tabWidth);
        $additionalSpaces = $spaceLength % $tabWidth;

        return str_repeat("\t", $tabCount) . str_repeat(' ', $additionalSpaces);
    }
}