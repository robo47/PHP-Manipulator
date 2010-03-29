<?php

class PHP_Formatter_TokenManipulator_UppercaseTokenValue
extends PHP_Formatter_TokenManipulator_Abstract
{

    /**
     * Uppercase for tokens value
     *
     * @param PHP_Formatter_Token $token
     * @param mixed $params
     * @return boolean
     */
    public function manipulate(PHP_Formatter_Token $token, $params = null)
    {
        $token->setValue(strtoupper($token->getValue()));
    }
}