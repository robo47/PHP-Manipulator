<?php

namespace PHP\Manipulator\ContainerManipulator;

use PHP\Manipulator\ContainerManipulator;
use PHP\Manipulator\Helper\NewlineDetector;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;

class StripUnneededPhpCloseTag
extends ContainerManipulator
{

    /**
     * Manipulate
     *
     * @param PHP\Manipulator\TokenContainer $container
     * @param mixed $params
     */
    public function manipulate(TokenContainer $container, $params = null)
    {
        $stripWhitespaceFromEnd = false;
        if (is_array($params) && isset($params['stripWhitespaceFromEnd'])) {
            $stripWhitespaceFromEnd = $params['stripWhitespaceFromEnd'];
        }
        $iterator = $container->getReverseIterator();

        while ($iterator->valid()) {
            $token = $iterator->current();

            if (!$this->_foo($token)) {
                break;
            } elseif($this->evaluateConstraint('IsType', $token, T_CLOSE_TAG)) {
                if ($this->evaluateConstraint('EndsWithNewline', $token)) {
                    $helper = new NewlineDetector("\n");
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
    protected function _foo(Token $token)
    {
        return $this->evaluateConstraint('IsType', $token, array(T_WHITESPACE, T_CLOSE_TAG)) || $this->_isOnlyWhitespace($token);
    }

    /**
     * @param Token $token
     * @return boolean
     * @todo Create \Contraint\ContainsOnlyWhitespace from this code
     */
    protected function _isOnlyWhitespace(Token $token)
    {
        if ($this->evaluateConstraint('IsType', $token, array(T_INLINE_HTML))) {
            if(false !== preg_match('~^(\n|\r|\t| )+$~', $token->getValue())) {
                return true;
            }
        }
        return false;
    }
}
