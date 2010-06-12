<?php

namespace PHP\Manipulator\TokenManipulator;

use PHP\Manipulator\TokenManipulator;
use PHP\Manipulator\Token;

/**
 * @package PHP\Manipulator
 * @license http://opensource.org/licenses/bsd-license.php The BSD License
 * @link    http://github.com/robo47/php-manipulator
 * @version @pear_package_version@ (@pear_package_git_hash@)
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
        $regexWhitespace = '[\t ]{1,}';
        $linebreak = '\n|\r\n|\r';
        if ($this->evaluateConstraint('IsMultilineComment', $token)) {
            $value = $token->getValue();
            $value = preg_replace('~^' . $regexWhitespace . '(\/\*)(.*?)(' . $linebreak . ')~m', '\1\2\3', $value);
            $value = preg_replace('~(' . $linebreak . ')' . $regexWhitespace . '(\*.*?)(' . $linebreak . ')~m', '\1\2\3', $value);
            $value = preg_replace('~(' . $linebreak . ')' . $regexWhitespace . '(\*.*?)$~m', '\1\2', $value);
            $token->setValue($value);
        }
    }
}