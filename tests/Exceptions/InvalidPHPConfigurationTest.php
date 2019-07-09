<?php

declare(strict_types=1);

namespace BeatLabs\Tests\Exceptions;

use BeatLabs\Exceptions\InvalidPHPConfiguration;
use PHPUnit\Framework\TestCase;

class InvalidPHPConfigurationTest extends TestCase
{
    public function testConstruct(): void
    {
        $exception = new InvalidPHPConfiguration('file.php');
        $this->assertEquals("Configuration loaded from file 'file.php' is invalid", $exception->getMessage());
    }
}