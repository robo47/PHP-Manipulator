<?php

interface PHP_Formatter_TokenConstraint_Interface
{
    /**
     * Evaluates a constraint for a token $token.
     *
     * @param PHP_Formatter_Token $token
     * @return bool
     */
    public function evaluate(PHP_Formatter_Token $token);
}