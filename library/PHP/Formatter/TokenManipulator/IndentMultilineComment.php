<?php

class PHP_Formatter_TokenManipulator_IndentMultilineComment
extends PHP_Formatter_TokenManipulator_Abstract
{

    /**
     * Manipulate Token
     *
     * @param PHP_Formatter_Token $token
     * @param mixed $params
     */
    public function manipulate(PHP_Formatter_Token $token, $params = null)
    {
        $regexNewline = '(\n|\r\n|\r)';
        $indention = $params;
        $value = $token->getValue();
        $lines = preg_split('~'.$regexNewline.'~', $value);
        // @todo preg_match the used newline [if one is used?]
        $newline = "\n";

        $first = true;
        $value = '';

        foreach($lines as $key => $line) {
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
