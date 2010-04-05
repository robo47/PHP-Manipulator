<?php

class PHP_Manipulator_TokenConstraint_IsSingleNewline
extends PHP_Manipulator_TokenConstraint_Abstract
{

    /**
     * Evaluate if the token is a multiline comment
     *
     * @param PHP_Manipulator_Token $token
     * @param mixed $param
     * @return boolean
     */
    public function evaluate(PHP_Manipulator_Token $token, $param = null)
    {
        $isNewline = false;
        $value = $token->getValue();
        if ($value === "\n" ||
            $value === "\r\n" ||
            $value === "\r") {
            $isNewline = true;
        }
        return $isNewline;
    }
}
