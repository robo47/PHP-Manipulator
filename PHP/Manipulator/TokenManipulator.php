<?php

namespace PHP\Manipulator;

use PHP\Manipulator\AHelper;
use PHP\Manipulator\Token;

/**
 * @package PHP\Manipulator
 * @license http://opensource.org/licenses/bsd-license.php The BSD License
 * @link    http://github.com/robo47/php-manipulator
 * @version @pear_package_version@ (@pear_package_git_hash@)
 */
abstract class TokenManipulator
extends AHelper
{

    /**
     * Manipulates a Token
     *
     * @param \PHP\Manipulator\Token $token
     * @param mixed $params
     */
    abstract public function manipulate(Token $token, $params = null);

}