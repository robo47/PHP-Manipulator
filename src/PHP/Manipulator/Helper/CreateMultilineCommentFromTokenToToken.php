<?php

namespace PHP\Manipulator\Helper;

use PHP\Manipulator\AHelper;
use PHP\Manipulator\Exception\HelperException;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;

class CreateMultilineCommentFromTokenToToken extends AHelper
{
    /**
     * @param TokenContainer $container
     * @param Token          $from
     * @param Token          $to
     */
    public function run(TokenContainer $container, Token $from, Token $to)
    {
        if (!$container->contains($from)) {
            $message = "Element 'from' not found in container";
            throw new HelperException($message, HelperException::FROM_NOT_FOUND_IN_CONTAINER);
        }

        if (!$container->contains($to)) {
            $message = "Element 'to' not found in container";
            throw new HelperException($message, HelperException::TO_NOT_FOUND_IN_CONTAINER);
        }

        // @todo lets try to moveoffset usage to only class-internal use
        $startOffset = $container->getOffsetByToken($from);
        $endOffset   = $container->getOffsetByToken($to);

        if ($startOffset > $endOffset) {
            $message = sprintf('StartOffset (%u) is behind EndOffset (%u)', $startOffset, $endOffset);
            throw new HelperException($message, HelperException::START_OFFSET_BEHIND_END_OFFSET);
        }

        $tokens = $this->getTokensFromStartToEnd($container, $from, $to);

        $value = $this->mergeTokenValuesIntoString($tokens);

        $value = '/*'.$value.'*/';

        $commentToken = Token::createFromValueAndType($value, T_COMMENT);

        $container->insertAtOffset($startOffset, $commentToken);
        $container->removeTokens($tokens);
    }

    /**
     * @param TokenContainer $container
     * @param Token          $startToken
     * @param Token          $endToken
     *
     * @return array
     */
    private function getTokensFromStartToEnd(TokenContainer $container, Token $startToken, Token $endToken)
    {
        $iterator = $container->getIterator();
        $iterator->seekToToken($startToken);

        $tokens = [];

        while ($iterator->valid()) {
            $tokens[] = $iterator->current();
            if ($iterator->current() === $endToken) {
                break;
            }
            $iterator->next();
        }

        return $tokens;
    }

    /**
     * @param Token[] $tokens
     *
     * @return string
     */
    private function mergeTokenValuesIntoString(array $tokens)
    {
        $value = '';
        foreach ($tokens as $token) {
            if (!$token->isMultilineComment()) {
                $value .= $token->getValue();
            }
        }
        $value = str_replace('*/', '', $value);

        return $value;
    }
}
