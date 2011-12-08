<?php

namespace PHP\Manipulator\Helper;

use PHP\Manipulator\AHelper;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;
use PHP\Manipulator\TokenContainer\Iterator;

/**
 * @package PHP\Manipulator
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @link    http://github.com/robo47/php-manipulator
 * @version @pear_package_version@ (@pear_package_git_hash@)
 * @uses    \PHP\Manipulator\Helper\SetWhitespaceAfterToken
 */
class SetWhitespaceBeforeToken
extends SetWhitespaceAfterToken
{

    /**
     * @param Iterator $iterator
     */
    protected function _moveIteratorToTargetToken(Iterator $iterator)
    {
        $iterator->previous();
    }

    /**
     * @param Iterator $iterator
     */
    protected function _moveIteratorBackFromTagetToken(Iterator $iterator)
    {
        $iterator->next();
    }

    /**
     * @param \PHP\Manipulator\TokenContainer $container
     * @param \PHP\Manipulator\Token $targetToken
     * @param \PHP\Manipulator\Token $newToken
     */
    protected function _insertToken(Token $newToken, Iterator $iterator)
    {
        $this->_container->insertTokenAfter($iterator->current(), $newToken);
    }
}