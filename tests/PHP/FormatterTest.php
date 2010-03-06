<?php

require_once dirname(__FILE__) . '/../TestHelper.php';
require_once 'PHP/Formatter.php';

class PHP_FormatterTest extends PHPFormatterTestCase
{
    /**
     * @covers PHP_Formatter::__construct
     */
    public function testDefaultConstruct()
    {
        $formatter = new PHP_Formatter();
        $this->assertEquals(array(), $formatter->getRules());
    }
}