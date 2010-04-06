<?php

namespace PHP\Manipulator\TokenManipulator;

use PHP\Manipulator\TokenManipulator;
use PHP\Manipulator\Token;

class RemoveCommentIndention
extends TokenManipulator
{

    /**
     * Manipulate Token
     *
     * @param PHP\Manipulator\Token $token
     * @param mixed $params
     */
    public function manipulate(Token $token, $params = null)
    {
        $regexWhitespace = '[\t ]{1,}';
        $linebreak = '\n|\r\n|\r';
        if ($this->evaluateConstraint('IsMultilineComment', $token)) {
            $value = $token->getValue();
            $value = preg_replace('~^' . $regexWhitespace . '(\/\*)(.*?)(' . $linebreak . ')~m', '\1\2\3', $value);
            $value = preg_replace('~(' . $linebreak . ')' . $regexWhitespace . '(\*.*?)(' . $linebreak . ')~m', '\1\2\3', $value);
            $value = preg_replace('~(' . $linebreak . ')' . $regexWhitespace . '(\*.*?)$~m', '\1\2', $value);
            $token->setValue($value);
        }
    }
}
