<?php

class PHP_Manipulator_ContainerManipulator_SetWhitespaceAfterToken
extends PHP_Manipulator_ContainerManipulator_Abstract
{

    /**
     * @param PHP_Manipulator_TokenContainer $container
     * @param mixed $params
     */
    public function manipulate(PHP_Manipulator_TokenContainer $container, $params = null)
    {
        if (!is_array($params)) {
            $message = 'invalid input $params should be an array';
            throw new PHP_Manipulator_Exception($message);
        }
        if (!isset($params['tokens'])) {
            $message = "key 'tokens' not found in \$params";
            throw new PHP_Manipulator_Exception($message);
        }
        if (!isset($params['whitespace'])) {
            $message = "key 'whitespace' not found in \$params";
            throw new PHP_Manipulator_Exception($message);
        }

        $tokens = $params['tokens'];
        $whitespace = $params['whitespace'];
        foreach ($tokens as $token) {
            $this->setWhitespace($container, $token, $whitespace);
        }
    }

    /**
     * @param PHP_Manipulator_TokenContainer $container
     * @param PHP_Manipulator_Token $token
     * @return PHP_Manipulator_Token
     */
    public function getTargetToken(PHP_Manipulator_TokenContainer $container, PHP_Manipulator_Token $token)
    {
        return $container->getNextToken($token);
    }

    /**
     * @param PHP_Manipulator_TokenContainer $container
     * @param PHP_Manipulator_Token $targetToken
     * @param PHP_Manipulator_Token $newToken
     */
    public function insertToken(PHP_Manipulator_TokenContainer $container, PHP_Manipulator_Token $targetToken, PHP_Manipulator_Token $newToken)
    {
        $newTargetToken = $container->getPreviousToken($targetToken);
        $container->insertTokenAfter($newTargetToken, $newToken);
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

    /**
     *
     * @param PHP_Manipulator_Token $token
     * @param array $whitespaces
     * @return mixed
     */
    public function getWhitespaceForToken(PHP_Manipulator_Token $token, array $whitespaces)
    {
        if (null === $token->getType()) {
            $token = $token->getValue();
        } else {
            $token = $token->getType();
        }
        if (array_key_exists($token, $whitespaces)) {
            return $whitespaces[$token];
        } else {
            $message = 'No option found for: ' . token_name($token) .
                ' (' . $token . ')';
            throw new PHP_Manipulator_Exception($message);
        }
    }
}
