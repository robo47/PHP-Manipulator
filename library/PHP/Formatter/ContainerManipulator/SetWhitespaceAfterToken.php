<?php

require_once 'PHP/Formatter/ContainerManipulator/Abstract.php';

class PHP_Formatter_ContainerManipulator_SetWhitespaceAfterToken
extends PHP_Formatter_ContainerManipulator_Abstract
{
    /**
     * @param PHP_Formatter_TokenContainer $container
     * @param mixed $params
     * @return boolean
     */
    public function manipulate(PHP_Formatter_TokenContainer $container, $params = null)
    {
        if (!is_array($params)) {
            require_once 'PHP/Formatter/Exception.php';
            $message = 'invalid input $params should be an array';
            throw new PHP_Formatter_Exception($message);
        }
        if (!isset($params['tokens'])) {
            require_once 'PHP/Formatter/Exception.php';
            $message = "key 'tokens' not found in \$params";
            throw new PHP_Formatter_Exception($message);
        }
        if (!isset($params['whitespace'])) {
            require_once 'PHP/Formatter/Exception.php';
            $message = "key 'whitespace' not found in \$params";
            throw new PHP_Formatter_Exception($message);
        }

        $tokens = $params['tokens'];
        $whitespace = $params['whitespace'];
        foreach($tokens as $token) {
            $this->setWhitespace($container, $token, $whitespace);
        }
        return true;
    }

    /**
     * @param PHP_Formatter_TokenContainer $container
     * @param PHP_Formatter_Token $token
     * @return PHP_Formatter_Token
     */
    public function getTargetToken(PHP_Formatter_TokenContainer $container, PHP_Formatter_Token $token)
    {
        return $container->getNextToken($token);
    }

    /**
     * @param PHP_Formatter_TokenContainer $container
     * @param PHP_Formatter_Token $targetToken
     * @param PHP_Formatter_Token $newToken
     */
    public function insertToken(PHP_Formatter_TokenContainer $container, PHP_Formatter_Token $targetToken, PHP_Formatter_Token $newToken)
    {
        $newTargetToken = $container->getPreviousToken($targetToken);
        $container->insertTokenAfter($newTargetToken, $newToken);
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
            if(empty($tokenValue)) {
                $container->removeToken($targetToken);
            } else {
                $targetToken->setValue($tokenValue);
            }
        }
        if(null !== $targetToken && !$this->evaluateConstraint('IsType', $targetToken, T_WHITESPACE)) {
            if(!empty($tokenValue)) {
                $newToken = PHP_Formatter_Token::factory(array(T_WHITESPACE, $tokenValue));
                $this->insertToken($container, $targetToken, $newToken);
            }
        }
    }

    /**
     *
     * @param PHP_Formatter_Token $token
     * @param array $whitespaces
     * @return mixed
     */
    public function getWhitespaceForToken(PHP_Formatter_Token $token, array $whitespaces)
    {
        if (null ===$token->getType()) {
            $token = $token->getValue();
        } else {
            $token = $token->getType();
        }
        if (array_key_exists($token, $whitespaces)) {
            return $whitespaces[$token];
        } else {
            require_once 'PHP/Formatter/Exception.php';
            $message = 'No option found for: ' . token_name($token) .
                        ' (' . $token . ')';
            throw new PHP_Formatter_Exception($message);
        }
    }
}
