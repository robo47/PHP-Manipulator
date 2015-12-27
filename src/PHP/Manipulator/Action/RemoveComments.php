<?php

namespace PHP\Manipulator\Action;

use PHP\Manipulator\Action;
use PHP\Manipulator\Helper\NewlineDetector;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;

class RemoveComments extends Action
{
    const OPTION_REMOVE_DOC_COMMENTS      = 'removeDocComments';
    const OPTION_REMOVE_STANDARD_COMMENTS = 'removeStandardComments';

    public function init()
    {
        if (!$this->hasOption(self::OPTION_REMOVE_DOC_COMMENTS)) {
            $this->setOption(self::OPTION_REMOVE_DOC_COMMENTS, true);
        }
        if (!$this->hasOption(self::OPTION_REMOVE_STANDARD_COMMENTS)) {
            $this->setOption(self::OPTION_REMOVE_STANDARD_COMMENTS, true);
        }
    }

    public function run(TokenContainer $container)
    {
        $helper  = new NewlineDetector();
        $newline = $helper->getNewlineFromContainer($container);

        $iterator = $container->getIterator();
        while ($iterator->valid()) {
            $token = $iterator->current();
            if ($this->isCommentAndShouldBeRemoved($token)) {
                if ($token->isSingleLineComment()) {
                    $token->setType(T_WHITESPACE);
                    $token->setValue($newline);
                } else {
                    $container->removeToken($token);
                }
            }
            $iterator->next();
        }
        $container->retokenize();
    }

    /**
     * @param Token $token
     *
     * @return bool
     */
    private function isCommentAndShouldBeRemoved(Token $token)
    {
        return ($token->isType(T_DOC_COMMENT) && $this->getOption(self::OPTION_REMOVE_DOC_COMMENTS)) ||
        ($token->isType(T_COMMENT) && $this->getOption(self::OPTION_REMOVE_STANDARD_COMMENTS));
    }
}
