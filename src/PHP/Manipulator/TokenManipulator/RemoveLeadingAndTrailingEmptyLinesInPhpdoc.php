<?php

namespace PHP\Manipulator\TokenManipulator;

use ArrayObject;
use PHP\Manipulator\Helper\NewlineDetector;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenManipulator;

class RemoveLeadingAndTrailingEmptyLinesInPhpdoc extends TokenManipulator
{
    public function manipulate(Token $token, $params = null)
    {
        $helper  = new NewlineDetector();
        $newline = $helper->getNewlineFromToken($token);
        $lines   = new ArrayObject(preg_split('~(\r\n|\r|\n)~', $token->getValue()));

        if (count($lines) >= 3) {
            // delete empty lines from begin
            $this->iterateLines($lines);
            // delete empty lines from end
            $lines = new ArrayObject(array_reverse($lines->getArrayCopy()));
            $this->iterateLines($lines);
            $token->setValue(implode($newline, array_reverse($lines->getArrayCopy())));
        }
    }

    /**
     * @param ArrayObject $lines
     */
    private function iterateLines(ArrayObject $lines)
    {
        $iter = $lines->getIterator();
        $iter->next();
        $keys = [];
        while ($iter->valid()) {
            if (preg_match('~^([\n\r\t\* ]+)$~', $iter->current())) {
                $keys[] = $iter->key();
            } else {
                break;
            }
            $iter->next();
        }
        foreach ($keys as $key) {
            unset($lines[$key]);
        }
        $iter->rewind();
    }
}
