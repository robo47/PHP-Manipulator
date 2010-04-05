<?php

class PHP_Manipulator_TokenManipulator_UppercaseTokenValue
extends PHP_Manipulator_TokenManipulator_Abstract
{

    /**
     * Uppercase for tokens value
     *
     * @param PHP_Manipulator_Token $token
     * @param mixed $params
     * @return boolean
     */
    public function manipulate(PHP_Manipulator_Token $token, $params = null)
    {
        $token->setValue(strtoupper($token->getValue()));
    }
}