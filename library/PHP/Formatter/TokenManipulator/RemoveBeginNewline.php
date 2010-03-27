<?php

require_once 'PHP/Formatter/TokenManipulator/Abstract.php';

class PHP_Formatter_TokenManipulator_RemoveBeginNewline
extends PHP_Formatter_TokenManipulator_Abstract
{

    /**
     * Manipulates a Token
     *
     * @param PHP_Formatter_Token $token
     * @param mixed $params
     * @return boolean
     */
    public function manipulate(PHP_Formatter_Token $token, $params = null)
    {
        $value = $token->getValue();

        if (substr($value, 0, 2) == "\r\n") {
            $token->setValue(substr($value, 2));
        } else if (substr($value, 0, 1) == "\n") {
            $token->setValue(substr($value, 1));
        } elseif (substr($value, 0, 1) == "\r") {
            $token->setValue(substr($value, 1));
        } else {
            return false;
        }
        return true;
    }
}