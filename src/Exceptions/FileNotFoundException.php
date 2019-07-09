<?php

declare(strict_types=1);

namespace BeatLabs\Exceptions;

use Throwable;

class FileNotFoundException extends \RuntimeException
{
    public function __construct(string $file, int $code = 0, Throwable $previous = null)
    {
        $message = sprintf("File '%s' does not exist", $file);
        parent::__construct($message, $code, $previous);
    }
}