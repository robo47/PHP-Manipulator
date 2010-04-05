<?php

interface PHP_Manipulator_TokenManipulator_Interface
{

    /**
     * Manipulates a Token
     *
     * @param PHP_Manipulator_Token $token
     * @param mixed $params
     */
    public function manipulate(PHP_Manipulator_Token $token, $params = null);

}