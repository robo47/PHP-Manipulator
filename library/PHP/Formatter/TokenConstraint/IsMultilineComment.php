<?php

require_once 'PHP/Formatter/TokenConstraint/Abstract.php';

class PHP_Formatter_TokenConstraint_IsMultilineComment
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
    public function evaluate(PHP_Formatter_Token $token, $param = null)
    {
        $isMultilineComment = false;
        if ($token->getType() === T_COMMENT) {
            $value = $token->getValue();
            if (strlen($value) > 2) {
                if (substr($value, 0, 2) == '/*') {
                    $isMultilineComment = true;
                }
            }
        } elseif($token->getType() === T_DOC_COMMENT) {
            $isMultilineComment = true;
        }
        return $isMultilineComment;
    }
}