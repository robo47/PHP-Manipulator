<?php

namespace PHP\Manipulator\Action;

use PHP\Manipulator\Action;
use PHP\Manipulator\TokenContainer;

class ShorttagsToLongTags
extends Action
{

    /**
     *
     * @param \PHP\Manipulator\TokenContainer $container
     */
    public function run(TokenContainer $container, $params = null)
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