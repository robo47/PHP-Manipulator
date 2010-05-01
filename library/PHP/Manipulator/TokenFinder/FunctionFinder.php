<?php

namespace PHP\Manipulator\TokenFinder;

use PHP\Manipulator\TokenFinder\Result;
use PHP\Manipulator\TokenFinder;
use PHP\Manipulator\TokenContainer;
use PHP\Manipulator\Token;

// @todo refactor find into some more functions
// @todo test with anon-functions
// @todo instead of $params use $options
class FunctionFinder
extends TokenFinder
{

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

        $pos = $container->getOffsetByToken($token);
        $iterator = $container->getIterator();
        $iterator->seek($pos);

        if ($this->_includeMethodProperties($params) && !$this->_includePhpDoc($params)) {
            // travel reverse as long as there is only whitespace and stuff
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
                $iterator->seek($pos);
            }
        }

        if ($this->_includePhpDoc($params)) {
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
                $iterator->seek($pos);
            }
        }

        $result = new Result();
        $inside = false;
        $level = 0;
        while ($iterator->valid()) {
            $token = $iterator->current();
            $result->addToken($token);

            if ($this->isOpeningCurlyBrace( $token)) {
                $inside = true;
                $level++;
            }

            if ($this->isClosingCurlyBrace( $token)) {
                $level--;
            }

            // abstract methods or interface-methods
            if (false === $inside && $this->isSemicolon( $token)) {
                break;
            }

            // last curly-brace closed
            if (true === $inside && $level === 0) {
                break;
            }

            $iterator->next();
        }
        return $result;
    }

    /**
     *
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