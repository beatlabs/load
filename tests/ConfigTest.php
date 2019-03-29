<?php

declare(strict_types=1);

namespace MOP\Tests;

use MOP\Config;
use MOP\Interfaces\Loader;
use PHPUnit\Framework\TestCase;

class ConfigTest extends TestCase
{
    /** @var Config */
    private $config;

    public function setUp(): void
    {
        parent::setUp();
        $loader = $this->getMockBuilder(Loader::class)
            ->setMethods([
                'load'
            ])
            ->getMockForAbstractClass();
        $loader->expects($this->any())
            ->method('load')
            ->willReturn([
                'foo' => [
                    'bar' => 'baz',
                ],
                'fer' => 'foo',
                'fooo' => [
                    'barr' => [
                        'bazz' => [
                            'boo' => 'hoo'
                        ]
                    ]
                ]
            ]);
        $this->config = new Config($loader);
    }

    public function tearDown(): void
    {
        parent::tearDown();
        apcu_clear_cache();
    }

    public function testLoad(): void
    {
        $this->assertFalse($this->config->isConfigLoaded());
        $this->config->load();
        $this->assertTrue($this->config->isConfigLoaded());
    }

    public function testClear(): void
    {
        $this->config->load();
        $this->assertTrue($this->config->isConfigLoaded());
        $this->config->clear();
        $this->assertFalse($this->config->isConfigLoaded());
    }

    public function testReload(): void
    {
        $this->config->load();
        $this->assertTrue($this->config->isConfigLoaded());
        $this->config->reload();
        $this->assertTrue($this->config->isConfigLoaded());
    }

    public function testGet(): void
    {
        $this->config->load();
        $this->assertEquals('hoo', $this->config->get('fooo/barr/bazz/boo'));
        $this->assertEquals('baz', $this->config->get('foo/bar'));
        $this->assertEquals('foo', $this->config->get('fer'));
        $this->assertEquals([
            'barr' => [
                'bazz' => [
                    'boo' => 'hoo'
                ]
            ]
        ], $this->config->get('fooo'));
    }

    public function testGetMissing(): void
    {
        $this->config->load();
        $this->assertNull($this->config->get('missing'));
    }

    public function testGetWithClearedCache(): void
    {
        $this->config->load();
        $this->assertEquals('hoo', $this->config->get('fooo/barr/bazz/boo'));
        $this->config->clear();
        $this->assertNull($this->config->get('fooo/barr/bazz/boo'));
    }

    public function testHas(): void
    {
        $this->assertFalse($this->config->has('fer'));
        $this->config->load();
        $this->assertTrue($this->config->has('fer'));
    }

    public function testHasWithClearedCache(): void
    {
        $this->assertFalse($this->config->has('fer'));
        $this->config->load();
        $this->assertTrue($this->config->has('fer'));
        $this->config->clear();
        $this->assertFalse($this->config->has('fer'));
    }
}