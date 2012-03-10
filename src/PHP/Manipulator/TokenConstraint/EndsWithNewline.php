<?php

namespace PHP\Manipulator\TokenConstraint;

use PHP\Manipulator\TokenConstraint;
use PHP\Manipulator\Token;

/**
 * @package PHP\Manipulator
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @link    http://github.com/robo47/php-manipulator
 */
class EndsWithNewline
extends TokenConstraint
{

    /**
     * Evaluate if the token is a multiline comment
     *
     * @param \PHP\Manipulator\Token $token
     * @param mixed $param
     * @return boolean
     */
    public function evaluate(Token $token, $params = null)
    {
        return (bool)preg_match('~(\n|\r\n|\r)$~', $token->getValue());
    }
}
