<?php

namespace PHP\Manipulator\TokenManipulator;

use PHP\Manipulator\TokenManipulator;
use PHP\Manipulator\Token;
use PHP\Manipulator\Helper\NewlineDetector;

class MultilineToSinglelineComment
extends TokenManipulator
{

    /**
     * @param PHP\Manipulator\Token $token
     * @param mixed $params
     */
    public function manipulate(Token $token, $params = null)
    {
        if (!$this->evaluateConstraint('IsMultilineComment', $token)) {
            throw new \Exception('Token is no Multiline-comment');
        }
        $this->manipulateToken('RemoveCommentIndention', $token);
        $value = preg_split('~(\r\n|\n|\r)~', $token->getValue());

        $newValue = '';
        $helper = new NewlineDetector();
        $newline = $helper->getNewline($token);
        foreach ($value as $line) {
            // removes */ and * and /** and /**
            $newValue .= '//' . preg_replace('~^(\*\/|\*|\/\*\*|\/\*){1,}(.*?)$~', '\2', $line) . $newline;
        }

        $token->setType(T_COMMENT);
        $token->setValue($newValue);
    }
}
