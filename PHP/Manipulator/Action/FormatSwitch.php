<?php

namespace PHP\Manipulator\Action;

use PHP\Manipulator\Action;
use PHP\Manipulator\TokenContainer;
use PHP\Manipulator\TokenContainer\Iterator;
use PHP\Manipulator\Token;
use PHP\Manipulator\ClosureFactory;
use PHP\Manipulator\Helper\NewlineDetector;

/**
 * @package PHP\Manipulator
 * @license http://opensource.org/licenses/bsd-license.php The BSD License
 * @link    http://github.com/robo47/php-manipulator
 * @version @pear_package_version@ (@pear_package_git_hash@)
 */
class FormatSwitch
extends Action
{
    /**
     * @var string
     */
    protected $_defaultLineBreak = "\n";

    public function init()
    {
        if (!$this->hasOption('spaceAfterSwitch')) {
            $this->setOption('spaceAfterSwitch', true);
        }
        // @todo rename to spaceBeforeCurlyBrace ?
        if (!$this->hasOption('spaceAfterSwitchVariable')) {
            $this->setOption('spaceAfterSwitchVariable', true);
        }
        if (!$this->hasOption('breakBeforeCurlyBrace')) {
            $this->setOption('breakBeforeCurlyBrace', false);
        }
    }


    /**
     * Run Action
     *
     * @param \PHP\Manipulator\TokenContainer $container
     */
    public function run(TokenContainer $container, $params = null)
    {
        $iterator = $container->getIterator();

        $helper = new NewlineDetector();
        $this->_defaultLineBreak = $helper->getNewlineFromContainer($container);

        while ($iterator->valid()) {
            $token = $iterator->current();
            if ($this->isType($token, T_SWITCH)) {
                if ($this->getOption('spaceAfterSwitch')) {
                    $iterator->next();

                    if ($this->isType($iterator->current(), T_WHITESPACE)) {
                        $iterator->current()->setValue(' ');
                    } else {
                        $whitespaceToken = new Token(' ', T_WHITESPACE);
                        $container->insertTokenAfter($token, $whitespaceToken);
                    }

                    $iterator->update($token);
                }
                if ($this->getOption('spaceAfterSwitchVariable')) {
                    $nextOpeningBrace = $this->getNextMatchingToken(
                        $iterator,
                        ClosureFactory::getTypeAndValueClosure(null, '(')
                    );
                    if (null !== $nextOpeningBrace) {
                        $iterator->seekToToken($nextOpeningBrace);
                        $matchingBrace = $this->getMatchingBrace($iterator);
                        if (null !== $matchingBrace) {
                            $iterator->seekToToken($matchingBrace);
                            $iterator->next();
                            if ($this->isType($iterator->current(), T_WHITESPACE)) {
                                $iterator->current()->setValue(' ');
                            } else {
                                $whitespaceToken = new Token(' ', T_WHITESPACE);
                                $container->insertTokenBefore($iterator->current(), $whitespaceToken);
                            }
                        }
                    }
                    $iterator->update($token);
                }
            }
            if ($this->isOpeningCurlyBrace($token)) {
                $iterator->next();
                if (!$this->isType($iterator->current(), T_WHITESPACE)) {
                    $whitespaceToken = new Token($this->_defaultLineBreak, T_WHITESPACE);
                    $container->insertTokenBefore($iterator->current(), $whitespaceToken);
                } else if (!$this->evaluateConstraint('ContainsNewline', $iterator->current())) {
                    $iterator->current()->setValue($this->_defaultLineBreak . $iterator->current()->getValue());
                }
                $iterator->update($token);
            }
            if ($this->isClosingCurlyBrace($token)) {
                $iterator->previous();
                if (!$this->isType($iterator->current(), T_WHITESPACE)) {
                    $whitespaceToken = new Token($this->_defaultLineBreak, T_WHITESPACE);
                    $container->insertTokenAfter($iterator->current(), $whitespaceToken);
                } else if (!$this->evaluateConstraint('ContainsNewline', $iterator->current())) {
                    $iterator->current()->setValue($iterator->current()->getValue() . $this->_defaultLineBreak);
                }
                $iterator->update($token);
            }
            if ($this->isType($token, T_CASE)) {
                $iterator->next();
                if (!$this->isType($iterator->current(), T_WHITESPACE)) {
                    $whitespaceToken = new Token(' ', T_WHITESPACE);
                    $container->insertTokenBefore($iterator->current(), $whitespaceToken);
                } else if (!$this->evaluateConstraint('ContainsNewline', $iterator->current())) {
                    $iterator->current()->setValue(' ');
                }
                $iterator->update($token);
                $iterator->previous();
                if (!$this->isType($iterator->current(), T_WHITESPACE)) {
                    $whitespaceToken = new Token($this->_defaultLineBreak, T_WHITESPACE);
                    $container->insertTokenAfter($iterator->current(), $whitespaceToken);
                } else if (!$this->evaluateConstraint('ContainsNewline', $iterator->current())) {
                    $iterator->current()->setValue($iterator->current()->getValue() . $this->_defaultLineBreak);
                }
                $iterator->update($token);

            }
            $iterator->next();
        }
        $container->retokenize();
    }
}