<?php

namespace PHP\Manipulator\TokenManipulator;

use PHP\Manipulator\TokenManipulator;
use PHP\Manipulator\Token;
use PHP\Manipulator\Helper\NewlineDetector;

/**
 * @package PHP\Manipulator
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @link    http://github.com/robo47/php-manipulator
 * @version @pear_package_version@ (@pear_package_git_hash@)
 * @uses    \PHP\Manipulator\Helper\NewlineDetector
 */
class IndentMultilineComment
extends TokenManipulator
{

    /**
     * Manipulate Token
     *
     * @param \PHP\Manipulator\Token $token
     * @param mixed $params
     */
    public function manipulate(Token $token, $params = null)
    {
        $regexNewline = '(\n|\r\n|\r)';
        $indention = $params;
        $value = $token->getValue();
        $lines = preg_split('~' . $regexNewline . '~', $value);

        $helper = new NewlineDetector();
        $newline = $helper->getNewlineFromToken($token);

        $first = true;
        $value = '';

        foreach ($lines as $key => $line) {
            if ($first) {
                $first = false;
            } else {
                $temp = trim($line);
                if (!empty($temp)) {
                    $lines[$key] = $indention . ' ' . $line;
                }
            }
        }

        $token->setValue(implode($newline, $lines));
    }
}