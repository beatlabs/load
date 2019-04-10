<?php

declare(strict_types=1);

namespace MOP\Loaders;

use MOP\Interfaces\Loader;

class EnvLoader implements Loader
{
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
        foreach($_ENV as $key => $val) {
            if ($this->isValidKey($key)) {
                $config[$key] = $val;
            }
        }

        return $config;
    }

    public function isValidKey(string $key): bool
    {
        return substr($key, 0, strlen($this->envPrefix)) === $this->envPrefix;
    }
}