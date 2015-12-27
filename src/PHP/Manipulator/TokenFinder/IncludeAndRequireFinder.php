<?php

namespace PHP\Manipulator\TokenFinder;

use PHP\Manipulator\Exception\TokenFinderException;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;
use PHP\Manipulator\TokenFinder;

/**
 * @todo needs Better name, propably it is possible to create a super-class which allows to find complete statements
 *          ?
 */
class IncludeAndRequireFinder extends TokenFinder
{
    /**
     * @param Token          $token
     * @param TokenContainer $container
     * @param mixed          $params
     *
     * @return Result
     */
    public function find(Token $token, TokenContainer $container, $params = null)
    {
        $allowedTokens = [T_INCLUDE, T_INCLUDE_ONCE, T_REQUIRE, T_REQUIRE_ONCE];
        if (!$token->isType($allowedTokens)) {
            $message = 'Start-token is not one of T_INCLUDE, T_INCLUDE_ONCE, T_REQUIRE, T_REQUIRE_ONCE';
            throw new TokenFinderException($message, TokenFinderException::UNSUPPORTED_START_TOKEN);
        }
        $result   = new Result();
        $iterator = $container->getIterator();
        $iterator->seekToToken($token);

        while ($iterator->valid()) {
            $token = $iterator->current();
            $result->addToken($token);
            if ($token->isSemicolon()) {
                break;
            }
            $iterator->next();
        }

        return $result;
    }
}
