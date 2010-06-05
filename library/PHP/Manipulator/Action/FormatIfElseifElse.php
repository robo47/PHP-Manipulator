<?php

namespace PHP\Manipulator\Action;

use PHP\Manipulator\Action;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;
use PHP\Manipulator\TokenContainer\Iterator;
use PHP\Manipulator\ClosureFactory;

class FormatIfElseifElse
extends Action
{
    /**
     * @var SplStack
     */
    protected $_ifStack = null;

    /**
     * @var SplStack
     */
    protected $_elseStack = null;

    /**
     * @var SplStack
     */
    protected $_elseifStack = null;

    /**
     * @var \PHP\Manipulator\TokenContainer\Iterator
     */
    protected $_container = null;

    /**
     * @var integer
     */
    protected $_level = 0;

    /**
     * @todo so many options are great but there needs to be an easy way to express what you want too!
     */
    public function init()
    {
        if (!$this->hasOption('spaceAfterIf')) {
            $this->setOption('spaceAfterIf', true);
        }
        if (!$this->hasOption('spaceAfterElseif')) {
            $this->setOption('spaceAfterElseif', true);
        }
        if (!$this->hasOption('spaceAfterElse')) {
            $this->setOption('spaceAfterElse', true);
        }
        if (!$this->hasOption('spaceBeforeIf')) {
            $this->setOption('spaceBeforeIf', true);
        }
        if (!$this->hasOption('spaceBeforeElseif')) {
            $this->setOption('spaceBeforeElseif', true);
        }
        if (!$this->hasOption('spaceBeforeElse')) {
            $this->setOption('spaceBeforeElse', true);
        }

        if (!$this->hasOption('breakAfterCurlyBraceOfIf')) {
            $this->setOption('breakAfterCurlyBraceOfIf', true);
        }
        if (!$this->hasOption('breakAfterCurlyBraceOfElse')) {
            $this->setOption('breakAfterCurlyBraceOfElse', true);
        }
        if (!$this->hasOption('breakAfterCurlyBraceOfElseif')) {
            $this->setOption('breakAfterCurlyBraceOfElseif', true);
        }

        if (!$this->hasOption('breakBeforeCurlyBraceOfElse')) {
            $this->setOption('breakBeforeCurlyBraceOfElse', true);
        }
        if (!$this->hasOption('breakBeforeCurlyBraceOfElseif')) {
            $this->setOption('breakBeforeCurlyBraceOfElseif', true);
        }

        if (!$this->hasOption('breakAfterIf')) {
            $this->setOption('breakAfterIf', false);
        }
        if (!$this->hasOption('breakAfterElse')) {
            $this->setOption('breakAfterElse', false);
        }
        if (!$this->hasOption('breakAfterElseif')) {
            $this->setOption('breakAfterElseif', false);
        }

        if (!$this->hasOption('breakBeforeElse')) {
            $this->setOption('breakBeforeElse', false);
        }
        if (!$this->hasOption('breakBeforeElseif')) {
            $this->setOption('breakBeforeElseif', false);
        }

        if (!$this->hasOption('spaceBeforeIfExpression')) {
            $this->setOption('spaceBeforeIfExpression', '');
        }
        if (!$this->hasOption('spaceAfterIfExpression')) {
            $this->setOption('spaceAfterIfExpression', '');
        }

        if (!$this->hasOption('spaceBeforeElseifExpression')) {
            $this->setOption('spaceBeforeElseifExpression', '');
        }
        if (!$this->hasOption('spaceAfterElseifExpression')) {
            $this->setOption('spaceAfterElseifExpression', '');
        }
    }

    /**
     * Reset all internal variables on each run
     */
    protected function _reset()
    {
        $this->_ifStack = new \SplStack();
        $this->_elseStack = new \SplStack();
        $this->_elseifStack = new \SplStack();
        $this->_level = 0;
    }

    /**
     * Run Action
     *
     * @param \PHP\Manipulator\TokenContainer $container
     */
    public function run(TokenContainer $container)
    {
        $this->_container = $container;
        $iterator = $container->getIterator();
        $this->_reset();

        while ($iterator->valid()) {
            $token = $iterator->current();

            if ($this->isType($token, (T_IF))) {
                $this->_ifStack->push($this->_level);
                $this->_format($iterator);
                $this->_handleSpaceBeforeAndAfterExpressions($iterator);
            }
            if ($this->isType($token, (T_ELSEIF))) {
                $this->_elseifStack->push($this->_level);
                $this->_format($iterator);
                $this->_handleSpaceBeforeAndAfterExpressions($iterator);
            }
            if ($this->isType($token, (T_ELSE))) {
                $this->_elseStack->push($this->_level);
                $this->_format($iterator);
            }
            if ($this->isOpeningCurlyBrace($token)) {
                $this->_level++;
                $this->_applyBreaksAfterCurlyBraces($iterator);

                if (true === $this->getOption('breakAfterIf') && $this->_stackHasLevelMatchingItem($this->_ifStack) - 1 ) {
                    $this->_addLineBreakBeforeCurrentToken($iterator);
                }
                if (true === $this->getOption('breakAfterElseif') && $this->_stackHasLevelMatchingItem($this->_elseifStack) - 1 ) {
                    $this->_addLineBreakBeforeCurrentToken($iterator);
                }
                if (true === $this->getOption('breakAfterElse') && $this->_stackHasLevelMatchingItem($this->_elseStack) - 1 ) {
                    $this->_addLineBreakBeforeCurrentToken($iterator);
                }
            }

            if ($this->isClosingCurlyBrace($token)) {
                $this->_level--;
                if (!$this->isPrecededByTokenType($iterator, T_WHITESPACE)) {
                    if ($this->_shouldInsertBreakBeforeCurrentCurlyBrace()) {
                        // @todo use container default linebreak ? fallback to \n
                        $newToken = new Token("\n", T_WHITESPACE);
                        $this->_container->insertTokenBefore($token, $newToken);
                        // @todo should update container -> creating and inserting new token into new function
                    }
                }

                if (true === $this->getOption('breakBeforeCurlyBraceOfElse') && $this->isFollowedByTokenType($iterator, T_ELSE)) {
                    $this->_addLineBreakBeforeCurrentToken($iterator);
                }
                if (true === $this->getOption('breakBeforeCurlyBraceOfElseif') && $this->isFollowedByTokenType($iterator, T_ELSEIF)) {
                    $this->_addLineBreakBeforeCurrentToken($iterator);
                }
                if (true === $this->getOption('breakBeforeElse') && $this->isFollowedByTokenType($iterator, T_ELSE)) {
                    $this->_addLineBreakAfterCurrentToken($iterator);
                }
                if (true === $this->getOption('breakBeforeElseif') && $this->isFollowedByTokenType($iterator, T_ELSEIF)) {
                    $this->_addLineBreakAfterCurrentToken($iterator);
                }

                if ($this->_stackHasLevelMatchingItem($this->_ifStack)) {
                    $this->_ifStack->pop();
                }
                if ($this->_stackHasLevelMatchingItem($this->_elseifStack)) {
                    $this->_elseifStack->pop();
                }
                if ($this->_stackHasLevelMatchingItem($this->_elseStack)) {
                    $this->_elseStack->pop();
                }
            }
            $iterator->next();
        }
        $container->retokenize();
    }

    /**
     *
     * @param \PHP\Manipulator\TokenContainer\Iterator $iterator
     */
    protected function _handleSpaceBeforeAndAfterExpressions(Iterator $iterator)
    {
        $start = $iterator->current();
        // Adding spaces for expressions
        $openingBrace = $this->getNextMatchingToken($iterator, ClosureFactory::getTypeAndValueClosure(null, '('));
        if (null !== $openingBrace) {
            $iterator->seekToToken($openingBrace);
            $iterator->next();
            $foundToken = $iterator->current();
            if ($this->isType($foundToken, T_WHITESPACE)) {
                $foundToken->setValue($this->getOption('spaceBeforeIfExpression'));
            } else {
                $whitespaceToken = new Token($this->getOption('spaceBeforeIfExpression'), T_WHITESPACE);
                $this->_container->insertTokenBefore($foundToken, $whitespaceToken);
            }
            $iterator->update($openingBrace);

            // insert space before expression
            $closingBrace = $this->getMatchingBrace($iterator);
            if (null !== $closingBrace)  {
                $iterator->seekToToken($closingBrace);
                $iterator->previous();
                $foundToken = $iterator->current();
                if ($this->isType($foundToken, T_WHITESPACE)) {
                    $foundToken->setValue($this->getOption('spaceAfterIfExpression'));
                } else {
                    $whitespaceToken = new Token($this->getOption('spaceAfterIfExpression'), T_WHITESPACE);
                    $this->_container->insertTokenAfter($foundToken, $whitespaceToken);
                }
            } else {
                throw new \Exception('fuck something went wrong');
            }
            // insert space after expression
            $iterator->update($start);
        }
    }

    /**
     * @param \PHP\Manipulator\TokenContainer\Iterator $iterator
     */
    protected function _addLineBreakBeforeCurrentToken(Iterator $iterator)
    {
        $token = $iterator->current();
        $iterator->previous();
        $currentToken = $iterator->current();
        if (!$this->isType($currentToken, T_WHITESPACE)) {
            // @todo use container default linebreak ? fallback to \n
            $whitespaceToken = new Token("\n", T_WHITESPACE);
            $this->_container->insertTokenBefore($token, $whitespaceToken);
        } elseif (!$this->evaluateConstraint('ContainsNewline', $currentToken)) {
            $currentToken->setValue($currentToken->getValue() . "\n");
        }

        $iterator->update($token);
    }

    /**
     * @param \PHP\Manipulator\TokenContainer\Iterator $iterator
     */
    protected function _addLineBreakAfterCurrentToken(Iterator $iterator)
    {
        $token = $iterator->current();
        $iterator->next();
        $currentToken = $iterator->current();
        if (!$this->isType($currentToken, T_WHITESPACE)) {
            // @todo use container default linebreak ? fallback to \n
            $whitespaceToken = new Token("\n", T_WHITESPACE);
            $this->_container->insertTokenBefore($currentToken, $whitespaceToken);
        } else {
            $currentToken->setValue("\n");
        }
        $iterator->update($token);
    }

    /**
     * @return boolean
     */
    protected function _shouldInsertBreakBeforeCurrentCurlyBrace()
    {
        if (true === $this->getOption('breakAfterCurlyBraceOfIf') &&
            $this->_stackHasLevelMatchingItem($this->_ifStack)) {
            return true;
        }
        if (true === $this->getOption('breakAfterCurlyBraceOfElseif') &&
            $this->_stackHasLevelMatchingItem($this->_elseifStack)) {
            return true;
        }
        if (true === $this->getOption('breakAfterCurlyBraceOfElse') &&
            $this->_stackHasLevelMatchingItem($this->_elseStack)) {
            return true;
        }
        return false;
    }

    /**
     * @param \SplStack $stack
     * @return boolean
     */
    protected function _stackHasLevelMatchingItem(\SplStack $stack)
    {
        return (!$stack->isEmpty() && $this->_level === $stack[count($stack) -1]);
    }

    /**
     * @param \PHP\Manipulator\TokenContainer\Iterator $iterator
     */
    protected function _applyBreaksAfterCurlyBraces(Iterator $iterator)
    {
        $token = $iterator->current();
        if ($this->_shouldInsertBreakAfterCurrentOpeningCurlyBrace($iterator)) {
            // @todo use container default linebreak ? fallback to \n
            $newToken = new Token("\n", T_WHITESPACE);
            $this->_container->insertTokenAfter($token, $newToken);
            $iterator->update();
            $iterator->seekToToken($token);
        }
    }

    /**
     * @param Iterator $iterator
     * @return boolean
     */
    protected function _isFollowedByWhitespaceContainingBreak(Iterator $iterator)
    {
        return $this->isFollowedByTokenMatchedByClosure(
            $iterator,
            function(Token $token) {
                $constraint = new \PHP\Manipulator\TokenConstraint\ContainsNewline();
                return $constraint->evaluate($token);
            }
        );
    }

    /**
     * @param \PHP\Manipulator\TokenContainer\Iterator $iterator
     * @return boolean
     */
    protected function _shouldInsertBreakAfterCurrentOpeningCurlyBrace(Iterator $iterator)
    {
        if (true === $this->getOption('breakAfterCurlyBraceOfIf') &&
            $this->_isOpeningBraceAfterType(T_IF, $iterator) &&
            !$this->_isFollowedByWhitespaceContainingBreak($iterator)) {
            return true;
        }
        if (true === $this->getOption('breakAfterCurlyBraceOfElseif') &&
            $this->_isOpeningBraceAfterType( T_ELSEIF, $iterator) &&
            !$this->_isFollowedByWhitespaceContainingBreak($iterator)) {
            return true;
        }
        if (true === $this->getOption('breakAfterCurlyBraceOfElse') &&
            $this->_isOpeningBraceAfterType(T_ELSE, $iterator) &&
            !$this->_isFollowedByWhitespaceContainingBreak($iterator)) {
            return true;
        }
        return false;
    }

    /**
     * @param \PHP\Manipulator\TokenContainer\Iterator $iterator
     */
    protected function _removeNextToken(Iterator $iterator)
    {
        $token = $iterator->current();
        $iterator->next();
        $this->_container->removeToken($iterator->current());
        $iterator->update($token);
    }

    /**
     * @param \PHP\Manipulator\TokenContainer\Iterator $iterator
     */
    protected function _removePreviousToken(Iterator $iterator)
    {
        $token = $iterator->current();
        $iterator->previous();
        $this->_container->removeToken($iterator->current());
        $iterator->update($token);
    }

    /**
     * @param \PHP\Manipulator\TokenContainer\Iterator $iterator
     * @return boolean
     */
    protected function _isFollowedByWrongWhitespace(Iterator $iterator)
    {
        $iterator->next();
        $newToken = $iterator->current();
        $iterator->previous();
        if ($this->isType($newToken, T_WHITESPACE) && $newToken->getValue() !== ' ') {
            return true;
        }
        return false;
    }

    /**
     * @param \PHP\Manipulator\TokenContainer\Iterator $iterator
     */
    protected function _addWhitespaceTokenAfter(Iterator $iterator)
    {
        $token = $iterator->current();
        $whitespaceToken = new Token(' ', null);
        $this->_container->insertTokenAfter($token, $whitespaceToken);
        $iterator->update($token);
    }

    /**
     * @param \PHP\Manipulator\TokenContainer\Iterator $iterator
     */
    protected function _addWhitespaceTokenBefore(Iterator $iterator)
    {
        $token = $iterator->current();
        $whitespaceToken = new Token(' ', null);
        $this->_container->insertTokenBefore($token, $whitespaceToken);
        $iterator->update($token);
    }

    /**
     * @param \PHP\Manipulator\TokenContainer\Iterator $iterator
     */
    protected function _setNextTokenValueToOneSpace(Iterator $iterator)
    {
        $token = $iterator->current();
        $iterator->next();
        $iterator->current()->setValue(' ');
        $iterator->update($token);
    }

    /**
     * @param \PHP\Manipulator\TokenContainer\Iterator $iterator
     */
    protected function _setPreviousTokenValueToOneSpace(Iterator $iterator)
    {
        $token = $iterator->current();
        $iterator->previous();
        $iterator->current()->setValue(' ');
        $iterator->update($token);
    }

    /**
     * @param \PHP\Manipulator\TokenContainer\Iterator $iterator
     */
    protected function _format(Iterator $iterator)
    {
        $token = $iterator->current();
        $type = ucfirst(strtolower(substr($token->getTokenName(),2)));

        if (!$this->isFollowedByTokenType($iterator, T_WHITESPACE) &&
            true === $this->getOption('spaceAfter' . $type)) {
            $this->_addWhitespaceTokenAfter($iterator);
        } else if ($this->_isFollowedByWrongWhitespace($iterator) &&
            true === $this->getOption('spaceAfter'. $type)) {
            $this->_setNextTokenValueToOneSpace($iterator);
        } else if (false === $this->getOption('spaceAfter' . $type) &&
            $this->isFollowedByTokenType($iterator, T_WHITESPACE)) {
            $this->_removeNextToken($iterator);
        }

        // If it is not an if, and should not break before else
        if (!$this->isType($token, T_IF) && false === $this->getOption('breakBeforeElse')) {
            if (!$this->isPrecededByTokenType($iterator, T_WHITESPACE) &&
                true === $this->getOption('spaceBefore' . $type)) {
                $this->_addWhitespaceTokenBefore($iterator);
            } else if ($this->_isPrecededByWrongWhitespace($iterator) &&
                true === $this->getOption('spaceBefore' . $type)) {
                $this->_setPreviousTokenValueToOneSpace($iterator);
            } else if (false === $this->getOption('spaceBefore' . $type) &&
                $this->isPrecededByTokenType($iterator, T_WHITESPACE) ) {
                $this->_removePreviousToken($iterator);
            }
        }
    }

    /**
     * @param \PHP\Manipulator\TokenContainer\Iterator $iterator
     * @return boolean
     */
    protected function _isPrecededByWrongWhitespace(Iterator $iterator)
    {
        $iterator->previous();
        $newToken = $iterator->current();
        $iterator->next();
        if ($this->isType($newToken, T_WHITESPACE) && $newToken->getValue() !== ' ') {
            return true;
        }
        return false;
    }

    /**
     * @param integer|null $type
     * @param \PHP\Manipulator\TokenContainer\Iterator $iterator
     * @return boolean
     */
    protected function _isOpeningBraceAfterType($type, Iterator $iterator)
    {
        $token = $iterator->current();
        if (!$this->isOpeningCurlyBrace($token)) {
            return false;
        }
        $breakTokens = array(T_CLASS, T_FUNCTION, T_IF, T_ELSE, T_ELSEIF);
        $filterCallback = function($tokentype) use ($type) {
                return ($tokentype === $type) ? false : true;
        };
        $breakTokens = array_filter($breakTokens, $filterCallback);
        $result = false;

        while ($iterator->valid()) {
            $iterator->previous();
            if ($iterator->valid() === false) {
                $result = false;
                break;
            }

            $current = $iterator->current();
            if ($this->isType($current, $breakTokens)) {
                $result = false;
                break;
            }
            if ($this->isType($current, $type)) {
                $result = true;
                break;
            }
            $iterator->previous();
        }
        $iterator->seekToToken($token);
        return $result;
    }
}