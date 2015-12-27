<?php

namespace Tests\Stub;

use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;
use PHP\Manipulator\TokenFinder;
use PHP\Manipulator\TokenFinder\Result;

class TokenFinderStub extends TokenFinder
{
    /**
     * @var Result
     */
    public $result = null;

    /**
     * @param Result $result
     */
    public function __construct(Result $result = null)
    {
        $this->result = $result;
    }

    /**
     * Finds tokens
     *
     * @param Token          $token
     * @param TokenContainer $container
     * @param mixed          $params
     *
     * @return Result
     */
    public function find(Token $token, TokenContainer $container, $params = null)
    {
        return $this->result;
    }
}
