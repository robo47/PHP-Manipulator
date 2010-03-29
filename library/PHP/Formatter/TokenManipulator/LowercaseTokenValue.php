<?php

class PHP_Formatter_TokenManipulator_LowercaseTokenValue
extends PHP_Formatter_TokenManipulator_Abstract
{

    /**
     * Lowercase for tokens value
     *
     * @param PHP_Formatter_Token $token
     * @param mixed $params
     */
    public function manipulate(PHP_Formatter_Token $token, $params = null)
    {
        $token->setValue(strtolower($token->getValue()));
    }
}
