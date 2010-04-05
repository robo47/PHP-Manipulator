<?php

interface PHP_Manipulator_TokenConstraint_Interface
{

    /**
     * Evaluates a constraint for a token $token.
     *
     * @param PHP_Manipulator_Token $token
     * @param mixed $params
     * @return bool
     */
    public function evaluate(PHP_Manipulator_Token $token, $params = null);

}