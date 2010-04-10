<?php

namespace PHP\Manipulator\TokenManipulator;

use PHP\Manipulator\TokenManipulator;
use PHP\Manipulator\Token;

class MultilineToSinglelineComment
extends TokenManipulator
{

    /**
     * @param PHP\Manipulator\Token $token
     * @param mixed $params
     */
    public function manipulate(Token $token, $params = null)
    {
        if(!$this->evaluateConstraint('IsMultilineComment', $token)) {
            throw new \Exception('Token is no Multiline-comment');
        }
        $this->manipulateToken('RemoveCommentIndention', $token);
        $value = preg_split('~(\r\n|\n|\r)~', $token->getValue());

        $newValue = '';
        foreach($value as $line) {
            // removes */ and * and /** and /**
            // @todo detected linebreak, fallback to \n
            $newValue .= '//' . preg_replace('~^(\*\/|\*|\/\*\*|\/\*){1,}(.*?)$~', '\2', $line) . "\n";
        }

        $token->setType(T_COMMENT);
        $token->setValue($newValue);
    }
}
