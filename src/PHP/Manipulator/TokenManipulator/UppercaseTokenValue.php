<?php

namespace PHP\Manipulator\TokenManipulator;

use PHP\Manipulator\TokenManipulator;
use PHP\Manipulator\Token;

/**
 * @package PHP\Manipulator
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @link    http://github.com/robo47/php-manipulator
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