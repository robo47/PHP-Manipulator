<?php

namespace PHP\Manipulator\Action;

use PHP\Manipulator\Action;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;

class StripPhp extends Action
{
    /**
     * @var Token[]
     */
    private $deleteList = [];

    public function run(TokenContainer $container)
    {
        $iterator = $container->getIterator();

        $open             = false;
        $this->deleteList = [];
        $allowedTokens    = [T_OPEN_TAG, T_OPEN_TAG_WITH_ECHO];
        while ($iterator->valid()) {
            $token = $iterator->current();
            if ($token->isType($allowedTokens)) {
                $open = true;
            }
            if ($this->shoudDelete($open)) {
                $this->deleteList[] = $token;
            }
            if ($token->isType(T_CLOSE_TAG)) {
                $open = false;
            }
            $iterator->next();
        }
        $container->removeTokens($this->deleteList);
        $container->retokenize();
    }

    /**
     * @param bool $open
     *
     * @return bool
     */
    protected function shoudDelete($open)
    {
        return $open;
    }
}
