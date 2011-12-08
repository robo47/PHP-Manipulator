<?php

namespace PHP\Manipulator\TokenContainer;

use PHP\Manipulator\TokenContainer\Iterator;

/**
 * @package PHP\Manipulator
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @link    http://github.com/robo47/php-manipulator
 * @version @pear_package_version@ (@pear_package_git_hash@)
 * @uses    \PHP\Manipulator\TokenContainer\Iterator
 */
class ReverseIterator extends Iterator
{

    protected function _init()
    {
        parent::_init();
        $this->_keys = array_reverse($this->_keys);
    }
}