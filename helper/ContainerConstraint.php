<?php

namespace PHP\Manipulator\ContainerConstraint;

use PHP\Manipulator\ContainerConstraint;
use PHP\Manipulator\TokenContainer;

/**
 * @package PHP\Manipulator
 * @license http://opensource.org/licenses/bsd-license.php The BSD License
 * @link    http://github.com/robo47/php-manipulator
 * @version @pear_package_version@ (@pear_package_git_hash@)
 */
class __classname__
extends ContainerConstraint
{

    /**
     * Evaluate if the container
     *
     * @param \PHP\Manipulator\TokenContainer $container
     * @param mixed $params
     * @return boolean
     */
    public function evaluate(TokenContainer $container, $params = null)
    {
        $iterator = $container->getIterator();

        while ($iterator->valid()) {

            $iterator->next();
        }
        return false;
    }
}
