<?php

namespace Tests\Mock;

use Tests\Mock\ConfigMock;
use PHP\Manipulator\TokenContainer;

/**
 * @group Mock
 * @group Mock\ConfigMock
 */
class ConfigMockTest extends \Tests\TestCase
{

    /**
     * @covers \Tests\Mock\ConfigMock
     */
    public function testMock()
    {
        $configMock = new ConfigMock('foo');
        $this->assertSame('foo', $configMock->data);
        $this->assertCount(4, $configMock->getOptions());
        $configMock->setOption('baa', 'foo');
        $this->assertCount(5, $configMock->getOptions());
        $options = $configMock->getOptions();
        $this->assertArrayHasKey('baa',  $options);
        $this->assertEquals('foo',  $options['baa']);
    }
}