<?php

namespace PHP\Manipulator\TokenManipulator;

use PHP\Manipulator\TokenManipulator;
use PHP\Manipulator\Token;
use PHP\Manipulator\Helper\NewlineDetector;
use ArrayIterator;
use ArrayObject;

/**
 * @package PHP\Manipulator
 * @license http://opensource.org/licenses/bsd-license.php The BSD License
 * @link    http://github.com/robo47/php-manipulator
 * @version @pear_package_version@ (@pear_package_git_hash@)
 * @uses    \PHP\Manipulator\Helper\NewlineDetector
 */
class RemoveLeadingAndTrailingEmptyLinesInPhpdoc
extends TokenManipulator
{

    protected $_keys = array();

    /**
     * @param \PHP\Manipulator\Token $token
     * @param mixed $params
     */
    public function manipulate(Token $token, $params = null)
    {
        $helper = new NewlineDetector();
        $newline = $helper->getNewlineFromToken($token);
        $lines = new ArrayObject(preg_split('~(\r\n|\r|\n)~', $token->getValue()));

        if (count($lines) >= 3) {
            // delete empty lines from begin
            $this->_iterateLines($lines);
            // delete empty lines from end
            $lines = new ArrayObject(array_reverse($lines->getArrayCopy()));
            $this->_iterateLines($lines);
            $token->setValue(implode($newline, array_reverse($lines->getArrayCopy())));
        }
    }

    /**
     * @param \ArrayObject $lines
     */
    protected function _iterateLines(ArrayObject $lines)
    {
        $iter = $lines->getIterator();
        $iter->next();
        $keys = array();
        while($iter->valid()) {
            if (preg_match('~^([\n\r\t\* ]+)$~', $iter->current())) {
                $keys[] = $iter->key();
            } else {
                break;
            }
            $iter->next();
        }
        foreach($keys as $key) {
            unset($lines[$key]);
        }
        $iter->rewind();
    }
}