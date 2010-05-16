<?php

namespace PHP\Manipulator\Action;

use PHP\Manipulator\Action;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;
use PHP\Manipulator\TokenContainer\Iterator;

// @todo support break directly before and after elseif/else (brace on the next line) ...
// @todo spaces inside if/elseif's expression if ( $foo == $baa ) or if ($foo == $baa)
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

        //        if (!$this->hasOption('breakBeforeCurlyBraceOfElse')) {
        //            $this->setOption('breakBeforeCurlyBraceOfElse', true);
        //        }
        //        if (!$this->hasOption('breakBeforeCurlyBraceOfElseif')) {
        //            $this->setOption('breakBeforeCurlyBraceOfElseif', true);
        //        }
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
        $this->_ifStack = new \SplStack();
        $this->_elseStack = new \SplStack();
        $this->_elseifStack = new \SplStack();
        $this->_level = 0;

        while ($iterator->valid()) {
            $token = $iterator->current();

            if ($this->isType($token, (T_IF))) {
                $this->_ifStack->push($this->_level);
                $this->_format($iterator);
            }
            if ($this->isType($token, (T_ELSEIF))) {
                $this->_elseifStack->push($this->_level);
                $this->_format($iterator);
            }
            if ($this->isType($token, (T_ELSE))) {
                $this->_elseStack->push($this->_level);
                $this->_format($iterator);
            }
            if ($this->isOpeningCurlyBrace($token)) {
                $this->_level++;
                $this->_applyBreaksAfterCurlyBraces($iterator);
            }

            if ($this->isClosingCurlyBrace($token)) {
                $this->_level--;
                if (!$this->isPrecededByTokenType($iterator, T_WHITESPACE)) {
                    if ($this->_shouldInsertBreakBeforeCurrentCurlyBrace()) {
                        $newToken = new Token("\n", T_WHITESPACE);
                        $this->_container->insertTokenBefore($token, $newToken);
                    }
                }

                //                if (true === $this->getOption('breakBeforeCurlyBraceOfElse') && $this->_isFollowedByElseOrElseIf($token, $iterator)) {
                //                    $this->_addLineBreakBeforeCurlyBraceIfNotPresent($token, $iterator);
                //                }

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

    //    /**
    //     * @param \PHP\Manipulator\Token $token
    //     * @param \PHP\Manipulator\TokenContainer\Iterator $iterator
    //     */
    //    protected function _addLineBreakBeforeCurlyBraceIfNotPresent(Token $token, Iterator $iterator)
    //    {
    //        $iterator->previous();
    //        if (!$this->isType($token, T_WHITESPACE) || $this->evaluateConstraint('ContainsNewline', $token)) {
    //            $whitespaceToken = new Token("\n", T_WHITESPACE);
    //            $this->_container->insertTokenBefore($token, $whitespaceToken);
    //        }
    //        $iterator->update($token);
    //    }

    //    /**
    //     * @param \PHP\Manipulator\Token $token
    //     * @param \PHP\Manipulator\TokenContainer\Iterator $iterator
    //     * @return boolean
    //     */
    //    protected function _isFollowedByElseOrElseIf(Token $token, Iterator $iterator)
    //    {
    //        $result = false;
    //        $iterator->next();
    //        while ($iterator->valid()) {
    //
    //            $current = $iterator->current();
    //            if($this->isType($current, array(T_ELSE, T_ELSEIF))) {
    //                $result = true;
    //                break;
    //            }
    //            if(!$this->isType($current, array(T_WHITESPACE, T_COMMENT))) {
    //                $result = false;
    //                break;
    //            }
    //            $iterator->next();
    //        }
    //        $iterator->update($token);
    //        return $result;
    //    }

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
        $type = ucfirst($token->getValue());

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

        if (!$this->isType($token, T_IF)) {
            if (!$this->isPrecededByTokenType($iterator, T_WHITESPACE) &&
                true === $this->getOption('spaceBefore' . $type)) {
                $this->_addWhitespaceTokenBefore($iterator);
            } else if ($this->_isPrecededByWrongWhitespace($iterator) &&
                true === $this->getOption('spaceBefore' . $type)) {
                $this->_setPreviousTokenValueToOneSpace($iterator);
            } else if (false === $this->getOption('spaceBefore' . $type) &&
                $this->isPrecededByTokenType($iterator, T_WHITESPACE)) {
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