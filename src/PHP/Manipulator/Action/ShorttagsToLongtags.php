<?php

namespace PHP\Manipulator\Action;

use PHP\Manipulator\Action;
use PHP\Manipulator\TokenContainer;

class ShorttagsToLongtags extends Action
{
    public function run(TokenContainer $container)
    {
        $iterator = $container->getIterator();

        while ($iterator->valid()) {
            $token = $iterator->current();

            if ($token->isType(T_OPEN_TAG)) {
                $token->replaceInValue('<?php', '<?');
                $token->replaceInValue('<?', '<?php');
            } elseif ($token->isType(T_OPEN_TAG_WITH_ECHO)) {
                $token->replaceInValue('<?=', '<?php echo ');
            }
            $iterator->next();
        }
        $container->retokenize();
    }
}
