<?php

namespace PHP\Manipulator\Action;

use PHP\Manipulator\Action;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;
use PHP\Manipulator\TokenContainer\Iterator;

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

        if (!$this->hasOption('breakAfterIf')) {
            $this->setOption('breakAfterIf', true);
        }
        if (!$this->hasOption('breakAfterElse')) {
            $this->setOption('breakAfterElse', true);
        }
        if (!$this->hasOption('breakAfterElseif')) {
            $this->setOption('breakAfterElseif', true);
        }
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
                $this->_formatIf($token, $iterator);
            }
            if ($this->isType($token, (T_ELSEIF))) {
                $this->_elseifStack->push($this->_level);
                $this->_formatElseIf($token, $iterator);
            }
            if ($this->isType($token, (T_ELSE))) {
                $this->_elseStack->push($this->_level);
                $this->_formatElse($token, $iterator);
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
        //var_dump($this->_ifStack->isEmpty(), $this->_elseStack->isEmpty(), $this->_elseifStack->isEmpty());
        $container->retokenize();
    }

    protected function _shouldInsertBreakBeforeCurrentCurlyBrace()
    {
        if (true === $this->getOption('breakAfterIf') && $this->_stackHasLevelMatchingItem($this->_ifStack)) {
            return true;
        }
        if (true === $this->getOption('breakAfterElseif') && $this->_stackHasLevelMatchingItem($this->_elseifStack)) {
            return true;
        }
        if (true === $this->getOption('breakAfterElse') && $this->_stackHasLevelMatchingItem($this->_elseStack)) {
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
        if (true === $this->getOption('breakAfterIf') &&
            $this->_isOpeningBraceAfterType($token, T_IF, $iterator)) {
            return true;
        }
        if (true === $this->getOption('breakAfterElseif') &&
            $this->_isOpeningBraceAfterType($token, T_ELSEIF, $iterator)) {
            return true;
        }
        if (true === $this->getOption('breakAfterElse') &&
            $this->_isOpeningBraceAfterType($token, T_ELSE, $iterator)) {
            return true;
        }
        return false;
    }

    /**
     * @param Token $token
     * @param \PHP\Formatter\TokenContainer\Iterator $iterator
     */
    protected function _formatIf(Token $token, Iterator $iterator)
    {
        if (!$this->_isFollowedByWhitespace($token, $iterator) &&
            true === $this->getOption('spaceAfterIf')) {
            $whitespaceToken = new Token(' ', null);
            $this->_container->insertTokenAfter($token, $whitespaceToken);
            $iterator->reInit();
            $iterator->seekToToken($token);
        } elseif ($this->_isFollowedByWrongWhitespace($token, $iterator) &&
            true === $this->getOption('spaceAfterIf')) {
            $iterator->next();
            $whitespaceToken = $iterator->current();
            $whitespaceToken->setValue(' ');
        } elseif (false === $this->getOption('spaceAfterIf') &&
            $this->_isFollowedByWhitespace($token, $iterator)) {
            $iterator->next();
            $this->_container->removeToken($iterator->current());
            $iterator->reInit();
            $iterator->seekToToken($token);
        }
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
     * @param \PHP\Formatter\TokenContainer\Iterator $iterator
     */
    protected function _formatElseIf(Token $token, Iterator $iterator)
    {
        if (!$this->_isFollowedByWhitespace($token, $iterator) &&
            true === $this->getOption('spaceAfterElseif')) {
            $whitespaceToken = new Token(' ', null);
            $this->_container->insertTokenAfter($token, $whitespaceToken);
            $iterator->reInit();
            $iterator->seekToToken($token);
        } elseif ($this->_isFollowedByWrongWhitespace($token, $iterator) &&
            true === $this->getOption('spaceAfterElseif')) {
            $iterator->next();
            $whitespaceToken = $iterator->current();
            $whitespaceToken->setValue(' ');
        } elseif (false === $this->getOption('spaceAfterElseif') &&
            $this->_isFollowedByWhitespace($token, $iterator)) {
            $iterator->next();
            $this->_container->removeToken($iterator->current());
            $iterator->reInit();
        }
        $iterator->seekToToken($token);

        if (!$this->_isPrecededByWhitespace($token, $iterator) &&
            true === $this->getOption('spaceBeforeElseif')) {
            $whitespaceToken = new Token(' ', null);
            $this->_container->insertTokenBefore($token, $whitespaceToken);
            $iterator->reInit();
            $iterator->seekToToken($token);
        }if
        ($this->_isPrecededByWrongWhitespace($token, $iterator) &&
            true === $this->getOption('spaceBeforeElseif')) {
            $iterator->previous();
            $whitespaceToken = $iterator->current();
            $whitespaceToken->setValue(' ');
        } elseif (false === $this->getOption('spaceBeforeElseif') &&
            $this->_isPrecededByWhitespace($token, $iterator)) {

            $iterator->previous();
            $this->_container->removeToken($iterator->current());
            $iterator->reInit();
            $iterator->seekToToken($token);
        }
        $iterator->seekToToken($token);
    }

    /**
     * @param Token $token
     * @param \PHP\Formatter\TokenContainer\Iterator $iterator
     */
    protected function _formatElse(Token $token, Iterator $iterator)
    {
        if (!$this->_isFollowedByWhitespace($token, $iterator) &&
            true === $this->getOption('spaceAfterElse')) {
            $whitespaceToken = new Token(' ', null);
            $this->_container->insertTokenAfter($token, $whitespaceToken);
            $iterator->reInit();
            $iterator->seekToToken($token);
        } elseif ($this->_isFollowedByWrongWhitespace($token, $iterator) &&
            true === $this->getOption('spaceAfterElse')) {
            $iterator->next();
            $whitespaceToken = $iterator->current();
            $whitespaceToken->setValue(' ');
        } elseif (false === $this->getOption('spaceAfterElse') &&
            $this->_isFollowedByWhitespace($token, $iterator)) {
            $iterator->next();
            $this->_container->removeToken($iterator->current());
            $iterator->reInit();
        }
        $iterator->seekToToken($token);

        if (!$this->_isPrecededByWhitespace($token, $iterator) &&
            true === $this->getOption('spaceBeforeElse')) {
            $whitespaceToken = new Token(' ', null);
            $this->_container->insertTokenBefore($token, $whitespaceToken);
            $iterator->reInit();
            $iterator->seekToToken($token);
        }if
        ($this->_isPrecededByWrongWhitespace($token, $iterator) &&
            true === $this->getOption('spaceBeforeElse')) {
            $iterator->previous();
            $whitespaceToken = $iterator->current();
            $whitespaceToken->setValue(' ');
        } elseif (false === $this->getOption('spaceBeforeElse') &&
            $this->_isPrecededByWhitespace($token, $iterator)) {
            $iterator->previous();
            $this->_container->removeToken($iterator->current());
            $iterator->reInit();
            $iterator->seekToToken($token);
        }
        $iterator->seekToToken($token);
    }

    /**
     * @param Token $token
     * @param Iterator $iterator
     * @return boolean
     */
    protected function _isFollowedByWhitespace(Token $token, Iterator $iterator)
    {
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
        $compareCallback = function($tokentype) use ($type) {
            return ($tokentype === $type) ? false : true;
        };
        $breakTokens = array_filter($breakTokens, $compareCallback);
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