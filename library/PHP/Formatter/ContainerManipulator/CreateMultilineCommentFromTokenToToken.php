<?php

class PHP_Formatter_ContainerManipulator_CreateMultilineCommentFromTokenToToken
extends PHP_Formatter_ContainerManipulator_Abstract
{

    /**
     * Manipulate
     *
     * @param PHP_Formatter_TokenContainer $container
     * @param mixed $params
     */
    public function manipulate(PHP_Formatter_TokenContainer $container, $params = null)
    {
        if (!is_array($params)) {
            $message = 'invalid input $params should be an array';
            throw new PHP_Formatter_Exception($message);
        }
        if (!isset($params['from'])) {
            $message = "key 'from' not found in \$params";
            throw new PHP_Formatter_Exception($message);
        }
        if (!($params['from'] instanceof PHP_Formatter_Token)) {
            $message = "key 'from' is not instance of PHP_Formatter_Token";
            throw new PHP_Formatter_Exception($message);
        }
        if (!isset($params['to'])) {
            $message = "key 'to' not found in \$params";
            throw new PHP_Formatter_Exception($message);
        }
        if (!($params['to'] instanceof PHP_Formatter_Token)) {
            $message = "key 'to' is not instance of PHP_Formatter_Token";
            throw new PHP_Formatter_Exception($message);
        }

        $from = $params['from'];
        /* @var $from PHP_Formatter_Token */
        $to = $params['to'];
        /* @var $from PHP_Formatter_Token */

        if (!$container->contains($from)) {
            $message = "element 'from' not found in \$container";
            throw new PHP_Formatter_Exception($message);
        }

        if (!$container->contains($to)) {
            $message = "element 'to' not found in \$container";
            throw new PHP_Formatter_Exception($message);
        }

        $startOffset = $container->getOffsetByToken($from);

        $endOffset = $container->getOffsetByToken($to);

        if ($startOffset > $endOffset) {
            $message = "startOffset is behind endOffset";
            throw new PHP_Formatter_Exception($message);
        }

        $tokens = $this->_getTokensFromStartToEnd($container, $startOffset, $endOffset);

        $value = $this->_mergeTokenValuesIntoString($tokens);

        $value = '/*' . $value . '*/';

        $commentToken = new PHP_Formatter_Token($value, T_COMMENT);

        $container->insertAtOffset($startOffset, $commentToken);

        $container->removeTokens($tokens);
    }

    /**
     * @param PHP_Formatter_TokenContainer $container
     * @param integer $startOffset
     * @param integer $endOffset
     * @return array
     */
    protected function _getTokensFromStartToEnd($container, $startOffset, $endOffset)
    {
        $iterator = $container->getIterator();
        $iterator->seek($startOffset);

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