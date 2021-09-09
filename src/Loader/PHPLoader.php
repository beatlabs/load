<?php

declare(strict_types=1);

namespace BeatLabs\Loader;

use BeatLabs\Exception\FileNotFoundException;
use BeatLabs\Exception\InvalidPHPConfiguration;
use BeatLabs\Interfaces\Loader;

class PHPLoader implements Loader
{
    /** @var string */
    private $file;

    public function __construct(string $file)
    {
        if (!file_exists($file)) {
            throw new FileNotFoundException($file);
        }
        $this->file = $file;
    }

    /**
     * Load configuration
     *
     * @return array
     */
    public function load(): array
    {
        $config = require_once($this->file);
        if (!is_array($config)) {
            throw new InvalidPHPConfiguration($this->file);
        }

        return $config;
    }
}
