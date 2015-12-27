<?php

namespace PHP\Manipulator\TokenManipulator;

use PHP\Manipulator\Token;
use PHP\Manipulator\TokenManipulator;

class RemoveCommentIndention extends TokenManipulator
{
    public function manipulate(Token $token, $params = null)
    {
        if ($token->isMultilineComment()) {
            $regexWhitespace = '[\t ]{1,}';
            $linebreak       = '\n|\r\n|\r';
            $value           = $token->getValue();
            $pattern1        = sprintf('~^%s(/\*)(.*?)(%s)~m', $regexWhitespace, $linebreak);
            $pattern2        = sprintf('~(%s)%s(\*.*?)(%s)~m', $linebreak, $regexWhitespace, $linebreak);
            $pattern3        = sprintf('~(%s)%s(\*.*?)$~m', $linebreak, $regexWhitespace);
            $value           = preg_replace($pattern1, '\1\2\3', $value);
            $value           = preg_replace($pattern2, '\1\2\3', $value);
            $value           = preg_replace($pattern3, '\1\2', $value);
            $token->setValue($value);
        }
    }
}
