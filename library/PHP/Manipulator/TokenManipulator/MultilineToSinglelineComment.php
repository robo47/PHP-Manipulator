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

        $newValue = "";
        foreach($value as $key => $line) {
            if (strlen($line) >= 2 && $line[0] == '*' && $line[1] == '/') {
                $line = substr($line, 2);
            }
            if (strlen($line) >= 1 && $line[0] == '*') {
                $line = substr($line, 1);
            }
            if (strlen($line) >= 3 && $line[0] == '/' && $line[1] == '*' && $line[2] == '*') {
                $line = substr($line, 3);
            }
            if (strlen($line) >= 2 && $line[0] == '/' && $line[1] == '*') {
                $line = substr($line, 2);
            }

            // @todo detected linebreak, fallback to \n
            $newValue .= '//' . $line . "\n";
        }

        $token->setType(T_COMMENT);
        $token->setValue($newValue);
    }
}
