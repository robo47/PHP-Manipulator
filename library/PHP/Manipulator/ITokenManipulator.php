<?php

namespace PHP\Manipulator;

use PHP\Manipulator\Token;

interface ITokenManipulator
{

    /**
     * Manipulates a Token
     *
     * @param PHP\Manipulator\Token $token
     * @param mixed $params
     */
    public function manipulate(Token $token, $params = null);

}