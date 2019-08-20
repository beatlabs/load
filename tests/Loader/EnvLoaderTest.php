<?php

declare(strict_types=1);

namespace BeatLabs\Tests\Loader;

use BeatLabs\Loader\EnvLoader;
use PHPUnit\Framework\TestCase;

class EnvLoaderTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $_ENV = [
            "PREFIX_KEY1" => "prefix_value1",
            "PREFIX_KEY2" => "prefix_value2",
            "PREFIX_KEY3_SUB1" => "prefix_value3",
            "PREFIX_KEY3_SUB2" => "prefix_value4",
            "KEY1" => "value1",
            "KEY2" => "value2",
            "KEY3_SUB1" => "value3",
            "KEY3_SUB2" => "value4"
        ];
    }

    public function testLoad(): void
    {
        $loader = new EnvLoader();
        $config = $loader->load();
        $this->assertCount(8, $config);
        $this->assertEquals('value1', $config["KEY1"]);
        $this->assertEquals('prefix_value1', $config["PREFIX_KEY1"]);
    }

    public function testLoadWithPrefix(): void
    {
        $loader = new EnvLoader("PREFIX_");
        $config = $loader->load();
        $this->assertCount(4, $config);
        $this->assertEquals('prefix_value1', $config["PREFIX_KEY1"]);
    }
}
