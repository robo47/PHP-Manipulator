<?php

namespace PHP\Manipulator\Action;

use PHP\Manipulator\Action;
use PHP\Manipulator\Helper\NewlineDetector;
use PHP\Manipulator\MatcherFactory;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;
use PHP\Manipulator\TokenContainer\TokenContainerIterator;
use SplStack;

/**
 * @todo should only apply breaks before/after curly-braces if they are part of an if/else ?!?
 */
class FormatIfElseifElse extends Action
{
    const OPTION_SPACE_AFTER_IF                     = 'spaceAfterIf';
    const OPTION_SPACE_AFTER_ELSEIF                 = 'spaceAfterElseif';
    const OPTION_SPACE_AFTER_ELSE                   = 'spaceAfterElse';
    const OPTION_SPACE_BEFORE_ELSEIF                = 'spaceBeforeElseif';
    const OPTION_SPACE_BEFORE_ELSE                  = 'spaceBeforeElse';
    const OPTION_BREAK_AFTER_CURLY_BRACE_OF_IF      = 'breakAfterCurlyBraceOfIf';
    const OPTION_BREAK_AFTER_CURLY_BRACE_OF_ELSE    = 'breakAfterCurlyBraceOfElse';
    const OPTION_BREAK_AFTER_CURLY_BRACE_OF_ELSEIF  = 'breakAfterCurlyBraceOfElseif';
    const OPTION_BREAK_BEFORE_CURLY_BRACE_OF_ELSE   = 'breakBeforeCurlyBraceOfElse';
    const OPTION_BREAK_BEFORE_CURLY_BRACE_OF_ELSEIF = 'breakBeforeCurlyBraceOfElseif';
    const OPTION_BREAK_AFTER_IF                     = 'breakAfterIf';
    const OPTION_BREAK_AFTER_ELSE                   = 'breakAfterElse';
    const OPTION_BREAK_AFTER_ELSEIF                 = 'breakAfterElseif';
    const OPTION_BREAK_BEFORE_ELSE                  = 'breakBeforeElse';
    const OPTION_BREAK_BEFORE_ELSEIF                = 'breakBeforeElseif';
    const OPTION_SPACE_BEFORE_IF_EXPRESSION         = 'spaceBeforeIfExpression';
    const OPTION_SPACE_AFTER_IF_EXPRESSION          = 'spaceAfterIfExpression';
    const OPTION_SPACE_BEFORE_ELSEIF_EXPRESSION     = 'spaceBeforeElseifExpression';
    const OPTION_SPACE_AFTER_ELSEIF_EXPRESSION      = 'spaceAfterElseifExpression';

    /**
     * @var SplStack
     */
    private $ifStack;

    /**
     * @var SplStack
     */
    private $elseStack;

    /**
     * @var SplStack
     */
    private $elseIfStack;

    /**
     * @var TokenContainer
     */
    private $container;

    /**
     * @var int
     */
    private $level = 0;

    /**
     * @var string
     */
    private $defaultLineBreak = "\n";

    public function init()
    {
        if (!$this->hasOption(self::OPTION_SPACE_AFTER_IF)) {
            $this->setOption(self::OPTION_SPACE_AFTER_IF, true);
        }
        if (!$this->hasOption(self::OPTION_SPACE_AFTER_ELSEIF)) {
            $this->setOption(self::OPTION_SPACE_AFTER_ELSEIF, true);
        }
        if (!$this->hasOption(self::OPTION_SPACE_AFTER_ELSE)) {
            $this->setOption(self::OPTION_SPACE_AFTER_ELSE, true);
        }

        if (!$this->hasOption(self::OPTION_SPACE_BEFORE_ELSEIF)) {
            $this->setOption(self::OPTION_SPACE_BEFORE_ELSEIF, true);
        }
        if (!$this->hasOption(self::OPTION_SPACE_BEFORE_ELSE)) {
            $this->setOption(self::OPTION_SPACE_BEFORE_ELSE, true);
        }

        if (!$this->hasOption(self::OPTION_BREAK_AFTER_CURLY_BRACE_OF_IF)) {
            $this->setOption(self::OPTION_BREAK_AFTER_CURLY_BRACE_OF_IF, true);
        }
        if (!$this->hasOption(self::OPTION_BREAK_AFTER_CURLY_BRACE_OF_ELSE)) {
            $this->setOption(self::OPTION_BREAK_AFTER_CURLY_BRACE_OF_ELSE, true);
        }
        if (!$this->hasOption(self::OPTION_BREAK_AFTER_CURLY_BRACE_OF_ELSEIF)) {
            $this->setOption(self::OPTION_BREAK_AFTER_CURLY_BRACE_OF_ELSEIF, true);
        }

        if (!$this->hasOption(self::OPTION_BREAK_BEFORE_CURLY_BRACE_OF_ELSE)) {
            $this->setOption(self::OPTION_BREAK_BEFORE_CURLY_BRACE_OF_ELSE, true);
        }
        if (!$this->hasOption(self::OPTION_BREAK_BEFORE_CURLY_BRACE_OF_ELSEIF)) {
            $this->setOption(self::OPTION_BREAK_BEFORE_CURLY_BRACE_OF_ELSEIF, true);
        }

        if (!$this->hasOption(self::OPTION_BREAK_AFTER_IF)) {
            $this->setOption(self::OPTION_BREAK_AFTER_IF, false);
        }
        if (!$this->hasOption(self::OPTION_BREAK_AFTER_ELSE)) {
            $this->setOption(self::OPTION_BREAK_AFTER_ELSE, false);
        }
        if (!$this->hasOption(self::OPTION_BREAK_AFTER_ELSEIF)) {
            $this->setOption(self::OPTION_BREAK_AFTER_ELSEIF, false);
        }

        if (!$this->hasOption(self::OPTION_BREAK_BEFORE_ELSE)) {
            $this->setOption(self::OPTION_BREAK_BEFORE_ELSE, false);
        }
        if (!$this->hasOption(self::OPTION_BREAK_BEFORE_ELSEIF)) {
            $this->setOption(self::OPTION_BREAK_BEFORE_ELSEIF, false);
        }

        if (!$this->hasOption(self::OPTION_SPACE_BEFORE_IF_EXPRESSION)) {
            $this->setOption(self::OPTION_SPACE_BEFORE_IF_EXPRESSION, '');
        }
        if (!$this->hasOption(self::OPTION_SPACE_AFTER_IF_EXPRESSION)) {
            $this->setOption(self::OPTION_SPACE_AFTER_IF_EXPRESSION, '');
        }

        if (!$this->hasOption(self::OPTION_SPACE_BEFORE_ELSEIF_EXPRESSION)) {
            $this->setOption(self::OPTION_SPACE_BEFORE_ELSEIF_EXPRESSION, '');
        }
        if (!$this->hasOption(self::OPTION_SPACE_AFTER_ELSEIF_EXPRESSION)) {
            $this->setOption(self::OPTION_SPACE_AFTER_ELSEIF_EXPRESSION, '');
        }
    }

    /**
     * Reset all internal variables on each run
     */
    private function reset()
    {
        $this->ifStack     = new SplStack();
        $this->elseStack   = new SplStack();
        $this->elseIfStack = new SplStack();
        $this->level       = 0;
    }

    public function run(TokenContainer $container)
    {
        $this->container = $container;

        $helper                 = new NewlineDetector();
        $this->defaultLineBreak = $helper->getNewlineFromContainer($container);

        $iterator = $container->getIterator();
        $this->reset();

        while ($iterator->valid()) {
            $token = $iterator->current();

            if ($token->isType(T_IF)) {
                $this->ifStack->push($this->level);
                $this->format($iterator);
                $this->handleSpaceBeforeAndAfterExpressions($iterator);
            }
            if ($token->isType(T_ELSEIF)) {
                $this->elseIfStack->push($this->level);
                $this->format($iterator);
                $this->handleSpaceBeforeAndAfterExpressions($iterator);
            }
            if ($token->isType(T_ELSE)) {
                $this->elseStack->push($this->level);
                $this->format($iterator);
            }
            if ($token->isOpeningCurlyBrace()) {
                $this->level++;
                $this->applyBreaksAfterCurlyBraces($iterator);

                // what does the following code really do ? subtracting ints from booleans ?
                if (true === $this->getOption(self::OPTION_BREAK_AFTER_IF) &&
                    $this->stackHasLevelMatchingItem($this->ifStack) - 1
                ) {
                    $this->addLineBreakBeforeCurrentToken($iterator);
                }
                if (true === $this->getOption(self::OPTION_BREAK_AFTER_ELSEIF) &&
                    $this->stackHasLevelMatchingItem($this->elseIfStack) - 1
                ) {
                    $this->addLineBreakBeforeCurrentToken($iterator);
                }
                if (true === $this->getOption(self::OPTION_BREAK_AFTER_ELSE) &&
                    $this->stackHasLevelMatchingItem($this->elseStack) - 1
                ) {
                    $this->addLineBreakBeforeCurrentToken($iterator);
                }
            }

            if ($token->isClosingCurlyBrace()) {
                $this->level--;
                if (!$this->isPrecededByTokenType($iterator, T_WHITESPACE)) {
                    if ($this->shouldInsertBreakBeforeCurrentCurlyBrace()) {
                        $newToken = Token::createFromValueAndType($this->defaultLineBreak, T_WHITESPACE);
                        $this->container->insertTokenBefore($token, $newToken);
                        $iterator->update($token);
                    }
                }

                if (true === $this->getOption(self::OPTION_BREAK_BEFORE_CURLY_BRACE_OF_ELSE) &&
                    $this->isFollowedByTokenType($iterator, T_ELSE)
                ) {
                    $this->addLineBreakBeforeCurrentToken($iterator);
                }
                if (true === $this->getOption(self::OPTION_BREAK_BEFORE_CURLY_BRACE_OF_ELSEIF) &&
                    $this->isFollowedByTokenType($iterator, T_ELSEIF)
                ) {
                    $this->addLineBreakBeforeCurrentToken($iterator);
                }
                if (true === $this->getOption(self::OPTION_BREAK_BEFORE_ELSE) &&
                    $this->isFollowedByTokenType($iterator, T_ELSE)
                ) {
                    $this->addLineBreakAfterCurrentToken($iterator);
                }
                if (true === $this->getOption(self::OPTION_BREAK_BEFORE_ELSEIF) &&
                    $this->isFollowedByTokenType($iterator, T_ELSEIF)
                ) {
                    $this->addLineBreakAfterCurrentToken($iterator);
                }

                if ($this->stackHasLevelMatchingItem($this->ifStack)) {
                    $this->ifStack->pop();
                }
                if ($this->stackHasLevelMatchingItem($this->elseIfStack)) {
                    $this->elseIfStack->pop();
                }
                if ($this->stackHasLevelMatchingItem($this->elseStack)) {
                    $this->elseStack->pop();
                }
            }
            $iterator->next();
        }
        $container->retokenize();
    }

    /**
     * @param TokenContainerIterator $iterator
     */
    private function handleSpaceBeforeAndAfterExpressions(TokenContainerIterator $iterator)
    {
        $start = $iterator->current();
        // Adding spaces for expressions
        $openingBrace = $this->getNextMatchingToken($iterator, MatcherFactory::getTypeAndValueClosure(null, '('));
        if (null !== $openingBrace) {
            $iterator->seekToToken($openingBrace);
            $iterator->next();
            $foundToken              = $iterator->current();
            $spaceBeforeIfExpression = $this->getOption(self::OPTION_SPACE_BEFORE_IF_EXPRESSION);
            if ($foundToken->isWhitespace()) {
                $foundToken->setValue($spaceBeforeIfExpression);
            } else {
                $whitespaceToken = Token::createFromValueAndType($spaceBeforeIfExpression, T_WHITESPACE);
                $this->container->insertTokenBefore($foundToken, $whitespaceToken);
            }
            $iterator->update($openingBrace);

            // insert space before expression
            $closingBrace = $this->getMatchingBrace($iterator);
            if (null !== $closingBrace) {
                $iterator->seekToToken($closingBrace);
                $iterator->previous();
                $foundToken             = $iterator->current();
                $spaceAfterIfExpression = $this->getOption(self::OPTION_SPACE_AFTER_IF_EXPRESSION);
                if ($foundToken->isWhitespace()) {
                    $foundToken->setValue($spaceAfterIfExpression);
                } else {
                    $whitespaceToken = Token::createFromValueAndType($spaceAfterIfExpression, T_WHITESPACE);
                    $this->container->insertTokenAfter($foundToken, $whitespaceToken);
                }
            }
            // insert space after expression
            $iterator->update($start);
        }
    }

    /**
     * @param TokenContainerIterator $iterator
     */
    private function addLineBreakBeforeCurrentToken(TokenContainerIterator $iterator)
    {
        $token = $iterator->current();
        $iterator->previous();
        $currentToken = $iterator->current();
        if (!$currentToken->isWhitespace()) {
            $whitespaceToken = Token::createFromValueAndType($this->defaultLineBreak, T_WHITESPACE);
            $this->container->insertTokenBefore($token, $whitespaceToken);
        } elseif (!$currentToken->containsNewline()) {
            $currentToken->setValue($currentToken->getValue().$this->defaultLineBreak);
        }

        $iterator->update($token);
    }

    /**
     * @param TokenContainerIterator $iterator
     */
    private function addLineBreakAfterCurrentToken(TokenContainerIterator $iterator)
    {
        $token = $iterator->current();
        $iterator->next();
        $currentToken = $iterator->current();
        if (!$currentToken->isWhitespace()) {
            $whitespaceToken = Token::createFromValueAndType($this->defaultLineBreak, T_WHITESPACE);
            $this->container->insertTokenBefore($currentToken, $whitespaceToken);
        } else {
            $currentToken->setValue($this->defaultLineBreak);
        }
        $iterator->update($token);
    }

    /**
     * @return bool
     */
    private function shouldInsertBreakBeforeCurrentCurlyBrace()
    {
        if (true === $this->getOption(self::OPTION_BREAK_AFTER_CURLY_BRACE_OF_IF) &&
            $this->stackHasLevelMatchingItem($this->ifStack)
        ) {
            return true;
        }
        if (true === $this->getOption(self::OPTION_BREAK_AFTER_CURLY_BRACE_OF_ELSEIF) &&
            $this->stackHasLevelMatchingItem($this->elseIfStack)
        ) {
            return true;
        }
        if (true === $this->getOption(self::OPTION_BREAK_AFTER_CURLY_BRACE_OF_ELSE) &&
            $this->stackHasLevelMatchingItem($this->elseStack)
        ) {
            return true;
        }

        return false;
    }

    /**
     * @param SplStack $stack
     *
     * @return bool
     */
    private function stackHasLevelMatchingItem(SplStack $stack)
    {
        return (!$stack->isEmpty() && $this->level === $stack[count($stack) - 1]);
    }

    /**
     * @param TokenContainerIterator $iterator
     */
    private function applyBreaksAfterCurlyBraces(TokenContainerIterator $iterator)
    {
        $token = $iterator->current();
        if ($this->shouldInsertBreakAfterCurrentOpeningCurlyBrace($iterator)) {
            $newToken = Token::createFromValueAndType($this->defaultLineBreak, T_WHITESPACE);
            $this->container->insertTokenAfter($token, $newToken);
            $iterator->update();
            $iterator->seekToToken($token);
        }
    }

    /**
     * @param TokenContainerIterator $iterator
     *
     * @return bool
     */
    private function isFollowedByWhitespaceContainingBreak(TokenContainerIterator $iterator)
    {
        return $this->isFollowedByTokenMatchedByClosure(
            $iterator,
            function (Token $token) {
                return $token->containsNewline();
            }
        );
    }

    /**
     * @param TokenContainerIterator $iterator
     *
     * @return bool
     */
    private function shouldInsertBreakAfterCurrentOpeningCurlyBrace(TokenContainerIterator $iterator)
    {
        if (true === $this->getOption(self::OPTION_BREAK_AFTER_CURLY_BRACE_OF_IF) &&
            $this->isOpeningBraceAfterType(T_IF, $iterator) &&
            !$this->isFollowedByWhitespaceContainingBreak($iterator)
        ) {
            return true;
        }
        if (true === $this->getOption(self::OPTION_BREAK_AFTER_CURLY_BRACE_OF_ELSEIF) &&
            $this->isOpeningBraceAfterType(T_ELSEIF, $iterator) &&
            !$this->isFollowedByWhitespaceContainingBreak($iterator)
        ) {
            return true;
        }
        if (true === $this->getOption(self::OPTION_BREAK_AFTER_CURLY_BRACE_OF_ELSE) &&
            $this->isOpeningBraceAfterType(T_ELSE, $iterator) &&
            !$this->isFollowedByWhitespaceContainingBreak($iterator)
        ) {
            return true;
        }

        return false;
    }

    /**
     * @param TokenContainerIterator $iterator
     */
    private function removeNextToken(TokenContainerIterator $iterator)
    {
        $token = $iterator->current();
        $iterator->next();
        $this->container->removeToken($iterator->current());
        $iterator->update($token);
    }

    /**
     * @param TokenContainerIterator $iterator
     */
    private function removePreviousToken(TokenContainerIterator $iterator)
    {
        $token = $iterator->current();
        $iterator->previous();
        $this->container->removeToken($iterator->current());
        $iterator->update($token);
    }

    /**
     * @param TokenContainerIterator $iterator
     *
     * @return bool
     */
    private function isFollowedByWrongWhitespace(TokenContainerIterator $iterator)
    {
        $iterator->next();
        $newToken = $iterator->current();
        $iterator->previous();
        if ($newToken->isWhitespace() && $newToken->getValue() !== ' ') {
            return true;
        }

        return false;
    }

    /**
     * @param TokenContainerIterator $iterator
     */
    private function addWhitespaceTokenAfter(TokenContainerIterator $iterator)
    {
        $token           = $iterator->current();
        $whitespaceToken = Token::createFromValueAndType(' ', null);
        $this->container->insertTokenAfter($token, $whitespaceToken);
        $iterator->update($token);
    }

    /**
     * @param TokenContainerIterator $iterator
     */
    private function addWhitespaceTokenBefore(TokenContainerIterator $iterator)
    {
        $token           = $iterator->current();
        $whitespaceToken = Token::createFromValueAndType(' ', null);
        $this->container->insertTokenBefore($token, $whitespaceToken);
        $iterator->update($token);
    }

    /**
     * @param TokenContainerIterator $iterator
     */
    private function setNextTokenValueToOneSpace(TokenContainerIterator $iterator)
    {
        $token = $iterator->current();
        $iterator->next();
        $iterator->current()->setValue(' ');
        $iterator->update($token);
    }

    /**
     * @param TokenContainerIterator $iterator
     */
    private function setPreviousTokenValueToOneSpace(TokenContainerIterator $iterator)
    {
        $token = $iterator->current();
        $iterator->previous();
        $iterator->current()->setValue(' ');
        $iterator->update($token);
    }

    /**
     * @param TokenContainerIterator $iterator
     */
    private function format(TokenContainerIterator $iterator)
    {
        $token = $iterator->current();
        $type  = ucfirst(strtolower(substr($token->getTokenName(), 2)));

        if (!$this->isFollowedByTokenType($iterator, T_WHITESPACE) &&
            true === $this->getOption('spaceAfter'.$type)
        ) {
            $this->addWhitespaceTokenAfter($iterator);
        } elseif ($this->isFollowedByWrongWhitespace($iterator) &&
            true === $this->getOption('spaceAfter'.$type)
        ) {
            $this->setNextTokenValueToOneSpace($iterator);
        } elseif (false === $this->getOption('spaceAfter'.$type) &&
            $this->isFollowedByTokenType($iterator, T_WHITESPACE)
        ) {
            $this->removeNextToken($iterator);
        }

        // If it is not an if, and should not break before else
        if (!$token->isType(T_IF) && false === $this->getOption(self::OPTION_BREAK_BEFORE_ELSE)) {
            if (!$this->isPrecededByTokenType($iterator, T_WHITESPACE) &&
                true === $this->getOption('spaceBefore'.$type)
            ) {
                $this->addWhitespaceTokenBefore($iterator);
            } elseif ($this->isPrecededByWrongWhitespace($iterator) &&
                true === $this->getOption('spaceBefore'.$type)
            ) {
                $this->setPreviousTokenValueToOneSpace($iterator);
            } elseif (false === $this->getOption('spaceBefore'.$type) &&
                $this->isPrecededByTokenType($iterator, T_WHITESPACE)
            ) {
                $this->removePreviousToken($iterator);
            }
        }
    }

    /**
     * @param TokenContainerIterator $iterator
     *
     * @return bool
     */
    private function isPrecededByWrongWhitespace(TokenContainerIterator $iterator)
    {
        $iterator->previous();
        $newToken = $iterator->current();
        $iterator->next();
        if ($newToken->isWhitespace() && $newToken->getValue() !== ' ') {
            return true;
        }

        return false;
    }

    /**
     * @param int|null               $type
     * @param TokenContainerIterator $iterator
     *
     * @return bool
     */
    private function isOpeningBraceAfterType($type, TokenContainerIterator $iterator)
    {
        $token = $iterator->current();
        if (!$token->isOpeningCurlyBrace()) {
            return false;
        }
        $breakTokens    = [T_CLASS, T_FUNCTION, T_IF, T_ELSE, T_ELSEIF];
        $filterCallback = function ($tokentype) use ($type) {
            return ($tokentype === $type) ? false : true;
        };
        $breakTokens = array_filter($breakTokens, $filterCallback);
        $result      = false;

        while ($iterator->valid()) {
            $iterator->previous();
            if ($iterator->valid() === false) {
                $result = false;
                break;
            }

            $current = $iterator->current();
            if ($current->isType($breakTokens)) {
                $result = false;
                break;
            }
            if ($current->isType($type)) {
                $result = true;
                break;
            }
            $iterator->previous();
        }
        $iterator->seekToToken($token);

        return $result;
    }
}
