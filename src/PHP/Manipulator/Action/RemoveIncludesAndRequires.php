<?php

namespace PHP\Manipulator\Action;

use PHP\Manipulator\TokenContainer;
use PHP\Manipulator\TokenFinder\IncludeAndRequireFinder;

class RemoveIncludesAndRequires extends CommentOutIncludesAndRequires
{
    protected function handleTokens(TokenContainer $container, array $tokens)
    {
        foreach ($tokens as $start) {
            if ($container->contains($start)) {
                $result = $this->findTokens(
                    IncludeAndRequireFinder::class,
                    $start,
                    $container
                );
                $tokens = $result->getTokens();
                foreach ($tokens as $token) {
                    if ($container->contains($token)) {
                        $container->removeToken($token);
                    }
                }
            }
        }
    }

    /**
     * @param bool $insideClass
     * @param bool $insideFunction
     *
     * @return bool
     */
    protected function shouldCheckAndReplace($insideClass, $insideFunction)
    {
        $globalScopeOnly = $this->getOption(CommentOutIncludesAndRequires::OPTION_GLOBAL_SCOPE_ONLY);
        if (true === $globalScopeOnly && !($insideClass || $insideFunction)) {
            return true;
        } elseif (false === $globalScopeOnly) {
            return true;
        }

        return false;
    }
}
