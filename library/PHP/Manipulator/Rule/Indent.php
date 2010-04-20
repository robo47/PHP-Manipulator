<?php

namespace PHP\Manipulator\Rule;

use PHP\Manipulator\Rule;
use PHP\Manipulator\Rule\RemoveIndention as RemoveIndentionRule;
use PHP\Manipulator\TokenContainer;
use PHP\Manipulator\Token;

class Indent
extends Rule
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
     *
     * @var TokenContainer
     */
    protected $_container = null;

    /**
     * Unindents all Code and then indent it right
     *
     * @param \PHP\Manipulator\TokenContainer $container
     */
    public function apply(TokenContainer $container)
    {
        $this->_container = $container;
        $removeIndention = new RemoveIndentionRule();
        $removeIndention->apply($container);

        $iterator = $container->getIterator();

        while ($iterator->valid()) {
            $token = $iterator->current();

            $this->checkAndChangeIndentionLevel($token);

            if ($this->_isMultilineComment($token)) {
                $this->indentMultilineComment($token);
            }

            $this->switchIndentionCheck($token);

            if ($this->_isWhitespaceWithBreak($token)) {
                $iterator->next();
                $nextToken = $iterator->current();

                $this->checkAndChangeIndentionLevelDecreasment($nextToken);
                $this->indentWhitespace($token);
                if ($this->_isMultilineComment($nextToken)) {
                    $this->indentMultilineComment($nextToken);
                }
                $this->switchIndentionCheck($nextToken);
            }

            $iterator->next();
        }
    }

    /**
     * @var boolean
     */
    protected $_incase = false;

    protected function switchIndentionCheck(Token $token)
    {
        if($this->evaluateConstraint('IsType', $token, T_BREAK) && true === $this->_incase) {
            $this->_incase = false;
            $this->decreaseIndentionLevel();
        }

        // only indent if case is not directly followed by case
        if($this->evaluateConstraint('IsType', $token, T_CASE) && $this->_caseIsDirectlyFollowedByAnotherCase($token)) {

        } else if($this->evaluateConstraint('IsType', $token, T_CASE)) {
            if ($this->evaluateConstraint('IsType', $token, T_CASE) &&
                true === $this->_incase &&
                !$this->_isT_CasePreceededByBreak($token)) {
                echo 'foo decrease';
                $this->decreaseIndentionLevel();
            }
            $this->_incase = true;
            $this->increasIndentionLevel();
        }
    }


    protected function _isT_CasePreceededByBreak(Token $caseToken)
    {
        $iterator = $this->_container->getReverseIterator();
        $iterator->seekToToken($caseToken);
        $iterator->previous();
        while ($iterator->valid()) {
            $token = $iterator->current();
            if ($this->evaluateConstraint('IsType', $token, T_BREAK)) {
                return true;
            } else {
                // later probably T_CLOSE_TAG, T_OPEN_TAG, T_INLINE_HTML
                if (!$this->evaluateConstraint('IsType', $token, array(T_WHITESPACE, T_COMMENT, T_DOC_COMMENT) || $token->getValue != ';')) {
                    return false;
                }
            }
            $iterator->next();
        }
        return false;
    }

    /**
     *
     * @param Token $caseToken
     * @return Token
     */
    protected function _findNextDoppelPunktToken(Token $caseToken)
    {
        $iterator = $this->_container->getIterator();
        $iterator->seekToToken($caseToken);
        $iterator->next();
        while ($iterator->valid()) {
            $token = $iterator->current();
            if ($token->getValue() === ':') {
                return $iterator->current();
            }
            $iterator->next();
        }
        throw new \Exception('no doppelpunkt found');
    }

    protected function _caseIsDirectlyFollowedByAnotherCase(Token $caseToken)
    {
        $iterator = $this->_container->getIterator();
        $iterator->seekToToken($this->_findNextDoppelPunktToken($caseToken));
        $iterator->next();
        while ($iterator->valid()) {
            $token = $iterator->current();
            if ($this->evaluateConstraint('IsType', $token, T_CASE)) {
                return true;
            } else {
                // later probably T_CLOSE_TAG, T_OPEN_TAG, T_INLINE_HTML
                if (!$this->evaluateConstraint('IsType', $token, array(T_WHITESPACE, T_COMMENT, T_DOC_COMMENT))) {
                    return false;
                }
            }
            $iterator->next();
        }
        return false;
    }

    protected function _isMultilineComment(Token $token)
    {
        return $this->evaluateConstraint('IsMultilineComment', $token);
    }

    public function indentMultilineComment(Token $token)
    {
        $this->manipulateToken('IndentMultilineComment', $token, $this->getIndention($this->getIndentionLevel()));
    }

    /**
     * @param \PHP\Manipulator\Token $whitespaceToken
     */
    public function indentWhitespace(Token $whitespaceToken)
    {
        $newValue = $whitespaceToken->getValue() .
            $this->getIndention($this->getIndentionLevel());
        $whitespaceToken->setValue($newValue);
    }

    /**
     * @param \PHP\Manipulator\Token $token
     * @return boolean
     */
    protected function _isWhitespaceWithBreak(Token $token)
    {
        return $this->evaluateConstraint('IsType', $token, T_WHITESPACE)
            && $this->evaluateConstraint('ContainsNewline', $token);
    }

    public function checkAndChangeIndentionLevel(Token $token)
    {
        $this->checkAndChangeIndentionLevelDecreasment($token);
        $this->checkAndChangeIndentionLevelIncreasment($token);
    }

    public function checkAndChangeIndentionLevelIncreasment(Token $token)
    {
        if ($this->_isIndentionLevelIncreasment($token)) {
            $this->increasIndentionLevel();
        }
    }

    public function checkAndChangeIndentionLevelDecreasment(Token $token)
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
     * @param \PHP\Manipulator\Token $token
     * @return boolean
     */
    protected function _isIndentionLevelIncreasment(Token $token)
    {
        return $this->evaluateConstraint('IsOpeningCurlyBrace', $token)
            || $this->evaluateConstraint('IsOpeningBrace', $token);
    }

    /**
     * @param \PHP\Manipulator\Token $token
     * @return boolean
     */
    protected function _isIndentionLevelDecreasement(Token $token)
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
