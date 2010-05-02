<?php

namespace PHP\Manipulator\Action;

use PHP\Manipulator\Action;
use PHP\Manipulator\TokenContainer;
use PHP\Manipulator\Token;

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
    public function run(TokenContainer $container, $params = null)
    {
        $iterator = $container->getIterator();

        $open = false;
        $this->_deleteList = array();
        while ($iterator->valid()) {
            $token = $iterator->current();
            if ($this->isType($token, array(T_OPEN_TAG, T_OPEN_TAG_WITH_ECHO))) {
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