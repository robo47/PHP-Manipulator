<?php

namespace PHP\Manipulator\TokenFinder;

use PHP\Manipulator\TokenFinder\Result;
use PHP\Manipulator\TokenFinder;
use PHP\Manipulator\TokenContainer;
use PHP\Manipulator\Token;

// @todo needs Better name, propably it is possible to create a super-class which allows to find complete statements ?
class IncludeAndRequire
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
        if (!$this->isType($token, array(T_INCLUDE, T_INCLUDE_ONCE, T_REQUIRE, T_REQUIRE_ONCE))) {
            throw new \Exception('Start-token is not one of T_INCLUDE, T_INCLUDE_ONCE, T_REQUIRE, T_REQUIRE_ONCE');
        }
        $result = new Result();
        $iterator = $container->getIterator();
        $iterator->seekToToken($token);

        while ($iterator->valid()) {
            $token = $iterator->current();
            $result->addToken($token);
            if ($this->isSemicolon( $token)) {
                break;
            }
            $iterator->next();
        }
        return $result;
    }
}