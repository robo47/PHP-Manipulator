<?php

namespace PHP\Manipulator\Action;

use PHP\Manipulator\Action;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;

// @todo make configureable via options instead params ? or ... move into ContainerManipulators or something like that
class SetWhitespaceBeforeToken
extends SetWhitespaceAfterToken
{

    /**
     * @param \PHP\Manipulator\TokenContainer $container
     * @param \PHP\Manipulator\Token $token
     * @return \PHP\Manipulator\Token
     */
    public function getTargetToken(TokenContainer $container, Token $token)
    {
        return $container->getPreviousToken($token);
    }

    /**
     * @param \PHP\Manipulator\TokenContainer $container
     * @param \PHP\Manipulator\Token $targetToken
     * @param \PHP\Manipulator\Token $newToken
     */
    public function insertToken(TokenContainer $container, Token $targetToken, Token $newToken)
    {
        $container->insertTokenAfter($targetToken, $newToken);
    }
}