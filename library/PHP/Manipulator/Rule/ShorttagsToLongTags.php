<?php

class PHP_Manipulator_Rule_ShorttagsToLongTags extends PHP_Manipulator_Rule_Abstract
{

    /**
     *
     * @param PHP_Manipulator_TokenContainer $container
     */
    public function applyRuleToTokens(PHP_Manipulator_TokenContainer $container)
    {
        $iterator = $container->getIterator();

        while ($iterator->valid()) {
            $token = $iterator->current();
            /* @var $token PHP_Manipulator_Token */

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