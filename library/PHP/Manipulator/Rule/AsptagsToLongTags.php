<?php

namespace PHP\Manipulator\Rule;

use PHP\Manipulator\Rule;
use PHP\Manipulator\TokenContainer;

class AsptagsToLongTags
extends Rule
{

    /**
     *
     * @param \PHP\Manipulator\TokenContainer $container
     */
    public function apply(TokenContainer $container)
    {
        $iterator = $container->getIterator();

        while ($iterator->valid()) {
            $token = $iterator->current();
            if ($this->evaluateConstraint('IsType', $token, T_OPEN_TAG)) {
                $token->setValue(str_replace('<%', '<?php', $token->getValue()));
            } else if ($this->evaluateConstraint('IsType', $token, T_OPEN_TAG_WITH_ECHO)) {
                $token->setValue(str_replace('<%=', '<?php echo ', $token->getValue()));
            }
            $iterator->next();
        }
        $container->retokenize();
    }
}