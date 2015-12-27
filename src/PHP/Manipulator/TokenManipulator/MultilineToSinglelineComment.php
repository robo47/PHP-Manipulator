<?php

namespace PHP\Manipulator\TokenManipulator;

use PHP\Manipulator\Exception\TokenManipulatorException;
use PHP\Manipulator\Helper\NewlineDetector;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenManipulator;

class MultilineToSinglelineComment extends TokenManipulator
{
    public function manipulate(Token $token, $params = null)
    {
        if (!$token->isMultilineComment()) {
            $message = 'Token is no Multiline-comment';
            throw new TokenManipulatorException($message, TokenManipulatorException::TOKEN_IS_NO_MULTILINE_COMMENT);
        }
        $this->manipulateToken(RemoveCommentIndention::class, $token);
        $value = preg_split('~(\r\n|\n|\r)~', $token->getValue());

        $newValue = '';
        $helper   = new NewlineDetector();
        $newline  = $helper->getNewlineFromToken($token);
        foreach ($value as $line) {
            // removes */ and * and /** and /**
            $newValue .= '//'.preg_replace('~^(\*/|\*|/\*\*|/\*)+(.*?)$~', '\2', $line).$newline;
        }

        $token->setType(T_COMMENT);
        $token->setValue($newValue);
    }
}
