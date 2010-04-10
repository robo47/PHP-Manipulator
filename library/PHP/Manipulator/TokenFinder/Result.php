<?php

namespace PHP\Manipulator\TokenFinder;

use PHP\Manipulator\Token;

class Result
implements \Countable
{
    protected $_tokens = array();

    /**
     *
     * @param Token $token
     * @return \PHP\Manipulator\TokenFinder\Result *Provides Fluent Interface*
     */
    public function addToken(Token $token)
    {
        $this->_tokens[] = $token;
        return $this;
    }

    /**
     *
     * @return array
     */
    public function getTokens()
    {
        return $this->_tokens;
    }

    /**
     *
     * @return PHP\Manipulator\Token
     */
    public function getFirstToken()
    {
        if (!$this->isEmpty()) {
            reset($this->_tokens);
            return current($this->_tokens);
        } else {
            throw new \Exception('Result is Empty');
        }
    }

    /**
     *
     * @return PHP\Manipulator\Token
     * @throws Exception if result is empty
     */
    public function getLastToken()
    {
        if (!$this->isEmpty()) {
            end($this->_tokens);
            return current($this->_tokens);
        } else {
            throw new \Exception('Result is Empty');
        }
    }

    /**
     *
     * @return boolean
     */
    public function isEmpty()
    {
        return (count($this->_tokens) == 0);
    }

    /**
     *
     * @return integer
     */
    public function count()
    {
        return count($this->_tokens);
    }

    /**
     *
     * @param array $tokens
     * @return \PHP\Manipulator\TokenFinder\Result
     */
    public static function factory(array $tokens = array())
    {
        $result = new Result();

        foreach($tokens as $token) {
            $result->addToken($token);
        }
        return $result;
    }
}