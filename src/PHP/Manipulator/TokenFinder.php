<?php

namespace PHP\Manipulator;

use PHP\Manipulator\TokenFinder\Result;

abstract class TokenFinder extends AHelper
{
    /**
     * Finds tokens
     *
     * @param Token          $token
     * @param TokenContainer $container
     * @param mixed          $params
     *
     * @return Result
     */
    abstract public function find(Token $token, TokenContainer $container, $params = null);
}
