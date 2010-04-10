<?php

namespace PHP\Manipulator;

use PHP\Manipulator\AHelper;
use PHP\Manipulator\Token;

abstract class TokenManipulator
extends AHelper
{

    /**
     * Manipulates a Token
     *
     * @param \PHP\Manipulator\Token $token
     * @param mixed $params
     */
    abstract public function manipulate(Token $token, $params = null);

}