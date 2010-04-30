<?php

namespace PHP\Manipulator\TokenFinder;

use PHP\Manipulator\TokenFinder;
use PHP\Manipulator\TokenFinder\Result;
use PHP\Manipulator\TokenContainer;
use PHP\Manipulator\Token;

class SwitchFinder
extends TokenFinder
{

    /**
     * Finds tokens
     *
     * @param \PHP\Manipulator\Token $token
     * @param \PHP\Manipulator\TokenContainer $container
     * @param mixed $params
     * @return \PHP\Manipulator\TokenFinder\Result
     */
    public function find(Token $token, TokenContainer $container, $params = null)
    {
        if (!$this->evaluateConstraint('IsType', $token, T_SWITCH)) {
            throw new Exception('Starttoken is not T_SWITCH');
        }
        $result = new Result();
        $iterator = $container->getIterator();
        $key = $container->getOffsetByToken($token);
        $iterator->seek($key);

        $level = 0;
        $inside = false;

        while ($iterator->valid()) {
            $token = $iterator->current();
            if ($this->evaluateConstraint('IsOpeningCurlyBrace', $token)) {
                if (0 === $level) {
                    $inside = true;
                }
                $level++;
            }
            if ($this->evaluateConstraint('IsClosingCurlyBrace', $token)) {
                $level--;
            }
            $result->addToken($token);

            if ($inside && 0 === $level) {
                break;
            }
            $iterator->next();
        }
        return $result;
    }
}