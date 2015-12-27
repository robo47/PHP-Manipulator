<?php

namespace PHP\Manipulator\Action;

use PHP\Manipulator\Action;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;

class ChangeQuotesToSingleQuotes extends Action
{
    public function run(TokenContainer $container)
    {
        $iterator = $container->getIterator();

        while ($iterator->valid()) {
            $token = $iterator->current();
            if ($token->isType(T_CONSTANT_ENCAPSED_STRING)) {
                if (!$this->containsEscapeSequence($token)) {
                    $token->replaceInValue('"', "'");
                }
            }
            $iterator->next();
        }
        $container->retokenize();
    }

    /**
     * @param Token $token
     *
     * @return bool
     */
    private function containsEscapeSequence(Token $token)
    {
        return (bool) preg_match('~'.preg_quote('\\', '~').'~', $token->getValue());
    }
}
