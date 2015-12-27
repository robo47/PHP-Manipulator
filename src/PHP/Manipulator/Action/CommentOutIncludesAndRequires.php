<?php

namespace PHP\Manipulator\Action;

use PHP\Manipulator\Action;
use PHP\Manipulator\Helper\CreateMultilineCommentFromTokenToToken;
use PHP\Manipulator\TokenContainer;
use PHP\Manipulator\TokenFinder\IncludeAndRequireFinder;

class CommentOutIncludesAndRequires extends Action
{
    const OPTION_GLOBAL_SCOPE_ONLY = 'globalScopeOnly';

    public function init()
    {
        if (!$this->hasOption(self::OPTION_GLOBAL_SCOPE_ONLY)) {
            $this->setOption(self::OPTION_GLOBAL_SCOPE_ONLY, true);
        }
    }

    public function run(TokenContainer $container)
    {
        $tokens = $this->searchStartTokens($container);
        $this->handleTokens($container, $tokens);
        $container->retokenize();
    }

    /**
     * @param TokenContainer $container
     * @param array          $tokens
     */
    protected function handleTokens(TokenContainer $container, array $tokens)
    {
        foreach ($tokens as $start) {
            if ($container->contains($start)) {
                $result = $this->findTokens(
                    IncludeAndRequireFinder::class,
                    $start,
                    $container
                );
                $commentOut = new CreateMultilineCommentFromTokenToToken();
                $commentOut->run(
                    $container,
                    $result->getFirstToken(),
                    $result->getLastToken()
                );
            }
        }
    }

    /**
     * @param TokenContainer $container
     *
     * @return array
     */
    private function searchStartTokens(TokenContainer $container)
    {
        $iterator = $container->getIterator();

        $searchedTokens = [
            T_INCLUDE,
            T_INCLUDE_ONCE,
            T_REQUIRE,
            T_REQUIRE_ONCE,
        ];

        $foundPairs = [];

        $insideClass      = false;
        $insideFunction   = false;
        $bracesStatus     = 0;

        while ($iterator->valid()) {
            $token = $iterator->current();
            if ($token->isType(T_CLASS)) {
                $insideClass      = true;
                $bracesStatus     = 0;
            }
            if ($token->isType(T_FUNCTION)) {
                $insideFunction   = true;
                $bracesStatus     = 0;
            }
            if ($insideClass || $insideFunction) {
                if ($token->isOpeningCurlyBrace()) {
                    $bracesStatus++;
                }
                if ($token->isClosingCurlyBrace()) {
                    $bracesStatus--;
                    if ($bracesStatus === 0) {
                        if ($insideClass) {
                            $insideClass = false;
                        }
                        if ($insideFunction) {
                            $insideFunction = false;
                        }
                    }
                }
            }
            if ($this->shouldCheckAndReplace($insideClass, $insideFunction)) {
                if ($token->isType($searchedTokens)) {
                    $foundPairs[] = $token;
                }
            }
            $iterator->next();
        }

        return $foundPairs;
    }

    /**
     * @param bool $insideClass
     * @param bool $insideFunction
     *
     * @return bool
     */
    protected function shouldCheckAndReplace($insideClass, $insideFunction)
    {
        $globalScopeOnly = $this->getOption(self::OPTION_GLOBAL_SCOPE_ONLY);
        if (true === $globalScopeOnly && !($insideClass || $insideFunction)) {
            return true;
        } elseif (false === $globalScopeOnly) {
            return true;
        }

        return false;
    }
}
