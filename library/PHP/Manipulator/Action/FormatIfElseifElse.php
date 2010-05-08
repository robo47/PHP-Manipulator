<?php

namespace PHP\Manipulator\Action;

use PHP\Manipulator\Action;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;
use PHP\Manipulator\TokenContainer\Iterator;

// @todo support break directly before and after elseif/else (brace on the next line) ...
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
     * @param PHP\Manipulator\TokenContainer $container
     */
    public function run(TokenContainer $container, $params = null)
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
                $this->_format($token, $iterator);
            }
            if ($this->isType($token, (T_ELSEIF))) {
                $this->_elseifStack->push($this->_level);
                $this->_format($token, $iterator);
            }
            if ($this->isType($token, (T_ELSE))) {
                $this->_elseStack->push($this->_level);
                $this->_format($token, $iterator);
            }
            if ($this->isOpeningCurlyBrace($token)) {
                $this->_level++;
                $this->_applyBreaksAfterCurlyBraces($token, $iterator);
            }

            if ($this->isClosingCurlyBrace($token)) {
                $this->_level--;
                if(!$this->_isPrecededByWhitespace($token, $iterator)) {
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
//     * @param Token $token
//     * @param Iterator $iterator
//     */
//    protected function _addLineBreakBeforeCurlyBraceIfNotPresent(Token $token, Iterator $iterator)
//    {
//        $iterator->previous();
//        if (!$this->isType($token, T_WHITESPACE) || $this->evaluateConstraint('ContainsNewline', $token)) {
//            $whitespaceToken = new Token("\n", T_WHITESPACE);
//            $this->_container->insertTokenBefore($token, $whitespaceToken);
//        }
//        $iterator->reInit($token);
//    }

//    /**
//     * @param Token $token
//     * @param Iterator $iterator
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
//        $iterator->reInit($token);
//        return $result;
//    }

    /**
     * @return boolean
     */
    protected function _shouldInsertBreakBeforeCurrentCurlyBrace()
    {
        if (true === $this->getOption('breakAfterCurlyBraceOfIf') && $this->_stackHasLevelMatchingItem($this->_ifStack)) {
            return true;
        }
        if (true === $this->getOption('breakAfterCurlyBraceOfElseif') && $this->_stackHasLevelMatchingItem($this->_elseifStack)) {
            return true;
        }
        if (true === $this->getOption('breakAfterCurlyBraceOfElse') && $this->_stackHasLevelMatchingItem($this->_elseStack)) {
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
     * @param Token $token
     * @param Iterator $iterator
     */
    protected function _applyBreaksAfterCurlyBraces(Token $token, Iterator $iterator)
    {
        if ($this->_shouldInsertBreakAfterCurrentOpeningCurlyBrace($token, $iterator)) {
            $newToken = new Token("\n", T_WHITESPACE);
            $this->_container->insertTokenAfter($token, $newToken);
            $iterator->reInit();
            $iterator->seekToToken($token);
        }
    }

    /**
     * @param Token $token
     * @param Iterator $iterator
     * @return boolean
     */
    protected function _shouldInsertBreakAfterCurrentOpeningCurlyBrace(Token $token, Iterator $iterator)
    {
        // @todo check if there is already a break ?!?!
        if (true === $this->getOption('breakAfterCurlyBraceOfIf') &&
            $this->_isOpeningBraceAfterType($token, T_IF, $iterator)) {
            return true;
        }
        if (true === $this->getOption('breakAfterCurlyBraceOfElseif') &&
            $this->_isOpeningBraceAfterType($token, T_ELSEIF, $iterator)) {
            return true;
        }
        if (true === $this->getOption('breakAfterCurlyBraceOfElse') &&
            $this->_isOpeningBraceAfterType($token, T_ELSE, $iterator)) {
            return true;
        }
        return false;
    }

    /**
     * @param Token $token
     * @param Iterator $iterator
     */
    protected function _removeNextToken(Token $token, Iterator $iterator)
    {
        $iterator->next();
        $this->_container->removeToken($iterator->current());
        $iterator->reInit($token);
    }

    /**
     * @param Token $token
     * @param Iterator $iterator
     */
    protected function _removePreviousToken(Token $token, Iterator $iterator)
    {
        $iterator->previous();
        $this->_container->removeToken($iterator->current());
        $iterator->reInit($token);
    }

    /**
     * @param Token $token
     * @param Iterator $iterator
     * @return boolean
     */
    protected function _isFollowedByWrongWhitespace(Token $token, Iterator $iterator)
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
     * @param Token $token
     * @param Iterator $iterator
     */
    protected function _addWhitespaceTokenAfter(Token $token, Iterator $iterator)
    {
        $whitespaceToken = new Token(' ', null);
        $this->_container->insertTokenAfter($token, $whitespaceToken);
        $iterator->reInit($token);
    }

    /**
     * @param Token $token
     * @param Iterator $iterator
     */
    protected function _addWhitespaceTokenBefore(Token $token, Iterator $iterator)
    {
        $whitespaceToken = new Token(' ', null);
        $this->_container->insertTokenBefore($token, $whitespaceToken);
        $iterator->reInit($token);
    }

    /**
     * @param Token $token
     * @param Iterator $iterator
     */
    protected function _setNextTokenValueToOneSpace(Token $token, Iterator $iterator)
    {
        $iterator->next();
        $iterator->current()->setValue(' ');
        $iterator->reInit($token);
    }

    /**
     * @param Token $token
     * @param Iterator $iterator
     */
    protected function _setPreviousTokenValueToOneSpace(Token $token, Iterator $iterator)
    {
        $iterator->previous();
        $iterator->current()->setValue(' ');
        $iterator->reInit($token);
    }

    /**
     * @param Token $token
     * @param Iterator $iterator
     */
    protected function _format(Token $token, Iterator $iterator)
    {
        $type = ucfirst($token->getValue());

        if (!$this->_isFollowedByWhitespace($token, $iterator) &&
            true === $this->getOption('spaceAfter' . $type)) {
            $this->_addWhitespaceTokenAfter($token, $iterator);
        } elseif ($this->_isFollowedByWrongWhitespace($token, $iterator) &&
            true === $this->getOption('spaceAfter'. $type)) {
            $this->_setNextTokenValueToOneSpace($token, $iterator);
        } elseif (false === $this->getOption('spaceAfter' . $type) &&
            $this->_isFollowedByWhitespace($token, $iterator)) {
            $this->_removeNextToken($token, $iterator);
        }

        if (T_IF !== $token->getType()) {
            if (!$this->_isPrecededByWhitespace($token, $iterator) &&
                true === $this->getOption('spaceBefore' . $type)) {
                $this->_addWhitespaceTokenBefore($token, $iterator);
            } elseif ($this->_isPrecededByWrongWhitespace($token, $iterator) &&
                true === $this->getOption('spaceBefore' . $type)) {
                $this->_setPreviousTokenValueToOneSpace($token, $iterator);
            } elseif (false === $this->getOption('spaceBefore' . $type) &&
                $this->_isPrecededByWhitespace($token, $iterator)) {
                $this->_removePreviousToken($token, $iterator);
            }
        }
    }

    /**
     * @param Token $token
     * @param Iterator $iterator
     * @return boolean
     */
    protected function _isFollowedByWhitespace(Token $token, Iterator $iterator)
    {
        // @todo Use isFollowedBy because of possible whitespace and stuff
        $iterator->next();
        $newToken = $iterator->current();
        $iterator->previous();
        if ($this->isType($newToken, T_WHITESPACE)) {
            return true;
        }
        return false;
    }

    /**
     * @param Token $token
     * @param Iterator $iterator
     * @return boolean
     */
    protected function _isPrecededByWhitespace(Token $token, Iterator $iterator)
    {
        // @todo Use isPrecededBy because of possible whitespace and stuff
        $iterator->previous();
        $newToken = $iterator->current();
        $iterator->next();
        if ($this->isType($newToken, T_WHITESPACE)) {
            return true;
        }
        return false;
    }

    /**
     * @param Token $token
     * @param Iterator $iterator
     * @return boolean
     */
    protected function _isPrecededByWrongWhitespace(Token $token, Iterator $iterator)
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
     * @param Token $token
     * @param integer|null $type
     * @param Iterator $iterator
     * @return boolean
     */
    protected function _isOpeningBraceAfterType(Token $token, $type, Iterator $iterator)
    {
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
            $current = $iterator->current();
            if($this->isType($current, $breakTokens)) {
                $result = false;
                break;
            }
            if($this->isType($current, $type)) {
                $result = true;
                break;
            }
            $iterator->previous();
        }
        $iterator->seekToToken($token);
        return $result;
    }
}