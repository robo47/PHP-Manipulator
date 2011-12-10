<?php

namespace PHP\Manipulator\TokenManipulator;

use PHP\Manipulator\TokenManipulator;
use PHP\Manipulator\Token;

/**
 * @package PHP\Manipulator
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @link    http://github.com/robo47/php-manipulator
 * @uses    \PHP\Manipulator\TokenConstraint\IsMultilineComment
 */
class RemoveCommentIndention
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
        if ($this->evaluateConstraint('IsMultilineComment', $token)) {
            $regexWhitespace = '[\t ]{1,}';
            $linebreak = '\n|\r\n|\r';
            $value = $token->getValue();
            $value = preg_replace('~^' . $regexWhitespace . '(\/\*)(.*?)(' . $linebreak . ')~m', '\1\2\3', $value);
            $value = preg_replace('~(' . $linebreak . ')' . $regexWhitespace . '(\*.*?)(' . $linebreak . ')~m', '\1\2\3', $value);
            $value = preg_replace('~(' . $linebreak . ')' . $regexWhitespace . '(\*.*?)$~m', '\1\2', $value);
            $token->setValue($value);
        }
    }
}