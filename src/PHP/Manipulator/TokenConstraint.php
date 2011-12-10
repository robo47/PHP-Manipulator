<?php

namespace PHP\Manipulator;

use PHP\Manipulator\AHelper;
use PHP\Manipulator\Token;

/**
 * @package PHP\Manipulator
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @link    http://github.com/robo47/php-manipulator
 */
abstract class TokenConstraint
extends AHelper
{

    /**
     * Evaluates a constraint for a token $token.
     *
     * @param \PHP\Manipulator\Token $token
     * @param mixed $params
     * @return boolean
     */
    abstract public function evaluate(Token $token, $params = null);

}