<?php

require_once 'PHP/Formatter/TokenManipulator/Abstract.php';

class PHP_Formatter_TokenManipulator_LowercaseTokenValue
extends PHP_Formatter_TokenManipulator_Abstract
{

    /**
     * Lowercase for tokens value
     * 
     * @param PHP_Formatter_Token $token
     * @param mixed $params
     * @return boolean
     */
    public function manipulate(PHP_Formatter_Token $token, $params = null)
    {
        $newValue = strtolower($token->getValue());
        $token->setValue($newValue);
        return true;
    }
}
