<?php

namespace PHP\Manipulator\TokenConstraint;

use PHP\Manipulator\TokenConstraint;
use PHP\Manipulator\Token;

/**
 * @package PHP\Manipulator
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @link    http://github.com/robo47/php-manipulator
 * @version @pear_package_version@ (@pear_package_git_hash@)
 */
class IsSingleNewline
extends TokenConstraint
{

    /**
     * Evaluate if the token is a multiline comment
     *
     * @param \PHP\Manipulator\Token $token
     * @param mixed $param
     * @return boolean
     */
    public function evaluate(Token $token, $param = null)
    {
        $value = $token->getValue();
        if ($value === "\n" ||
            $value === "\r\n" ||
            $value === "\r") {
            return true;
        }
        return false;
    }
}