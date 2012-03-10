<?php

namespace PHP\Manipulator\TokenConstraint;

use PHP\Manipulator\TokenConstraint;
use PHP\Manipulator\Token;

/**
 * @package PHP\Manipulator
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @link    http://github.com/robo47/php-manipulator
 */
class IsSinglelineComment
extends TokenConstraint
{

    /**
     * Evaluate if the token is a Singleline comment
     *
     * @param \PHP\Manipulator\Token $token
     * @param mixed $param
     * @return boolean
     */
    public function evaluate(Token $token, $param = null)
    {
        if ($token->getType() === T_COMMENT) {
            $value = $token->getValue();
            if (strlen($value) >= 1 && substr($value, 0, 1) === '#') {
                return true;
            } elseif (strlen($value) >= 2 && substr($value, 0, 2) === '//') {
                return true;
            }
        }

        return false;
    }
}
