<?php

namespace PHP\Manipulator\Action;

use PHP\Manipulator\Action\StripPhp;
use PHP\Manipulator\TokenContainer;
use PHP\Manipulator\Token;

/**
 * @package PHP\Manipulator
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @link    http://github.com/robo47/php-manipulator
 * @version @pear_package_version@ (@pear_package_git_hash@)
 */
class StripNonPhp
extends StripPhp
{
    /**
     * @param boolean $open
     * @return boolean
     */
    protected function _shoudDelete($open)
    {
        return !$open;
    }
}