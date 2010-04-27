<?php

namespace PHP\Manipulator\ContainerManipulator;

use PHP\Manipulator\ContainerManipulator;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;
use PHP\Manipulator\TokenConstraint\IsMultilineComment;

class CreateMultilineCommentFromTokenToToken
extends ContainerManipulator
{

    /**
     * Manipulate
     *
     * @param \PHP\Manipulator\TokenContainer $container
     * @param mixed $params
     */
    public function manipulate(TokenContainer $container, $params = null)
    {
        // @todo generic way for manipulators and constriants for checking $params ?
        if (!is_array($params)) {
            $message = 'invalid input $params should be an array';
            throw new \Exception($message);
        }
        if (!isset($params['from'])) {
            $message = "key 'from' not found in \$params";
            throw new \Exception($message);
        }
        if (!($params['from'] instanceof Token)) {
            $message = "key 'from' is not instance of PHP\Manipulator\Token";
            throw new \Exception($message);
        }
        if (!isset($params['to'])) {
            $message = "key 'to' not found in \$params";
            throw new \Exception($message);
        }
        if (!($params['to'] instanceof Token)) {
            $message = "key 'to' is not instance of PHP\Manipulator\Token";
            throw new \Exception($message);
        }

        $from = $params['from'];
        /* @var $from PHP\Manipulator\Token */

        $to = $params['to'];
        /* @var $from PHP\Manipulator\Token */

        if (!$container->contains($from)) {
            $message = "element 'from' not found in \$container";
            throw new \Exception($message);
        }

        if (!$container->contains($to)) {
            $message = "element 'to' not found in \$container";
            throw new \Exception($message);
        }

        $startOffset = $container->getOffsetByToken($from);

        $endOffset = $container->getOffsetByToken($to);

        if ($startOffset > $endOffset) {
            $message = 'startOffset is behind endOffset';
            throw new \Exception($message);
        }

        $tokens = $this->_getTokensFromStartToEnd($container, $startOffset, $endOffset);

        $value = $this->_mergeTokenValuesIntoString($tokens);

        $value = '/*' . $value . '*/';

        $commentToken = new Token($value, T_COMMENT);

        $container->insertAtOffset($startOffset, $commentToken);
        $container->removeTokens($tokens);
    }

    /**
     * @param \PHP\Manipulator\TokenContainer $container
     * @param integer $startOffset
     * @param integer $endOffset
     * @return array
     */
    protected function _getTokensFromStartToEnd(TokenContainer $container, $startOffset, $endOffset)
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
    protected function _mergeTokenValuesIntoString(array $tokens)
    {
        $value = '';
        foreach ($tokens as $token) {
            if (!$this->evaluateConstraint('IsMultilineComment', $token)) {
                $value .= $token->getValue();
            }
        }
        $value = str_replace('*/', '', $value);
        return $value;
    }
}