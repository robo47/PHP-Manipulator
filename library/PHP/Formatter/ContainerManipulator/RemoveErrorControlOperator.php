<?php

class PHP_Formatter_ContainerManipulator_RemoveErrorControlOperator
extends PHP_Formatter_ContainerManipulator_Abstract
{

    /**
     * Manipulate Container
     * 
     * @param PHP_Formatter_TokenContainer $container
     * @param mixed $params
     */
    public function manipulate(PHP_Formatter_TokenContainer $container, $params = null)
    {
        $iterator = $container->getIterator();

        $errorControllTokens = array();
        while ($iterator->valid()) {
            $token = $iterator->current();
            /* @var $token PHP_Formatter_Token */
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
