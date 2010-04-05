<?php

namespace PHP\Manipulator;

use PHP\Manipulator\TokenContainer;

interface IContainerManipulator
{

    /**
     * Manipulates a container
     *
     * @param PHP\Manipulator\TokenContainer $container
     * @param mixed $params
     */
    public function manipulate(TokenContainer $container, $params = null);

}