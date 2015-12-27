<?php

namespace PHP\Manipulator\TokenManipulator;

use PHP\Manipulator\Helper\NewlineDetector;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenManipulator;

class IndentMultilineComment extends TokenManipulator
{
    public function manipulate(Token $token, $params = null)
    {
        $regexNewline = '(\n|\r\n|\r)';
        $indention    = $params;
        $value        = $token->getValue();
        $lines        = preg_split('~'.$regexNewline.'~', $value);

        $helper  = new NewlineDetector();
        $newline = $helper->getNewlineFromToken($token);

        $first = true;

        foreach ($lines as $key => $line) {
            if ($first) {
                $first = false;
            } else {
                $temp = trim($line);
                if (!empty($temp)) {
                    $lines[$key] = $indention.' '.$line;
                }
            }
        }

        $token->setValue(implode($newline, $lines));
    }
}
