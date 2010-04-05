<?php

class PHP_Manipulator_TokenConstraint_EndsWithNewline
extends PHP_Manipulator_TokenConstraint_Abstract
{

    /**
     * Evaluate if the token is a multiline comment
     *
     * @param PHP_Manipulator_Token $token
     * @param mixed $param
     * @return boolean
     */
    public function evaluate(PHP_Manipulator_Token $token, $params = null)
    {
        $endsWithNewline = false;
        $pattern = '~(\n|\r\n|\r)$~';
        if (preg_match($pattern, $token->getValue())) {
            $endsWithNewline = true;
        }
        return $endsWithNewline;
    }
}
