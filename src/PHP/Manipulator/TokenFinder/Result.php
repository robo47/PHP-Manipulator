<?php

namespace PHP\Manipulator\TokenFinder;

use Countable;
use PHP\Manipulator\Exception\ResultException;
use PHP\Manipulator\Token;

class Result implements Countable
{
    /**
     * @var Token[]
     */
    private $tokens = [];

    /**
     * @param Token $token
     *
     * @return Result
     */
    public function addToken(Token $token)
    {
        $this->tokens[] = $token;

        return $this;
    }

    /**
     * @return Token[]
     */
    public function getTokens()
    {
        return $this->tokens;
    }

    /**
     * @throws ResultException
     *
     * @return Token
     */
    public function getFirstToken()
    {
        if (!$this->isEmpty()) {
            reset($this->tokens);

            return current($this->tokens);
        }
        throw new ResultException('Result is Empty', ResultException::EMPTY_RESULT);
    }

    /**
     * @throws ResultException
     *
     * @return Token
     */
    public function getLastToken()
    {
        if (!$this->isEmpty()) {
            end($this->tokens);

            return current($this->tokens);
        }
        throw new ResultException('Result is Empty', ResultException::EMPTY_RESULT);
    }

    /**
     * @return bool
     */
    public function isEmpty()
    {
        return (count($this->tokens) === 0);
    }

    /**
     * @return Result
     */
    public function clean()
    {
        $this->tokens = [];

        return $this;
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->tokens);
    }

    /**
     * @param Token[] $tokens
     *
     * @return Result
     */
    public static function factory(array $tokens = [])
    {
        $result = new self();

        foreach ($tokens as $token) {
            $result->addToken($token);
        }

        return $result;
    }
}
