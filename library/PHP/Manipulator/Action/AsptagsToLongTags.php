<?php

namespace PHP\Manipulator\Action;

use PHP\Manipulator\Action;
use PHP\Manipulator\TokenContainer;

class AsptagsToLongTags
extends Action
{

    /**
     * @param \PHP\Manipulator\TokenContainer $container
     * @param mixed $params
     */
    public function run(TokenContainer $container)
    {
        $iterator = $container->getIterator();

        while ($iterator->valid()) {
            $token = $iterator->current();
            if ($this->isType($token, T_OPEN_TAG)) {
                $token->setValue(str_replace('<%', '<?php', $token->getValue()));
            } else if ($this->isType($token, T_OPEN_TAG_WITH_ECHO)) {
                $token->setValue(str_replace('<%=', '<?php echo ', $token->getValue()));
            }
            $iterator->next();
        }
        $container->retokenize();
    }
}