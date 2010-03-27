<?php

require_once 'PHP/Formatter/Rule/Abstract.php';

class PHP_Formatter_Rule_AsptagsToLongTags extends PHP_Formatter_Rule_Abstract
{

    /**
     *
     * @param PHP_Formatter_TokenContainer $container
     */
    public function applyRuleToTokens(PHP_Formatter_TokenContainer $container)
    {
        $iterator = $container->getIterator();

        while ($iterator->valid()) {
            $token = $iterator->current();
            /* @var $token PHP_Formatter_Token */

            $value = $token->getValue();
            // @todo check if it is faster/better to use regular expression
            if ($this->evaluateConstraint('IsType', $token, T_OPEN_TAG)) {
                $value = str_replace('<%', '<?php', $value);
            } else if ($this->evaluateConstraint('IsType', $token, T_OPEN_TAG_WITH_ECHO)) {
                $value = str_replace('<%=', '<?php echo ', $value);
            }

            $token->setValue($value);
            $iterator->next();
        }
        $container->retokenize();
    }
}