<?php

namespace PHP\Manipulator\Action;

use PHP\Manipulator\Action;
use PHP\Manipulator\TokenContainer;

/**
 * @package PHP\Manipulator
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @link    http://github.com/robo47/php-manipulator
 */
class AsptagsToLongtags
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
            } elseif ($this->isType($token, T_OPEN_TAG_WITH_ECHO)) {
                $token->setValue(str_replace('<%=', '<?php echo ', $token->getValue()));
            }
            $iterator->next();
        }
        $container->retokenize();
    }
}
