<?php

namespace PHP\Manipulator\TokenManipulator;

use PHP\Manipulator\TokenManipulator;
use PHP\Manipulator\Token;

class IndentMultilineComment
extends TokenManipulator
{

    /**
     * Manipulate Token
     *
     * @param \PHP\Manipulator\Token $token
     * @param mixed $params
     */
    public function manipulate(Token $token, $params = null)
    {
        $regexNewline = '(\n|\r\n|\r)';
        $indention = $params;
        $value = $token->getValue();
        $lines = preg_split('~' . $regexNewline . '~', $value);
        // @todo preg_match the used newline [if one is used?]
        $newline = "\n";

        $first = true;
        $value = '';

        foreach ($lines as $key => $line) {
            if ($first) {
                $first = false;
            } else {
                $temp = trim($line);
                if (!empty($temp)) {
                    $lines[$key] = $indention . ' ' . $line;
                }
            }
        }

        $token->setValue(implode($newline, $lines));
    }
}