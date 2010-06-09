<?php

namespace PHP\Manipulator\TokenFinder;

use PHP\Manipulator\TokenFinder\Result;
use PHP\Manipulator\TokenFinder;
use PHP\Manipulator\TokenContainer;
use PHP\Manipulator\TokenContainer\Iterator;
use PHP\Manipulator\Token;

class FunctionFinder
extends TokenFinder
{

    /**
     * @var boolean
     */
    protected $_inside = false;

    /**
     * @var integer
     */
    protected $_level = 0;

    /**
     * @var boolean
     */
    protected $_end = false;

    /**
     * Finds tokens
     *
     * @param \PHP\Manipulator\Token $token
     * @param \PHP\Manipulator\TokenContainer $container
     * @param mixed $params
     * @return \PHP\Manipulator\TokenFinder\Result
     */
    public function find(Token $token, TokenContainer $container, $params = null)
    {
        if (!$this->isType($token, T_FUNCTION)) {
            throw new \Exception('Start-token is not T_FUNCTION: ' . $token->getTokenName());
        }

        $iterator = $container->getIterator();
        $iterator->seekToToken($token);

        if ($this->_includeMethodProperties($params) &&
            !$this->_includePhpDoc($params)) {
            $this->_seekToMethodProperties($iterator);
        }

        if ($this->_includePhpDoc($params)) {
            $this->_seekToPhpdoc($iterator, $params);
        }
        $this->_inside = false;
        $this->_level = 0;
        $this->_end = false;

        $result = new Result();
        while ($iterator->valid() && false === $this->_end) {
            $result->addToken($iterator->current());

            $this->_checkLevel($iterator);
            $this->_checkBreak($iterator);

            $iterator->next();
        }
        return $result;
    }

    /**
     * @param \PHP\Manipulator\TokenContainer\Iterator $iterator
     */
    protected function _checkBreak(Iterator $iterator)
    {
        $token = $iterator->current();
        // abstract methods or interface-methods
        if (false === $this->_inside && $this->isSemicolon($token)) {
            $this->_end = true;
        }
        // last curly-brace closed
        if (true === $this->_inside && $this->_level === 0) {
            $this->_end = true;
        }
    }

    /**
     * @param \PHP\Manipulator\TokenContainer\Iterator $iterator
     */
    protected function _checkLevel(Iterator $iterator)
    {
        $token = $iterator->current();
        if ($this->isOpeningCurlyBrace( $token)) {
            $this->_inside = true;
            $this->_level++;
        }

        if ($this->isClosingCurlyBrace( $token)) {
            $this->_level--;
        }
    }

    /**
     * @param \PHP\Manipulator\TokenContainer\Iterator $iterator
     */
    protected function _seekToMethodProperties(Iterator $iterator)
    {
        $token = $iterator->current();
        $iterator->previous();
        while ($iterator->valid()) {
            if (!$this->isType($iterator->current(), array(T_WHITESPACE, T_PUBLIC, T_COMMENT, T_DOC_COMMENT, T_PUBLIC, T_PROTECTED, T_PRIVATE, T_STATIC))) {
                $iterator->next();
                while ($iterator->valid()) {
                    if (!$this->isType($iterator->current(), array(T_PUBLIC, T_PROTECTED, T_PRIVATE, T_STATIC, T_FUNCTION))) {
                        $iterator->next();
                    } else {
                        break;
                    }
                }
                break;
            }
            $iterator->previous();
        }
        // didn't find anything
        if (!$iterator->valid()) {
            $iterator->seekToToken($token);
        }
    }

    /**
     * @param \PHP\Manipulator\TokenContainer\Iterator $iterator
     */
    protected function _seekToPhpdoc(Iterator $iterator)
    {
        $token = $iterator->current();
        // travel reverse as long as there is only whitespace and stuff
        $iterator->previous();
        while ($iterator->valid()) {
            if (!$this->isType($iterator->current(), array(T_WHITESPACE, T_PUBLIC, T_COMMENT, T_DOC_COMMENT, T_PUBLIC, T_PROTECTED, T_PRIVATE, T_STATIC))) {
                $iterator->next();
                while ($iterator->valid()) {
                    if (!$this->isType($iterator->current(), array(T_DOC_COMMENT, T_PUBLIC, T_PROTECTED, T_PRIVATE, T_STATIC, T_FUNCTION))) {
                        $iterator->next();
                    } else {
                        break;
                    }
                }
                break;
            }
            $iterator->previous();
        }
        // didn't find anything
        if (!$iterator->valid()) {
            $iterator->seekToToken($token);
        }
    }

    /**
     * @param array|null $params
     * @return boolean
     */
    protected function _includePhpDoc($params)
    {
        if (is_array($params) && isset($params['includePhpdoc'])) {
            return (bool) $params['includePhpdoc'];
        } else {
            return false;
        }
    }

    /**
     * wheter to check for public/protected/private
     *
     * @param array|null $params
     * @return boolean
     */
    protected function _includeMethodProperties($params)
    {
        if (is_array($params) && isset($params['includeMethodProperties'])) {
            return (bool) $params['includeMethodProperties'];
        } else {
            return false;
        }
    }
}