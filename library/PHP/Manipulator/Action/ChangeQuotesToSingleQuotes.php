<?php

namespace PHP\Manipulator\Action;

use PHP\Manipulator\Action;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;

class ChangeQuotesToSingleQuotes
extends Action
{

    /**
     * Run Action
     *
     * @param PHP\Manipulator\TokenContainer $container
     */
    public function run(TokenContainer $container, $params = null)
    {
        $iterator = $container->getIterator();

        while ($iterator->valid()) {
            $token = $iterator->current();
            if ($this->isType($token, T_CONSTANT_ENCAPSED_STRING)) {
                if (!$this->_containsEscapeSequence($token)) {
                    $value = $token->getValue();
                    $token->setValue(str_replace('"', '\'', $value));
                }
            }
            $iterator->next();
        }
        $container->retokenize();
    }

    /**
     * Token $token
     * @return boolean
     */
    protected function _containsEscapeSequence(Token $token)
    {
        return (bool) preg_match('~' . preg_quote('\\', '~') . '~', $token->getValue());
    }
}