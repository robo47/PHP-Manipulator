<?php

namespace PHP\Manipulator\ContainerManipulator;

use PHP\Manipulator\ContainerManipulator;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;

class SetWhitespaceAfterToken
extends ContainerManipulator
{

    /**
     * @param \PHP\Manipulator\TokenContainer $container
     * @param mixed $params
     */
    public function manipulate(TokenContainer $container, $params = null)
    {
        if (!is_array($params)) {
            $message = 'invalid input $params should be an array';
            throw new \Exception($message);
        }
        if (!isset($params['tokens'])) {
            $message = "key 'tokens' not found in \$params";
            throw new \Exception($message);
        }
        if (!isset($params['whitespace'])) {
            $message = "key 'whitespace' not found in \$params";
            throw new \Exception($message);
        }

        $tokens = $params['tokens'];
        $whitespace = $params['whitespace'];
        foreach ($tokens as $token) {
            $this->setWhitespace($container, $token, $whitespace);
        }
    }

    /**
     * @param \PHP\Manipulator\TokenContainer $container
     * @param \PHP\Manipulator\Token $token
     * @return \PHP\Manipulator\Token
     */
    public function getTargetToken(TokenContainer $container, Token $token)
    {
        return $container->getNextToken($token);
    }

    /**
     * @param \PHP\Manipulator\TokenContainer $container
     * @param \PHP\Manipulator\Token $targetToken
     * @param \PHP\Manipulator\Token $newToken
     */
    public function insertToken(TokenContainer $container, Token $targetToken, Token $newToken)
    {
        $newTargetToken = $container->getPreviousToken($targetToken);
        $container->insertTokenAfter($newTargetToken, $newToken);
    }

    /**
     * @param \PHP\Manipulator\TokenContainer $container
     * @param \PHP\Manipulator\Token $token
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

    /**
     *
     * @param \PHP\Manipulator\Token $token
     * @param array $whitespaces
     * @return mixed
     */
    public function getWhitespaceForToken(Token $token, array $whitespaces)
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
            throw new \Exception($message);
        }
    }
}
