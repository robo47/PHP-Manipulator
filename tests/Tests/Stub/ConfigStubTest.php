<?php

namespace Tests\Stub;

use Tests\TestCase;

/**
 * @covers Tests\Stub\ConfigStub
 */
class ConfigStubTest extends TestCase
{
    public function testStub()
    {
        $configStub = new ConfigStub('foo');
        $this->assertSame('foo', $configStub->data);
        $this->assertCount(4, $configStub->getOptions());
        $configStub->setOption('baa', 'foo');
        $this->assertCount(5, $configStub->getOptions());
        $options = $configStub->getOptions();
        $this->assertArrayHasKey('baa', $options);
        $this->assertSame('foo', $options['baa']);
    }
}
