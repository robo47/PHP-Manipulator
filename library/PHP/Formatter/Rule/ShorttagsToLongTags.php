<?php

class PHP_Formatter_Rule_ShorttagsToLongTags extends PHP_Formatter_Rule_Abstract
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