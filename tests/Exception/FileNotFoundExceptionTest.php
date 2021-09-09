<?php

declare(strict_types=1);

namespace BeatLabs\Load\Tests\Exception;

use BeatLabs\Load\Exception\FileNotFoundException;
use PHPUnit\Framework\TestCase;

class FileNotFoundExceptionTest extends TestCase
{
    public function testConstruct(): void
    {
        $exception = new FileNotFoundException('filename');
        $this->assertEquals("File 'filename' does not exist", $exception->getMessage());
    }
}
