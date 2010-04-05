<?php

class PHP_Manipulator_ContainerManipulator_SetWhitespaceBeforeToken
extends PHP_Manipulator_ContainerManipulator_SetWhitespaceAfterToken
{

    /**
     * @param PHP_Manipulator_TokenContainer $container
     * @param PHP_Manipulator_Token $token
     * @return PHP_Manipulator_Token
     */
    public function getTargetToken(PHP_Manipulator_TokenContainer $container, PHP_Manipulator_Token $token)
    {
        return $container->getPreviousToken($token);
    }

    /**
     * @param PHP_Manipulator_TokenContainer $container
     * @param PHP_Manipulator_Token $targetToken
     * @param PHP_Manipulator_Token $newToken
     */
    public function insertToken(PHP_Manipulator_TokenContainer $container, PHP_Manipulator_Token $targetToken, PHP_Manipulator_Token $newToken)
    {
        $container->insertTokenAfter($targetToken, $newToken);
    }

    /**
     * @param PHP_Manipulator_TokenContainer $container
     * @param PHP_Manipulator_Token $token
     * @param array $whitespace
     */
    public function setWhitespace(PHP_Manipulator_TokenContainer $container, PHP_Manipulator_Token $token, array $whitespace)
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
                $newToken = PHP_Manipulator_Token::factory(array(T_WHITESPACE, $tokenValue));
                $this->insertToken($container, $targetToken, $newToken);
            }
        }
    }
}