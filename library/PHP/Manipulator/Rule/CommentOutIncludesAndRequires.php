<?php

namespace PHP\Manipulator\Rule;

use PHP\Manipulator\Rule;
use PHP\Manipulator\TokenContainer;

class CommentOutIncludesAndRequires
extends Rule
{
    
    public function init()
    {
        if (!$this->hasOption('globalScopeOnly')) {
            $this->setOption('globalScopeOnly', true);
        }
    }

    /**
     *
     * @param \PHP\Manipulator\TokenContainer $tokens
     */
    public function apply(TokenContainer $container)
    {
        $iterator = $container->getIterator();
        $iterator->rewind();

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
            if ($this->evaluateConstraint('IsType', $token, T_CLASS)) {
                $inClass = true;
                $bracesStatus = 0;
            }
            if ($this->evaluateConstraint('IsType', $token, T_FUNCTION)) {
                $inFunction = true;
                $bracesStatus = 0;
            }
            if ($inClass || $inFunction) {
                if ($this->evaluateConstraint('IsOpeningCurlyBrace', $token)) {
                    $bracesStatus++;
                }
                if ($this->evaluateConstraint('IsClosingCurlyBrace', $token)) {
                    $bracesStatus--;
                    if ($bracesStatus == 0) {
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
                /* @var $token PHP\Manipulator\Token */
                if ($this->evaluateConstraint('IsType', $token, $searchedTokens)) {
                    $foundPairs[] = $token;
                }
            }
            $iterator->next();
        }

        foreach ($foundPairs as $start) {
            try {
                $result = $this->findTokens('IncludeAndRequire', $start, $container);
                $this->manipulateContainer(
                        'CreateMultilineCommentFromTokenToToken',
                        $container,
                        array(
                            'from' => $result->getFirstToken(),
                            'to' => $result->getLastToken(),
                        )
                );
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