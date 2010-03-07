<?php

interface PHP_Formatter_TokenConstraint_Interface
{

    /**
     * Evaluates a constraint for a token $token.
     *
     * @param PHP_Formatter_Token $token
     * @param mixed $params
     * @return bool
     */
    public function evaluate(PHP_Formatter_Token $token, $params = null);

}