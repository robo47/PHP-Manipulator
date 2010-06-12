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
class UppercaseTokenValue
extends TokenManipulator
{

    /**
     * Uppercase for tokens value
     *
     * @param \PHP\Manipulator\Token $token
     * @param mixed $params
     * @return boolean
     */
    public function manipulate(Token $token, $params = null)
    {
        $token->setValue(strtoupper($token->getValue()));
    }
}