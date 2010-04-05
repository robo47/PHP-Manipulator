<?php

interface PHP_Manipulator_ContainerManipulator_Interface
{

    /**
     * Manipulates a container
     *
     * @param PHP_Manipulator_TokenContainer $container
     * @param mixed $params
     */
    public function manipulate(PHP_Manipulator_TokenContainer $container, $params = null);

}