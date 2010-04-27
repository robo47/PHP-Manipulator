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
     * @var boolean
     */
    protected $_inuse = false;

    /**
     * @var boolean
     */
    protected $_incase = false;

    /**
     * Current Level of Indention
     *
     * @var integer
     */
    protected $_indentionLevel = 0;

    /**
     * The current container to not have to pass it to each method
     *
     * @var TokenContainer
     */
    protected $_container = null;

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

            $this->_checkAndChangeIndentionLevel($token);

            $this->_checkForMultilineCommentAndIndent($token);

            $this->_useIndentionCheck($token);
            $this->_switchIndentionCheck($token);

            if ($this->_isWhitespaceWithBreak($token)) {
                $iterator->next();
                $nextToken = $iterator->current();
                $this->_checkAndChangeIndentionLevelDecreasment($nextToken);
                $this->_indentWhitespace($token, $container->getOffsetByToken($token));
                $this->_checkForMultilineCommentAndIndent($nextToken);
                $this->_checkAndChangeIndentionLevelIncreasment($nextToken);

                $this->_useIndentionCheck($nextToken);
                $this->_switchIndentionCheck($nextToken);
            }
            $iterator->next();
        }
    }

    /**
     * Check if a Tiken is a Multilinecomment and indent it
     *
     * @param Token $token
     */
    protected function _checkForMultilineCommentAndIndent(Token $token)
    {
        if ($this->evaluateConstraint('IsMultilineComment', $token)) {
            $this->manipulateToken(
                'IndentMultilineComment',
                $token,
                $this->getIndention($this->getIndentionLevel())
            );
        }
    }

    /**
     * @param Token $token
     */
    protected function _useIndentionCheck(Token $token) {
        if($this->evaluateConstraint('IsType', $token, T_USE)) {
            $this->_inuse = true;
            $this->_indentionLevel++;
        }
        // @todo isSemicolonConstraint
        if (';' === $token->getValue() && true === $this->_inuse) {
            $this->_inuse = false;
            $this->_indentionLevel--;
        }
    }

    /**
     * @param Token $token
     */
    protected function _switchIndentionCheck(Token $token)
    {
        if($this->evaluateConstraint('IsType', $token, T_BREAK) && true === $this->_incase) {
            $this->_incase = false;
            $this->_indentionLevel--;
        }

        // only indent if case is not directly followed by case
        if ($this->evaluateConstraint('IsType', $token, T_CASE) && !$this->_caseIsDirectlyFollowedByAnotherCase($token)) {
            if ($this->evaluateConstraint('IsType', $token, T_CASE) &&
                true === $this->_incase &&
                !$this->_isCasePreceededByBreak($token)) {
                $this->_indentionLevel--;
            }
            $this->_incase = true;
            $this->_indentionLevel++;
        }
    }

    /**
     *
     * @param Token $caseToken
     * @return boolean
     */
    protected function _isCasePreceededByBreak(Token $caseToken)
    {
        $iterator = $this->_container->getReverseIterator();
        $iterator->seekToToken($caseToken);
        $iterator->previous();
        while ($iterator->valid()) {
            $token = $iterator->current();
            if ($this->evaluateConstraint('IsType', $token, T_BREAK)) {
                return true;
            } else {
                // @todo add/test T_CLOSE_TAG, T_OPEN_TAG, T_INLINE_HTML
                if (!$this->evaluateConstraint('IsType', $token, array(T_WHITESPACE, T_COMMENT, T_DOC_COMMENT) || $token->getValue != ';')) {
                    return false;
                }
            }
            $iterator->next();
        }
        return false;
    }

    /**
     * @param Token $caseToken
     * @return Token
     */
    protected function _findNextColonToken(Token $caseToken)
    {
        $iterator = $this->_container->getIterator();
        $iterator->seekToToken($caseToken);
        $iterator->next();
        while ($iterator->valid()) {
            $token = $iterator->current();
            // @todo IsColon-Constraint
            if ($token->getValue() === ':') {
                return $iterator->current();
            }
            $iterator->next();
        }
        throw new \Exception('no colon found');
    }

    /**
     * @param Token $caseToken
     * @return boolean
     */
    protected function _caseIsDirectlyFollowedByAnotherCase(Token $caseToken)
    {
        $iterator = $this->_container->getIterator();
        $iterator->seekToToken($this->_findNextColonToken($caseToken));
        $iterator->next();
        while ($iterator->valid()) {
            $token = $iterator->current();
            if ($this->evaluateConstraint('IsType', $token, T_CASE)) {
                return true;
            } else {
                // @todo add/test T_CLOSE_TAG, T_OPEN_TAG, T_INLINE_HTML
                if (!$this->evaluateConstraint('IsType', $token, array(T_WHITESPACE, T_COMMENT, T_DOC_COMMENT))) {
                    return false;
                }
            }
            $iterator->next();
        }
        return false;
    }

    /**
     * @param \PHP\Manipulator\Token $whitespaceToken
     */
    protected function _indentWhitespace(Token $whitespaceToken, $iterkey)
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
        return $this->evaluateConstraint('IsType', $token, T_WHITESPACE) &&
               $this->evaluateConstraint('ContainsNewline', $token);
    }

    /**
     *
     * @param Token $token
     */
    public function _checkAndChangeIndentionLevel(Token $token)
    {
        $this->_checkAndChangeIndentionLevelDecreasment($token);
        $this->_checkAndChangeIndentionLevelIncreasment($token);
    }

    /**
     * @param Token $token
     */
    public function _checkAndChangeIndentionLevelIncreasment(Token $token)
    {
        if ($this->_isIndentionLevelIncreasment($token)) {
            $this->_indentionLevel++;
        }
    }

    /**
     * @param Token $token
     */
    public function _checkAndChangeIndentionLevelDecreasment(Token $token)
    {
        if ($this->_isIndentionLevelDecreasement($token)) {
            $this->_indentionLevel--;
        }
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
            $indention = @str_repeat(' ', $indentionLength);
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
