<?php

require_once 'PHP/Formatter/ContainerManipulator/Abstract.php';

class PHP_Formatter_ContainerManipulator_CreateMultilineCommentFromTokenToToken
extends PHP_Formatter_ContainerManipulator_Abstract
{

    /**
     * Manipulate
     *
     * @param PHP_Formatter_TokenContainer $container
     * @param array $params
     * @return boolean
     */
    public function manipulate(PHP_Formatter_TokenContainer $container, $params = null)
    {
        if (!is_array($params)) {
            require_once 'PHP/Formatter/Exception.php';
            $message = 'invalid input $params should be an array';
            throw new PHP_Formatter_Exception($message);
        }
        if (!isset($params['from'])) {
            require_once 'PHP/Formatter/Exception.php';
            $message = "key 'from' not found in \$params";
            throw new PHP_Formatter_Exception($message);
        }
        if (!($params['from'] instanceof PHP_Formatter_Token)) {
            require_once 'PHP/Formatter/Exception.php';
            $message = "key 'from' is not instance of PHP_Formatter_Token";
            throw new PHP_Formatter_Exception($message);
        }
        if (!isset($params['to'])) {
            require_once 'PHP/Formatter/Exception.php';
            $message = "key 'to' not found in \$params";
            throw new PHP_Formatter_Exception($message);
        }
        if (!($params['to'] instanceof PHP_Formatter_Token)) {
            require_once 'PHP/Formatter/Exception.php';
            $message = "key 'to' is not instance of PHP_Formatter_Token";
            throw new PHP_Formatter_Exception($message);
        }

        $from = $params['from'];
        /* @var $from PHP_Formatter_Token */
        $to = $params['to'];
        /* @var $from PHP_Formatter_Token */

        if (!$container->contains($from)) {
            require_once 'PHP/Formatter/Exception.php';
            $message = "element 'from' not found in \$container";
            throw new PHP_Formatter_Exception($message);
        }

        if (!$container->contains($to)) {
            require_once 'PHP/Formatter/Exception.php';
            $message = "element 'to' not found in \$container";
            throw new PHP_Formatter_Exception($message);
        }

        $startOffset = $container->getOffsetByToken($from);
        $startPosition = $container->getPositionForOffset($startOffset);

        $endOffset = $container->getOffsetByToken($to);

        if ($startOffset > $endOffset) {
            require_once 'PHP/Formatter/Exception.php';
            $message = "startOffset is behind endOffset";
            throw new PHP_Formatter_Exception($message);
        }

        $tokens = $this->_getTokensFromStartToEnd($container, $startPosition, $endOffset);

        $value = $this->_mergeTokenValuesIntoString($tokens);

        $value = '/*' . $value . '*/';

        $commentToken = new PHP_Formatter_Token($value, T_COMMENT);

        $container->insertAtPosition($startPosition, $commentToken);

        $container->removeTokens($tokens);
    }

    /**
     * @param PHP_Formatter_TokenContainer $container
     * @param integer $startPosition
     * @param integer $endOffset
     * @return array
     */
    protected function _getTokensFromStartToEnd($container, $startPosition, $endOffset)
    {
        $iterator = $container->getIterator();
        $iterator->seek($startPosition);

        $tokens = array();
        while ($iterator->valid()) {
            $tokens[] = $iterator->current();
            if ($iterator->key() == $endOffset) {
                break;
            }
            $iterator->next();
        }
        return $tokens;
    }

    /**
     * @param array $tokens
     * @return string
     */
    protected function _mergeTokenValuesIntoString($tokens)
    {
        $value = '';
        foreach ($tokens as $token) {
            /* @var $token PHP_Formatter_Token */
            if (!$this->_isMultilineComment($token)) {
                $value .= $token->getValue();
            }
        }
        return $value;
    }

    /**
     * @param PHP_Formatter_Token $token
     * @return boolean
     */
    protected function _isMultilineComment($token)
    {
        $constraint = new PHP_Formatter_TokenConstraint_IsMultilineComment();
        return $constraint->evaluate($token);
    }
}