<?php

namespace PHP\Manipulator\Action;

use PHP\Manipulator\Action;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;

/**
 * @package PHP\Manipulator
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @link    http://github.com/robo47/php-manipulator
 * @version @pear_package_version@ (@pear_package_git_hash@)
 * @uses    \PHP\Manipulator\TokenConstraint\IsErrorControlOperator
 */
class RemoveErrorControlOperator
extends Action
{

    /**
     * Remove ErrorControlOperators (@)
     *
     * @param \PHP\Manipulator\TokenContainer $container
     * @param mixed $params
     */
    public function run(TokenContainer $container)
    {
        $iterator = $container->getIterator();

        while ($iterator->valid()) {
            $token = $iterator->current();
            if ($this->evaluateConstraint('IsErrorControlOperator', $token)) {
                $container->removeToken($token);
            }
            $iterator->next();
        }
        $container->retokenize();
    }
}