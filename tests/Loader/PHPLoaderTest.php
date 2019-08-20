<?php

declare(strict_types=1);

namespace BeatLabs\Tests\Loader;

use BeatLabs\Exception\FileNotFoundException;
use BeatLabs\Exception\InvalidPHPConfiguration;
use BeatLabs\Loader\PHPLoader;
use PHPUnit\Framework\TestCase;

class PHPLoaderTest extends TestCase
{
    public function testConstruct(): void
    {
        $loader = new PHPLoader(__DIR__.'/../data/config.php');
        $this->assertInstanceOf(PHPLoader::class, $loader);
    }

    public function testConstructWithException(): void
    {
        $this->expectException(FileNotFoundException::class);
        $loader = new PHPLoader(__DIR__.'/../data/missing_config.php');
    }

    public function testLoad(): void
    {
        $loader = new PHPLoader(__DIR__.'/../data/config.php');
        $config = $loader->load();
        $this->assertTrue(is_array($config));
    }

    public function testInvalidLoad(): void
    {
        $this->expectException(InvalidPHPConfiguration::class);
        $loader = new PHPLoader(__DIR__.'/../data/invalid_config.php');
        $config = $loader->load();
    }

    public function testNotPHPLoad(): void
    {
        $this->expectException(InvalidPHPConfiguration::class);
        $loader = new PHPLoader(__DIR__.'/../data/wrong_config.ini');
        $config = $loader->load();
    }
}
