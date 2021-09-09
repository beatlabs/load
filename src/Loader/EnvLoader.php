<?php

declare(strict_types=1);

namespace BeatLabs\Loader;

use BeatLabs\Interfaces\Loader;

class EnvLoader implements Loader
{
    /** @var string */
    private $envPrefix;

    public function __construct(string $prefix = "")
    {
        $this->envPrefix = $prefix;
    }

    /**
     * Load configuration
     *
     * @return array
     */
    public function load(): array
    {
        $config = [];
        foreach ($_ENV as $key => $val) {
            if ($this->isValidKey($key)) {
                $config[$key] = $val;
            }
        }

        return $config;
    }

    /**
     * @param string $key
     *
     * @return bool
     */
    public function isValidKey(string $key): bool
    {
        return substr($key, 0, strlen($this->envPrefix)) === $this->envPrefix;
    }
}
