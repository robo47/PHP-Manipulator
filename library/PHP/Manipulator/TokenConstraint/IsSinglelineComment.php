<?php

class PHP_Manipulator_TokenConstraint_IsSinglelineComment
extends PHP_Manipulator_TokenConstraint_Abstract
{

    /**
     * Evaluate if the token is a Singleline comment
     *
     * @param PHP_Manipulator_Token $token
     * @param mixed $param
     * @return boolean
     */
    public function evaluate(PHP_Manipulator_Token $token, $param = null)
    {
        $isSinglelineComment = false;
        if ($token->getType() === T_COMMENT) {
            $value = $token->getValue();
            if (strlen($value) >= 1 && substr($value, 0, 1) == '#') {
                $isSinglelineComment = true;
            } else if (strlen($value) >= 2 && substr($value, 0, 2) == '//') {
                $isSinglelineComment = true;
            }
        }
        return $isSinglelineComment;
    }
}