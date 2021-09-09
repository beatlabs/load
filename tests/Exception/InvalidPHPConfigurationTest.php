<?php

declare(strict_types=1);

namespace BeatLabs\Load\Tests\Exception;

use BeatLabs\Load\Exception\InvalidPHPConfiguration;
use PHPUnit\Framework\TestCase;

class InvalidPHPConfigurationTest extends TestCase
{
    public function testConstruct(): void
    {
        $exception = new InvalidPHPConfiguration('file.php');
        $this->assertEquals("Configuration loaded from file 'file.php' is invalid", $exception->getMessage());
    }
}
