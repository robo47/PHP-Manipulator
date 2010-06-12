<?php

namespace PHP\Manipulator\Action;

use PHP\Manipulator\Action;
use PHP\Manipulator\TokenContainer;
use PHP\Manipulator\Token;

/**
 * @package PHP\Manipulator
 * @license http://opensource.org/licenses/bsd-license.php The BSD License
 * @link    http://github.com/robo47/php-manipulator
 * @version @pear_package_version@ (@pear_package_git_hash@)
 */
class StripPhp
extends Action
{
    /**
     * @var array
     */
    protected $_deleteList = array();

    /**
     * Remove php-code
     *
     * @param \PHP\Manipulator\TokenContainer $container
     * @param mixed $params
     */
    public function run(TokenContainer $container)
    {
        $iterator = $container->getIterator();

        $open = false;
        $this->_deleteList = array();
        $allowedTokens = array(T_OPEN_TAG, T_OPEN_TAG_WITH_ECHO);
        while ($iterator->valid()) {
            $token = $iterator->current();
            if ($this->isType($token, $allowedTokens)) {
                $open = true;
            }
            if ($this->_shoudDelete($open)) {
                $this->_deleteList[] = $token;
            }
            if ($this->isType($token, T_CLOSE_TAG)) {
                $open = false;
            }
            $iterator->next();
        }
        $container->removeTokens($this->_deleteList);
        $container->retokenize();
    }

    /**
     * @param boolean $open
     * @return boolean
     */
    protected function _shoudDelete($open)
    {
        return $open;
    }
}