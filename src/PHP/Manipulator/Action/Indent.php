<?php

namespace PHP\Manipulator\Action;

use PHP\Manipulator\Action;
use PHP\Manipulator\Exception\ActionException;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;
use PHP\Manipulator\TokenManipulator\IndentMultilineComment;
use SplStack;

class Indent extends Action
{
    const OPTION_USE_SPACES              = 'useSpaces';
    const OPTION_TAB_WIDTH               = 'tabWidth';
    const OPTION_INDENTION_WIDTH         = 'indentionWidth';
    const OPTION_INITIAL_INDENTION_WIDTH = 'initialIndentionWidth';

    /**
     * @var bool
     */
    private $insideUse = false;

    /**
     * @var bool
     */
    private $insideCase = false;

    /**
     * @var bool
     */
    private $insideSwitch = false;

    /**
     * @var int
     */
    private $indentionLevel = 0;

    /**
     * @var bool
     */
    private $insideString = false;

    /**
     * @var TokenContainer
     */
    private $container;

    /**
     * @var SplStack
     */
    private $switchStack;

    public function init()
    {
        // indentions are always given in tabs!
        if (!$this->hasOption(self::OPTION_USE_SPACES)) {
            $this->setOption(self::OPTION_USE_SPACES, true);
        }
        if (!$this->hasOption(self::OPTION_TAB_WIDTH)) {
            $this->setOption(self::OPTION_TAB_WIDTH, 4);
        }
        if (!$this->hasOption(self::OPTION_INDENTION_WIDTH)) {
            $this->setOption(self::OPTION_INDENTION_WIDTH, 4);
        }
        if (!$this->hasOption(self::OPTION_INITIAL_INDENTION_WIDTH)) {
            $this->setOption(self::OPTION_INITIAL_INDENTION_WIDTH, 0);
        }
    }

    /**
     * Since Actions can be used multiple times, they need to reset themself each time they are used!
     */
    private function reset()
    {
        $this->insideUse      = false;
        $this->insideCase     = false;
        $this->insideSwitch   = false;
        $this->indentionLevel = 0;
        $this->switchStack    = new SplStack();
    }

    public function run(TokenContainer $container)
    {
        $this->reset();
        $this->container = $container;

        $removeIndention = new RemoveIndention();
        $removeIndention->run($container);

        $iterator = $container->getIterator();

        /** @var Token $previous */
        $previous = null;
        while ($iterator->valid()) {
            $token = $iterator->current();
            $this->checkAndChangeIndentionLevel($token);
            $this->checkForMultilineCommentAndIndent($token);
            $this->useIndentionCheck($token);
            $this->switchIndentionCheck($token);
            if (null !== $previous &&
                $previous->isSingleLineComment() &&
                !$this->isWhitespaceWithNewline($token)
            ) {
                $newToken = Token::createFromValueAndType('', T_WHITESPACE);
                $this->indentWhitespace($newToken);
                $container->insertTokenAfter($previous, $newToken);
                $iterator = $container->getIterator();
                $iterator->seekToToken($token);
            } elseif ($this->isWhitespaceWithNewline($token)) {
                $iterator->next();
                if (!$iterator->valid()) {
                    break;
                }
                $nextToken = $iterator->current();
                $this->checkAndChangeIndentionLevelDecreasment($nextToken);
                $this->indentWhitespace($token);
                if ($nextToken->isClosingCurlyBrace() &&
                    true === $this->insideSwitch &&
                    true === $this->insideCase
                ) {
                    if ($this->isSwitchClosingCurlyBrace()) {
                        $this->removeLastIndention($token);
                    }
                }
                $this->checkForMultilineCommentAndIndent($nextToken);
                $this->checkAndChangeIndentionLevelIncreasment($nextToken);
                $this->useIndentionCheck($nextToken);
                $this->switchIndentionCheck($nextToken);
            }
            $previous = $iterator->current();
            $iterator->next();
        }
        $container->retokenize();
    }

    /**
     * @return bool
     */
    private function isSwitchClosingCurlyBrace()
    {
        return (($this->switchStack[$this->switchStack->count() - 1] + 1) === $this->indentionLevel);
    }

    /**
     * @param Token $token
     */
    private function removeLastIndention(Token $token)
    {
        $length = mb_strlen($this->getIndention(1));
        $token->setValue(substr($token->getValue(), 0, -$length));
    }

    /**
     * @param Token $token
     */
    private function checkForMultilineCommentAndIndent(Token $token)
    {
        if ($token->isMultilineComment()) {
            $this->manipulateToken(
                IndentMultilineComment::class,
                $token,
                $this->getIndention($this->getIndentionLevel())
            );
        }
    }

    /**
     * @param Token $token
     */
    private function useIndentionCheck(Token $token)
    {
        if ($token->isType(T_USE)) {
            $this->insideUse = true;
            $this->indentionLevel++;
        }

        if ($token->isSemicolon() && true === $this->insideUse) {
            $this->insideUse = false;
            $this->indentionLevel--;
        }
    }

    /**
     * @param Token $token
     */
    private function switchIndentionCheck(Token $token)
    {
        if ($token->isClosingCurlyBrace() && true === $this->insideSwitch) {
            if ($this->switchStack[$this->switchStack->count() - 1] === $this->indentionLevel) {
                $this->switchStack->pop();
                $this->insideSwitch = false;
            }
        }
        if ($token->isType(T_SWITCH)) {
            $this->insideSwitch = true;
            $this->switchStack->push($this->indentionLevel);
        }

        if ($token->isType(T_BREAK) && true === $this->insideCase) {
            $this->insideCase = false;
            $this->indentionLevel--;
        }

        // only indent if case/default is not directly followed by case/default
        if ($token->isType([T_CASE, T_DEFAULT]) &&
            !$this->caseIsDirectlyFollowedByAnotherCase($token)
        ) {
            if ($token->isType([T_CASE, T_DEFAULT]) &&
                true === $this->insideCase &&
                !$this->isCasePreceededByBreak($token)
            ) {
                $this->indentionLevel--;
            }
            $this->insideCase = true;
            $this->indentionLevel++;
        }
    }

    /**
     * @param Token $caseToken
     *
     * @return bool
     */
    private function isCasePreceededByBreak(Token $caseToken)
    {
        $iterator = $this->container->getReverseIterator();
        $iterator->seekToToken($caseToken);

        return $this->isPrecededByTokenType($iterator, T_BREAK);
    }

    /**
     * @param Token $caseToken
     *
     * @return Token
     */
    private function findNextColonToken(Token $caseToken)
    {
        $iterator = $this->container->getIterator();
        $iterator->seekToToken($caseToken);
        $iterator->next();
        while ($iterator->valid()) {
            $token = $iterator->current();
            if ($token->isColon()) {
                return $iterator->current();
            }
            $iterator->next();
        }
        throw new ActionException('No colon found', ActionException::NO_COLON_FOUND);
    }

    /**
     * @param Token $caseToken
     *
     * @return bool
     */
    private function caseIsDirectlyFollowedByAnotherCase(Token $caseToken)
    {
        $iterator = $this->container->getIterator();
        $iterator->seekToToken($this->findNextColonToken($caseToken));

        return $this->isFollowedByTokenType($iterator, [T_DEFAULT, T_CASE]);
    }

    /**
     * @param Token $whitespaceToken
     */
    private function indentWhitespace(Token $whitespaceToken)
    {
        $newValue = sprintf(
            '%s%s',
            $whitespaceToken->getValue(),
            $this->getIndention($this->getIndentionLevel())
        );
        $whitespaceToken->setValue($newValue);
    }

    /**
     * @param Token $token
     *
     * @return bool
     */
    private function isWhitespaceWithNewline(Token $token)
    {
        return $token->isWhitespace() && $token->containsNewline();
    }

    /**
     * @param Token $token
     */
    private function checkInsideString(Token $token)
    {
        if ($token->isDoublequote()) {
            $this->insideString = !$this->insideString;
        }
    }

    /**
     * @param Token $token
     */
    public function checkAndChangeIndentionLevel(Token $token)
    {
        $this->checkInsideString($token);
        if (false === $this->insideString) {
            $this->checkAndChangeIndentionLevelDecreasment($token);
            $this->checkAndChangeIndentionLevelIncreasment($token);
        }
    }

    /**
     * @param Token $token
     */
    public function checkAndChangeIndentionLevelIncreasment(Token $token)
    {
        if ($token->isColon()) {
            $iterator = $this->container->getIterator();
            $iterator->seekToToken($token);
            $allowedTypes = [null, T_STRING, T_WHITESPACE, T_COMMENT, T_DOC_COMMENT];

            if ($this->isPrecededByTokenType($iterator, T_IF, $allowedTypes)) {
                $this->indentionLevel++;
            }
        }
        if ($this->isIndentionLevelIncreasment($token)) {
            $this->indentionLevel++;
        }
    }

    /**
     * @param Token $token
     */
    public function checkAndChangeIndentionLevelDecreasment(Token $token)
    {
        if ($token->isType(T_ENDIF)) {
            $this->indentionLevel--;
        }
        if ($this->isIndentionLevelDecreasement($token)) {
            $this->indentionLevel--;
        }
    }

    /**
     * @return int
     */
    public function getIndentionLevel()
    {
        return $this->indentionLevel;
    }

    /**
     * @param Token $token
     *
     * @return bool
     */
    private function isIndentionLevelIncreasment(Token $token)
    {
        return $token->isOpeningCurlyBrace() || $token->isOpeningBrace();
    }

    /**
     * @param Token $token
     *
     * @return bool
     */
    private function isIndentionLevelDecreasement(Token $token)
    {
        return $token->isClosingCurlyBrace() || $token->isClosingBrace();
    }

    /**
     * @param int $depth
     *
     * @return string
     */
    public function getIndention($depth)
    {
        $useSpaces             = $this->getOption(self::OPTION_USE_SPACES);
        $tabWidth              = $this->getOption(self::OPTION_TAB_WIDTH);
        $indentionWidth        = $this->getOption(self::OPTION_INDENTION_WIDTH);
        $initialIndentionWidth = $this->getOption(self::OPTION_INITIAL_INDENTION_WIDTH);

        $indentionLength = ($indentionWidth * $depth) + $initialIndentionWidth;
        if (!$useSpaces) {
            $indention = $this->getAsTabs($indentionLength, $tabWidth);
        } else {
            $indention = @str_repeat(' ', $indentionLength);
        }

        return $indention;
    }

    /**
     * @param int $spaceLength
     * @param int $tabWidth
     *
     * @return string
     */
    public function getAsTabs($spaceLength, $tabWidth)
    {
        $tabCount         = floor($spaceLength / $tabWidth);
        $additionalSpaces = $spaceLength % $tabWidth;

        return sprintf('%s%s', str_repeat("\t", $tabCount), str_repeat(' ', $additionalSpaces));
    }
}
