<?php

namespace PHP\Manipulator\Action;

use PHP\Manipulator\Action;
use PHP\Manipulator\TokenContainer;

/**
 * @package PHP\Manipulator
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @link    http://github.com/robo47/php-manipulator
 * @version @pear_package_version@ (@pear_package_git_hash@)
 * @uses    \PHP\Manipulator\TokenManipulator\RemoveLeadingAndTrailingEmptyLinesInPhpdoc
 */
class RemoveLeadingAndTrailingEmptyLinesInPhpdoc
extends Action
{

    /**
     * Run Action
     *
     * @param \PHP\Manipulator\TokenContainer $container
     */
    public function run(TokenContainer $container, $params = null)
    {
        $iterator = $container->getIterator();

        while ($iterator->valid()) {
            $token = $iterator->current();
            if ($this->isType($token, T_DOC_COMMENT)) {
                $this->manipulateToken('RemoveLeadingAndTrailingEmptyLinesInPhpdoc', $token);
            }
            $iterator->next();
        }
        $container->retokenize();
    }
}