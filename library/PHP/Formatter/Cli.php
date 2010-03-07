<?php

class PHP_Formatter_Cli
{
    /**
     *
     * @return int
     */
    public static function run()
    {
        $statusCode = 0;
        $start = microtime(true);

        $formatter = new PHP_Formatter();


        echo 'Time: ' .  round(microtime(true) - $start, 4) , 's' . PHP_EOL;
        echo 'Memory: ' .  round(microtime(true) - $start, 4) , 's' . PHP_EOL;
        return $statusCode;
    }

    public function addRulesetPath()
    {

    }

    public function addRulePath()
    {

    }

    public function loadRule($rule)
    {

    }

    public function loadRuleset($ruleset)
    {

    }

    public function parseConfig($config, $type)
    {
        
    }
}