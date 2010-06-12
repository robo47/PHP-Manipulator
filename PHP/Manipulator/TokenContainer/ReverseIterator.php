<?php

namespace PHP\Manipulator\TokenContainer;

use PHP\Manipulator\TokenContainer\Iterator;
use PHP\Manipulator\TokenContainer;
use PHP\Manipulator\Token;

/**
 * @package PHP\Manipulator
 * @license http://opensource.org/licenses/bsd-license.php The BSD License
 * @link    http://github.com/robo47/php-manipulator
 * @version @pear_package_version@ (@pear_package_git_hash@)
 */
class ReverseIterator extends Iterator
{

    /**
     * @param \PHP\Manipulator\TokenContainer $container
     */
    public function __construct(TokenContainer $container)
    {
        parent::__construct($container);
    }

    protected function _init()
    {
        parent::_init();
        $this->_keys = array_reverse($this->_keys);
    }
}