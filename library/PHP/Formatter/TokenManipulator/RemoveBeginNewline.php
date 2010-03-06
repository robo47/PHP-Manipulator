<?php

require_once 'PHP/Formatter/TokenManipulator/Interface.php';

class PHP_Formatter_TokenManipulator_RemoveBeginNewline
implements PHP_Formatter_TokenManipulator_Interface
{
    /**
     * Manipulates a Token
     *
     * @param PHP_Formatter_Token $token
     */
    public function manipulate(PHP_Formatter_Token $token)
    {
        $value = $token->getValue();
        if (substr($value, 0, 1) == "\n") {
            $token->setValue(substr($value, 1));
        } elseif (substr($value, 0, 2) == "\n\r") {
            $token->setValue(substr($value, 2));
        } elseif (substr($value, 0, 1) == "\r") {
            $token->setValue(substr($value, 1));
        } else {
            require_once 'PHP/Formatter/Exception.php';
            $message = 'Token does not begin with Newline';
            throw new PHP_Formatter_Exception($message);
        }
    }
}