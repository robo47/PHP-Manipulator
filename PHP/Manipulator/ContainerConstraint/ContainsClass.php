<?php

namespace PHP\Manipulator\ContainerConstraint;

use PHP\Manipulator\ContainerConstraint;
use PHP\Manipulator\TokenContainer;

/**
 * @package PHP\Manipulator
 * @license http://opensource.org/licenses/bsd-license.php The BSD License
 * @link    http://github.com/robo47/php-manipulator
 * @version @pear_package_version@ (@pear_package_git_hash@)
 * @deprecated
 * @todo seems unneeded ... marked deprecated can be removed if no usage is found
 * 
 */
class ContainsClass
extends ContainerConstraint
{

    /**
     * @param \PHP\Manipulator\TokenContainer $container
     * @param mixed $params
     * @return boolean
     */
    public function evaluate(TokenContainer $container, $params = null)
    {
        $iterator = $container->getIterator();

        while ($iterator->valid()) {
            if ($this->isType($iterator->current(), T_CLASS)) {
                return true;
            }
            $iterator->next();
        }
        return false;
    }
}