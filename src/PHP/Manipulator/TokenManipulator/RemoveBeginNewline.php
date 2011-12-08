<?php

namespace PHP\Manipulator\TokenManipulator;

use PHP\Manipulator\TokenManipulator;
use PHP\Manipulator\Token;

/**
 * @package PHP\Manipulator
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @link    http://github.com/robo47/php-manipulator
 * @version @pear_package_version@ (@pear_package_git_hash@)
 */
class RemoveBeginNewline
extends TokenManipulator
{

    /**
     * Manipulates a Token
     *
     * @param \PHP\Manipulator\Token $token
     * @param mixed $params
     */
    public function manipulate(Token $token, $params = null)
    {
        $value = $token->getValue();

        if (substr($value, 0, 2) === "\r\n") {
            $token->setValue(substr($value, 2));
        } else if (substr($value, 0, 1) === "\n") {
            $token->setValue(substr($value, 1));
        } else if (substr($value, 0, 1) === "\r") {
            $token->setValue(substr($value, 1));
        }
    }
}