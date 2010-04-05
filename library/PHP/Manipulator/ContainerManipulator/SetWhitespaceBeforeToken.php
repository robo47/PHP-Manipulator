<?php

namespace PHP\Manipulator\ContainerManipulator;

use PHP\Manipulator\ContainerManipulator;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;

class SetWhitespaceBeforeToken
extends SetWhitespaceAfterToken
{

    /**
     * @param PHP\Manipulator\TokenContainer $container
     * @param PHP\Manipulator\Token $token
     * @return PHP\Manipulator\Token
     */
    public function getTargetToken(TokenContainer $container, Token $token)
    {
        return $container->getPreviousToken($token);
    }

    /**
     * @param PHP\Manipulator\TokenContainer $container
     * @param PHP\Manipulator\Token $targetToken
     * @param PHP\Manipulator\Token $newToken
     */
    public function insertToken(TokenContainer $container, Token $targetToken, Token $newToken)
    {
        $container->insertTokenAfter($targetToken, $newToken);
    }

    /**
     * @param PHP\Manipulator\TokenContainer $container
     * @param PHP\Manipulator\Token $token
     * @param array $whitespace
     */
    public function setWhitespace(TokenContainer $container, Token $token, array $whitespace)
    {
        $targetToken = $this->getTargetToken($container, $token);

        $tokenValue = $this->getWhitespaceForToken($token, $whitespace);

        if (null !== $targetToken && $this->evaluateConstraint('IsType', $targetToken, T_WHITESPACE)) {
            if (empty($tokenValue)) {
                $container->removeToken($targetToken);
            } else {
                $targetToken->setValue($tokenValue);
            }
        }
        if (null !== $targetToken && !$this->evaluateConstraint('IsType', $targetToken, T_WHITESPACE)) {
            if (!empty($tokenValue)) {
                $newToken = Token::factory(array(T_WHITESPACE, $tokenValue));
                $this->insertToken($container, $targetToken, $newToken);
            }
        }
    }
}