<?php

namespace PHP\Manipulator\Action;

use PHP\Manipulator\Action;
use PHP\Manipulator\Action\RemoveIndention;
use PHP\Manipulator\TokenContainer;
use PHP\Manipulator\Token;

class Indent
extends Action
{

    /**
     * @var boolean
     */
    protected $_insideUse = false;

    /**
     * @var boolean
     */
    protected $_insideCase = false;

    /**
     * @var boolean
     */
    protected $_insideSwitch = false;

    /**
     * @var integer
     */
    protected $_indentionLevel = 0;

    /**
     * @var boolean
     */
    protected $_insideString = false;

    /**
     * @var TokenContainer
     */
    protected $_container = null;

    /**
     * @var  SplStack
     */
    protected $_switchStack = null;

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
     * Since Actions can be used multiple times, they need to reset themself each time they are used!
     */
    protected function reset()
    {
        $this->_insideUse = false;
        $this->_insideCase = false;
        $this->_insideSwitch = false;
        $this->_indentionLevel = 0;
        $this->_switchStack = new \SplStack();
    }

    /**
     * Unindents all Code and then indent it right
     *
     * @param \PHP\Manipulator\TokenContainer $container
     * @param mixed $params
     */
    public function run(TokenContainer $container, $params = null)
    {
        $this->reset();
        $this->_switchStack = new \SplStack();
        $this->_container = $container;
        $removeIndention = new RemoveIndention();
        $removeIndention->run($container);

        $iterator = $container->getIterator();

        $previous = null;
        while ($iterator->valid()) {
            $token = $iterator->current();
            $this->_checkAndChangeIndentionLevel($token);
            $this->_checkForMultilineCommentAndIndent($token);
            $this->_useIndentionCheck($token);
            $this->_switchIndentionCheck($token);

            if (null !== $previous && $this->evaluateConstraint('IsSinglelineComment', $previous) && !$this->_isWhitespaceWithBreak($token)) {
                $newToken = new Token('', T_WHITESPACE);
                $this->_indentWhitespace($newToken);
                $container->insertTokenAfter($previous, $newToken);
                $iterator = $container->getIterator();
                $iterator->seekToToken($token);
            } else if ($this->_isWhitespaceWithBreak($token)) {
                $iterator->next();
                if (!$iterator->valid()) {
                    break;
                }
                $nextToken = $iterator->current();
                $this->_checkAndChangeIndentionLevelDecreasment($nextToken);
                $this->_indentWhitespace($token);
                if ($this->isClosingCurlyBrace( $nextToken) && true === $this->_insideSwitch && true === $this->_insideCase) {
                    if ($this->_isSwitchClosingCurlyBrace($nextToken)) {
                        $this->_removeLastIndention($token);
                    }
                }
                $this->_checkForMultilineCommentAndIndent($nextToken);
                $this->_checkAndChangeIndentionLevelIncreasment($nextToken);
                $this->_useIndentionCheck($nextToken);
                $this->_switchIndentionCheck($nextToken);
            }
            $previous = $iterator->current();
            $iterator->next();
        }
        $container->retokenize();
    }

    /**
     * @param Token $token
     * @return boolean
     */
    protected function _isSwitchClosingCurlyBrace(Token $token)
    {
        return (($this->_switchStack[$this->_switchStack->count() - 1]+1) === $this->_indentionLevel);
    }

    /**
     * @param Token $token
     */
    protected function _removeLastIndention(Token $token)
    {
        $token->setValue(substr($token->getValue(), 0, -4));
    }

    /**
     * Check if a Token is a Multilinecomment and indent it
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
    protected function _useIndentionCheck(Token $token)
    {
        if ($this->isType($token, T_USE)) {
            $this->_insideUse = true;
            $this->_indentionLevel++;
        }

        if ($this->isSemicolon( $token) && true === $this->_insideUse) {
            $this->_insideUse = false;
            $this->_indentionLevel--;
        }
    }

    /**
     * @param Token $token
     */
    protected function _switchIndentionCheck(Token $token)
    {
        if ($this->isClosingCurlyBrace( $token) && true === $this->_insideSwitch) {
            if ($this->_switchStack[$this->_switchStack->count() - 1] === $this->_indentionLevel) {
                $this->_switchStack->pop();
                $this->_insideSwitch = false;
            }
        }
        if ($this->isType($token, T_SWITCH)) {
            $this->_insideSwitch = true;
            $this->_switchStack->push($this->_indentionLevel);
        }

        if ($this->isType($token, T_BREAK) && true === $this->_insideCase) {
            $this->_insideCase = false;
            $this->_indentionLevel--;
        }

        // only indent if case/default is not directly followed by case/default
        if ($this->isType($token, array(T_CASE, T_DEFAULT)) && !$this->_caseIsDirectlyFollowedByAnotherCase($token)) {
            if ($this->isType($token, array(T_CASE, T_DEFAULT)) &&
                true === $this->_insideCase &&
                !$this->_isCasePreceededByBreak($token)) {
                $this->_indentionLevel--;
            }
            $this->_insideCase = true;
            $this->_indentionLevel++;
        }
    }

    /**
     * @param Token $caseToken
     * @return boolean
     */
    protected function _isCasePreceededByBreak(Token $caseToken)
    {
        $iterator = $this->_container->getReverseIterator();
        $iterator->seekToToken($caseToken);
        // @todo add/test T_CLOSE_TAG, T_OPEN_TAG, T_INLINE_HTML
        return $this->isPrecededByTokenType($iterator, T_BREAK);
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
            if ($this->isColon( $token)) {
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
        // @todo add/test T_CLOSE_TAG, T_OPEN_TAG, T_INLINE_HTML
        return $this->isFollowedByTokenType($iterator, array(T_DEFAULT,T_CASE));
    }

    /**
     * @param \PHP\Manipulator\Token $whitespaceToken
     */
    protected function _indentWhitespace(Token $whitespaceToken)
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
        return $this->isType($token, T_WHITESPACE) &&
        $this->evaluateConstraint('ContainsNewline', $token);
    }

    /**
     * @param Token $token
     */
    protected function _checkInsideString(Token $token)
    {
        // @todo isQuote-Constraint ?
        if (null === $token->getType() && $token->getValue() === '"') {
            $this->_insideString = !$this->_insideString;
        }
    }

    /**
     * @param Token $token
     */
    public function _checkAndChangeIndentionLevel(Token $token)
    {
        $this->_checkInsideString($token);
        if (false === $this->_insideString) {
            $this->_checkAndChangeIndentionLevelDecreasment($token);
            $this->_checkAndChangeIndentionLevelIncreasment($token);
        }
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
        return $this->isOpeningCurlyBrace( $token)
        || $this->isOpeningBrace( $token);
    }

    /**
     * @param \PHP\Manipulator\Token $token
     * @return boolean
     */
    protected function _isIndentionLevelDecreasement(Token $token)
    {
        return $this->isClosingCurlyBrace( $token)
        || $this->isClosingBrace( $token);
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