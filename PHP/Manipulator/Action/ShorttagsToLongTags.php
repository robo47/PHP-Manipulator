<?php

namespace PHP\Manipulator\Action;

use PHP\Manipulator\Action;
use PHP\Manipulator\TokenContainer;

class ShorttagsToLongTags
extends Action
{

    /**
     * Transform Shorttags to Longtags
     *
     * @param \PHP\Manipulator\TokenContainer $container
     * @param mixed $params
     */
    public function run(TokenContainer $container)
    {
        $iterator = $container->getIterator();

        while ($iterator->valid()) {
            $token = $iterator->current();

            $value = $token->getValue();
            if ($this->isType($token, T_OPEN_TAG)) {
                $value = str_replace('<?php', '<?', $value);
                $value = str_replace('<?', '<?php', $value);
            } else if ($this->isType($token, T_OPEN_TAG_WITH_ECHO)) {
                $value = str_replace('<?=', '<?php echo ', $value);
            }

            $token->setValue($value);
            $iterator->next();
        }
        $container->retokenize();
    }
}