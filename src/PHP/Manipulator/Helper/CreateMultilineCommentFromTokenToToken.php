<?php

namespace PHP\Manipulator\Helper;

use PHP\Manipulator\AHelper;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;
use PHP\Manipulator\TokenConstraint\IsMultilineComment;

/**
 * @package PHP\Manipulator
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @link    http://github.com/robo47/php-manipulator
 * @version @pear_package_version@ (@pear_package_git_hash@)
 */
class CreateMultilineCommentFromTokenToToken
extends AHelper
{

    /**
     * @param \PHP\Manipulator\TokenContainer $container
     * @param mixed $params
     */
    public function run(TokenContainer $container, Token $from, Token $to)
    {
        if (!$container->contains($from)) {
            $message = "element 'from' not found in \$container";
            throw new \Exception($message);
        }


        if (!$container->contains($to)) {
            $message = "element 'to' not found in \$container";
            throw new \Exception($message);
        }

        $startOffset = $container->getOffsetByToken($from);

        $endOffset = $container->getOffsetByToken($to);

        if ($startOffset > $endOffset) {
            $message = 'startOffset is behind endOffset';
            throw new \Exception($message);
        }

        $tokens = $this->_getTokensFromStartToEnd($container, $startOffset, $endOffset);

        $value = $this->_mergeTokenValuesIntoString($tokens);

        $value = '/*' . $value . '*/';

        $commentToken = new Token($value, T_COMMENT);

        $container->insertAtOffset($startOffset, $commentToken);
        $container->removeTokens($tokens);
    }

    /**
     * @param \PHP\Manipulator\TokenContainer $container
     * @param integer $startOffset
     * @param integer $endOffset
     * @return array
     */
    protected function _getTokensFromStartToEnd(TokenContainer $container, $startOffset, $endOffset)
    {
        $iterator = $container->getIterator();
        $iterator->seek($startOffset);

        $tokens = array();
        while ($iterator->valid()) {
            $tokens[] = $iterator->current();
            if ($iterator->key() === $endOffset) {
                break;
            }
            $iterator->next();
        }
        return $tokens;
    }

    /**
     * @param array $tokens
     * @return string
     */
    protected function _mergeTokenValuesIntoString(array $tokens)
    {
        $value = '';
        foreach ($tokens as $token) {
            if (!$this->evaluateConstraint('IsMultilineComment', $token)) {
                $value .= $token->getValue();
            }
        }
        $value = str_replace('*/', '', $value);
        return $value;
    }
}