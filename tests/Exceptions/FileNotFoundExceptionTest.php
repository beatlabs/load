<?php

declare(strict_types=1);

namespace MOP\Tests\Exceptions;

use MOP\Exceptions\FileNotFoundException;
use PHPUnit\Framework\TestCase;

class FileNotFoundExceptionTest extends TestCase
{
    public function testConstruct(): void
    {
        $exception = new FileNotFoundException('filename');
        $this->assertEquals("File 'filename' does not exist", $exception->getMessage());
    }
}