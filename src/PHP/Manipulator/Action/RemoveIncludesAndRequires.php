<?php

namespace PHP\Manipulator\Action;

use PHP\Manipulator\Action;
use PHP\Manipulator\TokenContainer;

/**
 * @package PHP\Manipulator
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @link    http://github.com/robo47/php-manipulator
 * @version @pear_package_version@ (@pear_package_git_hash@)
 * @uses    \PHP\Manipulator\TokenFinder\IncludeAndRequire
 * @todo add possibility to filter which tokens should be deleted and which not
 */
class RemoveIncludesAndRequires
extends CommentOutIncludesAndRequires
{

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