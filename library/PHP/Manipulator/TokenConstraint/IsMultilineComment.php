<?php

namespace PHP\Manipulator\TokenConstraint;

use PHP\Manipulator\TokenConstraint;
use PHP\Manipulator\Token;

class IsMultilineComment
extends TokenConstraint
{

    /**
     * Evaluate if the token is a multiline comment
     *
     * @param \PHP\Manipulator\Token $token
     * @param mixed $param
     * @return boolean
     */
    public function evaluate(Token $token, $param = null)
    {
        $isMultilineComment = false;
        if ($token->getType() === T_COMMENT) {
            $value = $token->getValue();
            if (strlen($value) > 2) {
                if (substr($value, 0, 2) === '/*') {
                    $isMultilineComment = true;
                }
            }
        } elseif($token->getType() === T_DOC_COMMENT) {
            $isMultilineComment = true;
        }
        return $isMultilineComment;
    }
}