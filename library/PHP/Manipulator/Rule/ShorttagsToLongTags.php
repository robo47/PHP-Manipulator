<?php

namespace PHP\Manipulator\Rule;

use PHP\Manipulator\Rule;
use PHP\Manipulator\TokenContainer;

class ShorttagsToLongTags
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

            $value = $token->getValue();
            if ($this->evaluateConstraint('IsType', $token, T_OPEN_TAG)) {
                $value = str_replace('<?php', '<?', $value);
                $value = str_replace('<?', '<?php', $value);
            } else if ($this->evaluateConstraint('IsType', $token, T_OPEN_TAG_WITH_ECHO)) {
                $value = str_replace('<?=', '<?php echo ', $value);
            }

            $token->setValue($value);
            $iterator->next();
        }
        $container->retokenize();
    }
}