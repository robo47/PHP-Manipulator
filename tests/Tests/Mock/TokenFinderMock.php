<?php

namespace Tests\Mock;

use PHP\Manipulator\TokenFinder\Result;
use PHP\Manipulator\TokenFinder;
use PHP\Manipulator\TokenContainer;
use PHP\Manipulator\Token;

class TokenFinderMock
extends TokenFinder
{

    /**
     * @var \PHP\Manipulator\TokenFinder\Result
     */
    public $result = null;

    /**
     * @param \PHP\Manipulator\TokenFinder\Result $result
     */
    public function __construct(Result $result = null)
    {
        $this->result = $result;
    }

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
        return $this->result;
    }
}