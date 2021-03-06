<?php

namespace PHP\Manipulator\Action;

use PHP\Manipulator\Action;
use PHP\Manipulator\TokenContainer;
use PHP\Manipulator\Helper\NewlineDetector;

/**
 * @package PHP\Manipulator
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @link    http://github.com/robo47/php-manipulator
 * @uses    \PHP\Manipulator\Helper\NewlineDetector
 * @uses    \PHP\Manipulator\TokenConstraint\IsSinglelineComment
 */
class RemoveMultipleEmptyLines
extends Action
{
    public function init()
    {
        if (!$this->hasOption('maxEmptyLines')) {
            $this->setOption('maxEmptyLines', 2);
        }
    }

    /**
     * @param \PHP\Manipulator\TokenContainer $container
     * @param mixed $params
     */
    public function run(TokenContainer $container)
    {
        $newlineDetector = new NewlineDetector();
        $iterator = $container->getIterator();
        $maxEmptyLines = $this->getOption('maxEmptyLines');
        $defaultBreak = $newlineDetector->getNewlineFromContainer($container);

        $previous = null;
        while ($iterator->valid()) {
            $token = $iterator->current();
            if ($this->isType($token, T_WHITESPACE)) {
                if (null !== $previous && $this->evaluateConstraint('IsSinglelineComment', $previous)) {
                    $maxEmptyLines = $this->getOption('maxEmptyLines') - 1;
                } else {
                    $maxEmptyLines = $this->getOption('maxEmptyLines');
                }
                $pattern = '~(((\r\n|\r|\n)([\t| ]{0,})){' . ($maxEmptyLines + 1) . ',}([\t| ]{0,}))~';
                $replace = str_repeat($defaultBreak, $maxEmptyLines) . '\4';
                $value = preg_replace($pattern, $replace, $token->getValue());
                $token->setValue($value);
            }
            $previous = $token;
            $iterator->next();
        }
    }
}
