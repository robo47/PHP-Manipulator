<?php

namespace PHP\Manipulator\Action;

use PHP\Manipulator\Action;
use PHP\Manipulator\TokenContainer;
use PHP\Manipulator\Helper\NewlineDetector;

/**
 * @package PHP\Manipulator
 * @license http://opensource.org/licenses/bsd-license.php The BSD License
 * @link    http://github.com/robo47/php-manipulator
 * @version @pear_package_version@ (@pear_package_git_hash@)
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