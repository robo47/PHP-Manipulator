<?php

require_once 'PHP/Formatter/TokenManipulator/Interface.php';

class PHP_Formatter_TokenManipulator_RemoveFirstLinebreak
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

        $token->setValue($value);
    }
}