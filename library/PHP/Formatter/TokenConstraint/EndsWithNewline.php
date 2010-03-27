<?php

require_once 'PHP/Formatter/TokenConstraint/Abstract.php';

class PHP_Formatter_TokenConstraint_EndsWithNewline
extends PHP_Formatter_TokenConstraint_Abstract
{
    public function init()
    {

    }
    

    /**
     * Evaluate if the token is a multiline comment
     *
     * @param PHP_Formatter_Token $token
     * @param mixed $param
     * @return boolean
     */
    public function evaluate(PHP_Formatter_Token $token, $params = null)
    {
        $endsWithNewline = false;
        $pattern = '~(\n|\r\n|\r)$~';
        if (preg_match($pattern, $token->getValue())) {
            $endsWithNewline = true;
        }
        return $endsWithNewline;
    }
}
