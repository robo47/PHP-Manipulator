<?php

class PHP_Formatter_ContainerManipulator_SetWhitespaceBeforeToken
extends PHP_Formatter_ContainerManipulator_SetWhitespaceAfterToken
{

    /**
     * @param PHP_Formatter_TokenContainer $container
     * @param PHP_Formatter_Token $token
     * @return PHP_Formatter_Token
     */
    public function getTargetToken(PHP_Formatter_TokenContainer $container, PHP_Formatter_Token $token)
    {
        return $container->getPreviousToken($token);
    }

    /**
     * @param PHP_Formatter_TokenContainer $container
     * @param PHP_Formatter_Token $targetToken
     * @param PHP_Formatter_Token $newToken
     */
    public function insertToken(PHP_Formatter_TokenContainer $container, PHP_Formatter_Token $targetToken, PHP_Formatter_Token $newToken)
    {
        $container->insertTokenAfter($targetToken, $newToken);
    }

    /**
     * @param PHP_Formatter_TokenContainer $container
     * @param PHP_Formatter_Token $token
     * @param array $whitespace
     */
    public function setWhitespace(PHP_Formatter_TokenContainer $container, PHP_Formatter_Token $token, array $whitespace)
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
                $newToken = PHP_Formatter_Token::factory(array(T_WHITESPACE, $tokenValue));
                $this->insertToken($container, $targetToken, $newToken);
            }
        }
    }
}