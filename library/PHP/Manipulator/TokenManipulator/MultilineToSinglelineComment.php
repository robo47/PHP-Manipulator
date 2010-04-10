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
        $value = $token->getValue();
        $value = preg_split('~(\r\n|\n|\r)~', $value);

        $newValue = '';
        foreach($value as $line) {
            // removes */ and * and /** and /**
            $line = preg_replace('~^(\*\/|\*|\/\*\*|\/\*){1,}(.*?)$~', '\2', $line);
            // @todo detected linebreak, fallback to \n
            $newValue .= '//' . $line . "\n";
        }

        $token->setType(T_COMMENT);
        $token->setValue($newValue);
    }
}
