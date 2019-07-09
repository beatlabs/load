<?php

declare(strict_types=1);

namespace BeatLabs\Tests\Caches;

use BeatLabs\Caches\ApcuCache;
use PHPUnit\Framework\TestCase;

class ApcuCacheTest extends TestCase
{
    /** @var ApcuCache */
    private $cache;

    public function setUp(): void
    {
        parent::setUp();
        $this->cache = new ApcuCache();
    }

    public function tearDown(): void
    {
        parent::tearDown();
        apcu_clear_cache();
    }

    public function testSetGet(): void
    {
        $this->cache->set('key', 'val');
        $this->assertEquals('val', $this->cache->get('key'));
    }

    public function testHas(): void
    {
        $this->assertFalse($this->cache->has('key'));
        $this->cache->set('key', 'val');
        $this->assertTrue($this->cache->has('key'));
    }

    public function testDelete(): void
    {
        $this->cache->set('key', 'val');
        $this->assertTrue($this->cache->has('key'));
        $this->cache->delete('key');
        $this->assertFalse($this->cache->has('key'));
    }
}