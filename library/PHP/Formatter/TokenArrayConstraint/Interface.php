<?php

interface PHP_Formatter_TokenConstraintArray_Interface
{
    /**
     * Evaluates a constraint for a tokenArray $tokenArray.
     *
     * @param PHP_Formatter_TokenContainer $tokenArray
     * @return bool
     */
    public function evaluate(PHP_Formatter_TokenContainer $tokenArray);
}