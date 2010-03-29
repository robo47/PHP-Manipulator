<?php

interface PHP_Formatter_ContainerManipulator_Interface
{

    /**
     * Manipulates a container
     *
     * @param PHP_Formatter_TokenContainer $container
     * @param mixed $params
     */
    public function manipulate(PHP_Formatter_TokenContainer $container, $params = null);

}