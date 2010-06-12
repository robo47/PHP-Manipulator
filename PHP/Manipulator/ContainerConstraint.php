<?php

namespace PHP\Manipulator;

use PHP\Manipulator\AHelper;
use PHP\Manipulator\TokenContainer;

/**
 * @package PHP\Manipulator
 * @license http://opensource.org/licenses/bsd-license.php The BSD License
 * @link    http://github.com/robo47/php-manipulator
 * @version @pear_package_version@ (@pear_package_git_hash@)
 */
abstract class ContainerConstraint
extends AHelper
{

    /**
     * Evaluates a constraint on a container
     *
     * @param \PHP\Manipulator\TokenContainer $container
     * @param mixed $params
     */
    abstract public function evaluate(TokenContainer $container, $params = null);

}