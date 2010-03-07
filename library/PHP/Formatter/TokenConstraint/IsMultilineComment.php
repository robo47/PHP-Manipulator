<?php

require_once 'PHP/Formatter/TokenConstraint/Interface.php';

class PHP_Formatter_TokenConstraint_IsMultilineComment
implements PHP_Formatter_TokenConstraint_Interface
{

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
        if ($token->isType(T_COMMENT)) {
            $value = $token->getValue();
            if (strlen($value) >= 2) {
                if (substr($value, 0, 2) == '/*') {
                    $isMultilineComment = true;
                }
            }
        } elseif($token->isType(T_DOC_COMMENT)) {
            $isMultilineComment = true;
        }
        return $isMultilineComment;
    }
}
