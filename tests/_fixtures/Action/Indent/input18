<?php

namespace PHP\Manipulator\Action;

use PHP\Manipulator\Action;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;

class FormatOperators
extends Action
{

    public function init()
    {
        if (!$this->hasOption('afterOperator')) {
            $this->setOption(
                'afterOperator',
                array (
                    // assignment operators
                    '=' => ' ',
                    T_AND_EQUAL => ' ',
                    T_CONCAT_EQUAL => ' ',
                    T_DIV_EQUAL => ' ',
                    T_MINUS_EQUAL => ' ',
                    T_MOD_EQUAL => ' ',
                    T_MUL_EQUAL => ' ',
                    T_OR_EQUAL => ' ',
                    T_PLUS_EQUAL => ' ',
                    T_SR_EQUAL => ' ',
                    T_SL_EQUAL => ' ',
                    T_XOR_EQUAL => ' ',

                    // logical operators
                    T_LOGICAL_AND => ' ',
                    T_LOGICAL_OR => ' ',
                    T_LOGICAL_XOR => ' ',
                    T_BOOLEAN_AND => ' ',
                    T_BOOLEAN_OR => ' ',

                    // bitwise operators
                    T_SL => ' ',
                    T_SR => ' ',

                    // incrementing/decrementing operators
                    T_DEC => '',
                    T_INC => '',

                    // comparision operators
                    T_IS_EQUAL => ' ',
                    T_IS_GREATER_OR_EQUAL => ' ',
                    T_IS_IDENTICAL => ' ',
                    T_IS_NOT_EQUAL => ' ',
                    T_IS_NOT_IDENTICAL => ' ',
                    T_IS_SMALLER_OR_EQUAL => ' ',

                    // type-operators
                    T_INSTANCEOF => ' ',
                )
            );
        }
        // @todo blub
    }

    /**
     *
     *
     */
    public function apply(TokenContainer $container)
    {
        $iterator = $container->getIterator();
    }
}