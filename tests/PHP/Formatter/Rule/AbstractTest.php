<?php

require_once dirname(__FILE__) . '/../../../TestHelper.php';
require_once 'PHP/Formatter/Rule/Abstract.php';

class PHP_Formatter_Rule_NonAbstract extends PHP_Formatter_Rule_Abstract
{
    public $init = false;

    public function init()
    {
        $this->init = true;
    }

    public function applyRuleToTokens(PHP_Formatter_TokenContainer $tokens)
    {
    }
}

class PHP_Formatter_Rule_AbstractTest extends PHPFormatterTestCase
{
    /**
     * @covers PHP_Formatter_Rule_Abstract
     */
    public function testAbstractClassAndMethods()
    {
        $reflection = new ReflectionClass('PHP_Formatter_Rule_Abstract');
        $this->assertTrue($reflection->isAbstract(), 'Class is not abstract');
        $this->assertTrue($reflection->isSubclassOf('PHP_Formatter_Rule_Interface'), 'Class not implements PHP_Formatter_Rule_Interface');
        $methods = $reflection->getMethods();
    }


    /**
     * @covers PHP_Formatter_Rule_Abstract::__construct
     */
    public function testDefaultConstructor()
    {
        $rule = new PHP_Formatter_Rule_NonAbstract();
        $this->assertEquals(array(), $rule->getOptions(), 'options don\'t match');
    }

    /**
     * @covers PHP_Formatter_Rule_Abstract::__construct
     * @covers PHP_Formatter_Rule_Abstract::init
     */
    public function testConstructorCallsInit()
    {
        $rule = new PHP_Formatter_Rule_NonAbstract();
        $this->assertTrue($rule->init, 'init is not true');
    }

    /**
     * @return array
     */
    public function constructorOptionsProvider()
    {
        $data = array();

        $data[] = array(array());
        $data[] = array(array('baa' => 'foo'));
        $data[] = array(array('baa' => 'foo', 'blub' => 'bla'));

        return $data;
    }

    /**
     * @covers PHP_Formatter_Rule_Abstract::__construct
     * @dataProvider constructorOptionsProvider
     */
    public function testConstructorSetsOptions($options)
    {
        $rule = new PHP_Formatter_Rule_NonAbstract($options);
        $this->assertEquals($options, $rule->getOptions(), 'options don\'t match');
    }
}