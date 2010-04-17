<?php

namespace PHP\Manipulator\Rule;

use PHP\Manipulator\Rule;
use PHP\Manipulator\TokenContainer;

class RemoveIncludesAndRequires
extends CommentOutIncludesAndRequires
{
    /**
     * @param array $tokens
     */
    protected function _handleTokens(TokenContainer $container, array $tokens)
    {
        foreach ($tokens as $start) {
            try {
                $result = $this->findTokens('IncludeAndRequire', $start, $container);
                $tokens = $result->getTokens();
                foreach ($tokens as $token) {
                    try {
                        $container->removeToken($token);
                    } catch (\Exception $e) {
                        // @todo better way to Catch this Exception, named exceptions or exceptions with error-codes
                        // ignore exceptions which occur on nested include/require-stuff
                        if (false === strpos($e->getMessage(), 'does not exist in this container')) {
                            throw $e;
                        }
                    }
                }
            } catch (\Exception $e) {
                // @todo better way to Catch this Exception, named exceptions or exceptions with error-codes
                // ignore exceptions which occur on nested include/require-stuff
                if (false === strpos($e->getMessage(), 'does not exist in this container')) {
                    throw $e;
                }
            }
        }
    }



    /**
     *
     * @param boolean $inClass
     * @param boolean $inFunction
     * @return boolean
     */
    protected function _shouldCheckAndReplace($inClass, $inFunction)
    {
        $globalScopeOnly = $this->getOption('globalScopeOnly');
        if (true === $globalScopeOnly && !($inClass || $inFunction)) {
            return true;
        } elseif (false === $globalScopeOnly) {
            return true;
        }
        return false;
    }
}