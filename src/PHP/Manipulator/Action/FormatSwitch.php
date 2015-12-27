<?php

namespace PHP\Manipulator\Action;

use PHP\Manipulator\Action;
use PHP\Manipulator\MatcherFactory;
use PHP\Manipulator\Helper\NewlineDetector;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;

class FormatSwitch extends Action
{
    const OPTION_BREAK_BEFORE_CURLY_BRACE = 'breakBeforeCurlyBrace';
    const OPTION_SPACE_BEFORE_CURLY_BRACE = 'spaceAfterSwitchVariable';
    const OPTION_SPACE_AFTER_SWITCH       = 'spaceAfterSwitch';

    /**
     * @var string
     */
    private $defaultLineBreak = "\n";

    public function init()
    {
        if (!$this->hasOption(self::OPTION_SPACE_AFTER_SWITCH)) {
            $this->setOption(self::OPTION_SPACE_AFTER_SWITCH, true);
        }
        // @todo rename to spaceBeforeCurlyBrace ?
        if (!$this->hasOption(self::OPTION_SPACE_BEFORE_CURLY_BRACE)) {
            $this->setOption(self::OPTION_SPACE_BEFORE_CURLY_BRACE, true);
        }
        if (!$this->hasOption(self::OPTION_BREAK_BEFORE_CURLY_BRACE)) {
            $this->setOption(self::OPTION_BREAK_BEFORE_CURLY_BRACE, false);
        }
    }

    public function run(TokenContainer $container, $params = null)
    {
        $iterator = $container->getIterator();

        $helper                 = new NewlineDetector();
        $this->defaultLineBreak = $helper->getNewlineFromContainer($container);

        while ($iterator->valid()) {
            $token = $iterator->current();
            if ($token->isType(T_SWITCH)) {
                if ($this->getOption(self::OPTION_SPACE_AFTER_SWITCH)) {
                    $iterator->next();

                    if ($iterator->current()->isWhitespace()) {
                        $iterator->current()->setValue(' ');
                    } else {
                        $whitespaceToken = Token::createFromValueAndType(' ', T_WHITESPACE);
                        $container->insertTokenAfter($token, $whitespaceToken);
                    }

                    $iterator->update($token);
                }
                if ($this->getOption(self::OPTION_SPACE_BEFORE_CURLY_BRACE)) {
                    $nextOpeningBrace = $this->getNextMatchingToken(
                        $iterator,
                        MatcherFactory::getTypeAndValueClosure(null, '(')
                    );
                    if (null !== $nextOpeningBrace) {
                        $iterator->seekToToken($nextOpeningBrace);
                        $matchingBrace = $this->getMatchingBrace($iterator);
                        if (null !== $matchingBrace) {
                            $iterator->seekToToken($matchingBrace);
                            $iterator->next();
                            if ($iterator->current()->isWhitespace()) {
                                $iterator->current()->setValue(' ');
                            } else {
                                $whitespaceToken = Token::createFromValueAndType(' ', T_WHITESPACE);
                                $container->insertTokenBefore($iterator->current(), $whitespaceToken);
                            }
                        }
                    }
                    $iterator->update($token);
                }
            }
            if ($token->isOpeningCurlyBrace()) {
                $iterator->next();
                if (!$iterator->current()->isWhitespace()) {
                    $whitespaceToken = Token::createFromValueAndType($this->defaultLineBreak, T_WHITESPACE);
                    $container->insertTokenBefore($iterator->current(), $whitespaceToken);
                } elseif (!$iterator->current()->containsNewline()) {
                    $iterator->current()->setValue($this->defaultLineBreak.$iterator->current()->getValue());
                }
                $iterator->update($token);
            }
            if ($token->isClosingCurlyBrace()) {
                $iterator->previous();
                if (!$iterator->current()->isWhitespace()) {
                    $whitespaceToken = Token::createFromValueAndType($this->defaultLineBreak, T_WHITESPACE);
                    $container->insertTokenAfter($iterator->current(), $whitespaceToken);
                } elseif (!$iterator->current()->containsNewline()) {
                    $iterator->current()->setValue($iterator->current()->getValue().$this->defaultLineBreak);
                }
                $iterator->update($token);
            }
            if ($token->isType(T_CASE)) {
                $iterator->next();
                if (!$iterator->current()->isWhitespace()) {
                    $whitespaceToken = Token::createFromValueAndType(' ', T_WHITESPACE);
                    $container->insertTokenBefore($iterator->current(), $whitespaceToken);
                } elseif (!$iterator->current()->containsNewline()) {
                    $iterator->current()->setValue(' ');
                }
                $iterator->update($token);
                $iterator->previous();
                if (!$iterator->current()->isWhitespace()) {
                    $whitespaceToken = Token::createFromValueAndType($this->defaultLineBreak, T_WHITESPACE);
                    $container->insertTokenAfter($iterator->current(), $whitespaceToken);
                } elseif (!$iterator->current()->containsNewline()) {
                    $iterator->current()->setValue($iterator->current()->getValue().$this->defaultLineBreak);
                }
                $iterator->update($token);
            }
            $iterator->next();
        }
        $container->retokenize();
    }
}
