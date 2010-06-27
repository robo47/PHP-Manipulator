<?php

namespace Tests\Stub;

use Tests\Stub\ConfigStub;
use PHP\Manipulator\TokenContainer;

/**
 * @group Stub
 * @group Stub\ConfigStub
 */
class ConfigStubTest extends \Tests\TestCase
{

    /**
     * @covers \Tests\Stub\ConfigStub
     */
    public function testStub()
    {
        $configStub = new ConfigStub('foo');
        $this->assertSame('foo', $configStub->data);
        $this->assertCount(4, $configStub->getOptions());
        $configStub->setOption('baa', 'foo');
        $this->assertCount(5, $configStub->getOptions());
        $options = $configStub->getOptions();
        $this->assertArrayHasKey('baa',  $options);
        $this->assertEquals('foo',  $options['baa']);
    }
}