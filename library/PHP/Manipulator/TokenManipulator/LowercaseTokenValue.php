<?php

class PHP_Manipulator_TokenManipulator_LowercaseTokenValue
extends PHP_Manipulator_TokenManipulator_Abstract
{

    /**
     * Lowercase for tokens value
     *
     * @param PHP_Manipulator_Token $token
     * @param mixed $params
     */
    public function manipulate(PHP_Manipulator_Token $token, $params = null)
    {
        $token->setValue(strtolower($token->getValue()));
    }
}
