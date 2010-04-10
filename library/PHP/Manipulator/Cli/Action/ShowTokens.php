<?php

namespace PHP\Manipulator\Cli\Action;

use PHP\Manipulator\Cli\Action;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;

class ShowTokens extends Action
{
    
    public function run()
    {
        $output = $this->getCli()->getConsoleOutput();
        $file = $this->_param[1];
        $file = realpath($file);
        if (!\file_exists($file) || !\is_readable($file)) {
            throw new \Exception('Unable to open file: ' . $file);
        }

        $code = \file_get_contents($file);
        $container = new TokenContainer($code);

        $size = filesize($file);

        $output->outputLine('Filesize: ' . $size . 'bytes');
        $output->outputLine('Filesize: ' . $size . 'bytes');
        echo 'Tokens: ' . count($container) . PHP_EOL . PHP_EOL;

        foreach ($container as $number => $token) {
            echo $number . ') ' . $this->printToken($token) . PHP_EOL;
        }
    }

    /**
     * @param string $value
     * @return string
     */
    public function transformTokenValue($value)
    {
        $value = str_replace(' ', '.', $value);
        $value = str_replace("\t", '\t', $value);
        $value = str_replace("\r", '\r' . "\r", $value);
        $value = str_replace("\n", '\n' . "\n", $value);

        return str_replace('', '', $value);
    }

    /**
     * @param Token $token
     */
    public function printToken(Token $token)
    {
        $name = \token_name($token->getType());
        $value = $this->transformTokenValue($token->getValue());
        return $name . ':' . $value;
    }

    /**
     *
     * @return array
     */
    public function getConsoleOption()
    {
        return array (
            new \ezcConsoleOption(
            'sh',
            'showtokens',
            \ezcConsoleInput::TYPE_NONE,
            null,
            false,
            'Prints out the Tokens of a file',
            '-- LONG --',
            array(),
            array(),
            true,
            false,
            true
            )
        );
    }
}