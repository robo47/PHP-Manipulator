<?php

namespace PHP\Manipulator\Action;

use PHP\Manipulator\Action;
use PHP\Manipulator\TokenContainer;

class ElseAndIfToElseIf
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

        $waitingForIf = false;
        $replaceTokens = array();

        while ($iterator->valid()) {
            $token = $iterator->current();
            if ($this->evaluateConstraint('IsType', $token, T_ELSE)) {
                $waitingForIf = true;
                $replaceTokens = array();
            }
            if (true === $waitingForIf && !$this->evaluateConstraint('IsType', $token, array(T_IF, T_ELSE, T_WHITESPACE))) {
                $waitingForIf = false;
            } else {
                $replaceTokens[] = $token;
            }

            if (true === $waitingForIf && $this->evaluateConstraint('IsType', $token, T_IF)) {
                $waitingForIf = false;
                $token = array_pop($replaceTokens);
                $token->setType(T_ELSEIF);
                $token->setValue('elseif');

                $container->removeTokens($replaceTokens);

                $replaceTokens = array();
            }

            $iterator->next();
        }
        $container->retokenize();
    }
}