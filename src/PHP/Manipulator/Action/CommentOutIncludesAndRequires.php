<?php

namespace PHP\Manipulator\Action;

use PHP\Manipulator\Action;
use PHP\Manipulator\TokenContainer;
use PHP\Manipulator\Helper\CreateMultilineCommentFromTokenToToken;

/**
 * @package PHP\Manipulator
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @link    http://github.com/robo47/php-manipulator
 * @version @pear_package_version@ (@pear_package_git_hash@)
 * @uses    \PHP\Manipulator\Helper\CreateMultilineCommentFromTokenToToken;
 */
class CommentOutIncludesAndRequires
extends Action
{

    public function init()
    {
        if (!$this->hasOption('globalScopeOnly')) {
            $this->setOption('globalScopeOnly', true);
        }
    }

    /**
     * @param \PHP\Manipulator\TokenContainer $container
     * @param mixed $params
     */
    public function run(TokenContainer $container)
    {
        $tokens = $this->_searchStartTokens($container);
        $this->_handleTokens($container, $tokens);
        $container->retokenize();
    }

    /**
     * @param array $tokens
     */
    protected function _handleTokens(TokenContainer $container, array $tokens)
    {
        foreach ($tokens as $start) {
            if ($container->contains($start)) {
                $result = $this->findTokens(
                    'IncludeAndRequire',
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
     * @param \PHP\Manipulator\TokenContainer $container
     * @return array
     */
    protected function _searchStartTokens(TokenContainer $container)
    {
        $iterator = $container->getIterator();

        $searchedTokens = array(
            T_INCLUDE,
            T_INCLUDE_ONCE,
            T_REQUIRE,
            T_REQUIRE_ONCE
        );

        $foundPairs = array();

        $inClass = false;
        $inFunction = false;
        $bracesStatus = 0;

        while ($iterator->valid()) {
            $token = $iterator->current();
            if ($this->isType($token, T_CLASS)) {
                $inClass = true;
                $bracesStatus = 0;
            }
            if ($this->isType($token, T_FUNCTION)) {
                $inFunction = true;
                $bracesStatus = 0;
            }
            if ($inClass || $inFunction) {
                if ($this->isOpeningCurlyBrace( $token)) {
                    $bracesStatus++;
                }
                if ($this->isClosingCurlyBrace( $token)) {
                    $bracesStatus--;
                    if ($bracesStatus === 0) {
                        if ($inClass) {
                            $inClass = false;
                        }
                        if ($inFunction) {
                            $inFunction = false;
                        }
                    }
                }
            }
            if ($this->_shouldCheckAndReplace($inClass, $inFunction)) {
                if ($this->isType($token, $searchedTokens)) {
                    $foundPairs[] = $token;
                }
            }
            $iterator->next();
        }
        return $foundPairs;
    }

    /**
     * @param boolean $inClass
     * @param boolean $inFunction
     * @return boolean
     */
    protected function _shouldCheckAndReplace($inClass, $inFunction)
    {
        $globalScopeOnly = $this->getOption('globalScopeOnly');
        if (true === $globalScopeOnly && !($inClass || $inFunction)) {
            return true;
        } else if (false === $globalScopeOnly) {
            return true;
        }
        return false;
    }
}