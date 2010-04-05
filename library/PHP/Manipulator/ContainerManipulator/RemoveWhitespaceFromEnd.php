<?php

class PHP_Manipulator_ContainerManipulator_RemoveWhitespaceFromEnd
extends PHP_Manipulator_ContainerManipulator_Abstract
{

    /**
     * Manipulate
     *
     * @param PHP_Manipulator_TokenContainer $container
     * @param mixed $params
     */
    public function manipulate(PHP_Manipulator_TokenContainer $container, $params = null)
    {
        $iterator = $container->getReverseIterator();

        $tokensToDelete = array();

        while ($iterator->valid()) {
            $token = $iterator->current();
            if ($this->evaluateConstraint('IsType', $token, T_WHITESPACE)) {
                $tokensToDelete[] = $token;
            } else {
                break;
            }
            $iterator->next();
        }

        foreach ($tokensToDelete as $token) {
            $container->removeToken($token);
        }
    }
}