<?php

declare(strict_types=1);

namespace BeatLabs\Exception;

use Throwable;

class InvalidPHPConfiguration extends \RuntimeException
{
    public function __construct(string $file, int $code = 0, Throwable $previous = null)
    {
        $message = sprintf("Configuration loaded from file '%s' is invalid", $file);
        parent::__construct($message, $code, $previous);
    }
}
