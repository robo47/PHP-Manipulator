<?php

class PHP_Manipulator_ContainerManipulator_RemoveErrorControlOperator
extends PHP_Manipulator_ContainerManipulator_Abstract
{

    /**
     * Manipulate Container
     * 
     * @param PHP_Manipulator_TokenContainer $container
     * @param mixed $params
     */
    public function manipulate(PHP_Manipulator_TokenContainer $container, $params = null)
    {
        $iterator = $container->getIterator();

        $errorControllTokens = array();
        while ($iterator->valid()) {
            $token = $iterator->current();
            /* @var $token PHP_Manipulator_Token */
            if ($this->evaluateConstraint('IsErrorControlOperator', $token)) {
                $errorControllTokens[] = $token;
            }
            $iterator->next();
        }
        foreach($errorControllTokens as $errorControllToken) {
            $container->removeToken($errorControllToken);
        }
        $container->retokenize();
    }
}
