<?php

namespace PHP\Manipulator\Action;

use PHP\Manipulator\Action;
use PHP\Manipulator\Helper\NewlineDetector;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;

class StripUnneededPhpCloseTag
extends Action
{

    /**
     * Manipulate
     *
     * @param PHP\Manipulator\TokenContainer $container
     * @param mixed $params
     */
    public function run(TokenContainer $container, $params = null)
    {
        $stripWhitespaceFromEnd = false;
        if (is_array($params) && isset($params['stripWhitespaceFromEnd'])) {
            $stripWhitespaceFromEnd = $params['stripWhitespaceFromEnd'];
        }
        $iterator = $container->getReverseIterator();
        $helper = new NewlineDetector("\n");

        while ($iterator->valid()) {
            $token = $iterator->current();

            if (!$this->_isNotAllowedTag($token)) {
                break;
            } elseif($this->evaluateConstraint('IsType', $token, T_CLOSE_TAG)) {
                if ($this->evaluateConstraint('EndsWithNewline', $token)) {

                    $newline = $helper->getNewlineFromToken($token);
                    $token->setType(T_WHITESPACE);
                    $token->setValue($newline);
                } else {
                    $container->removeToken($token);
                }
                break;
            }
            $iterator->next();
        }
        $container->retokenize();
        if (true === $stripWhitespaceFromEnd) {
            $this->manipulateContainer('RemoveWhitespaceFromEnd', $container);
        }
    }

    /**
     * @param Token $token
     * @return boolean
     */
    protected function _isNotAllowedTag(Token $token)
    {
        return $this->evaluateConstraint('IsType', $token, array(T_WHITESPACE, T_CLOSE_TAG)) || $this->evaluateConstraint('ContainsOnlyWhitespace', $token);
    }
}
