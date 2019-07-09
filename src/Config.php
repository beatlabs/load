<?php

declare(strict_types=1);

namespace BeatLabs;

use BeatLabs\Caches\ApcuCache;
use BeatLabs\Interfaces\Cache;
use BeatLabs\Interfaces\Loader;

class Config
{
    const CONFIG_CACHED_FLAG_NAME = 'config_cached';

    /** @var Loader[] */
    private $loaders;

    /** @var Cache */
    private $cache;

    /** @var string */
    private $separator;

    /** @var bool */
    private $cacheLoaded = false;

    public function __construct(array $loaders, Cache $cache = null, string $separator = '.')
    {
        if (is_null($cache)) {
            $cache = new ApcuCache();
        }
        $this->loaders = $loaders;
        $this->cache = $cache;
        $this->separator = $separator;
        $this->cacheLoaded = $this->isConfigLoaded();
        $this->registerSignals();
    }

    /**
     * Load configuration
     */
    public function load(): void
    {
        $config = [];
        foreach ($this->loaders as $loader) {
            $config = array_merge($config, $loader->load());
        }
        $this->cacheConfig($config);
        $this->cache->set(self::CONFIG_CACHED_FLAG_NAME, true);
        $this->cacheLoaded = true;
    }

    /**
     * Clears configuration
     */
    public function clear(): void
    {
        $this->cache->set(self::CONFIG_CACHED_FLAG_NAME, false);
        $this->cacheLoaded = false;
    }

    /**
     * Reloads configuration in cache
     */
    public function reload(): void
    {
        $this->clear();
        $this->load();
    }

    /**
     * Returns if value exists;
     *
     * @param string $key
     *
     * @return bool
     */
    public function has(string $key): bool
    {
        if (!$this->cacheLoaded) {
            return false;
        }
        return $this->cache->has($key);
    }

    /**
     * Cache configuration
     *
     * @param array $config
     */
    public function cacheConfig(array $config): void
    {
        // Save config as is
        foreach ($config as $key => $value) {
            $this->cache->set($key, $value);
        }
        // Save flatten config
        $flat = $this->flattenConfig($config);
        foreach ($flat as $key => $value) {
            $this->cache->set($key, $value);
        }
    }

    /**
     * Returns if configuration is loaded in cache
     *
     * @return bool
     */
    public function isConfigLoaded(): bool
    {
        return $this->cache->get(self::CONFIG_CACHED_FLAG_NAME) === true;
    }

    /**
     * Get configuration value
     *
     * @param string $key
     *
     * @return null|mixed
     */
    public function get(string $key, $default = null)
    {
        if (!$this->cacheLoaded) {
            return $default;
        }
        return $this->cache->get($key);
    }

    /**
     * Create flattened keys for complex configuration values
     *
     * For example:
     * [
     *  'foo' => [
     *      'bar' => 'baz'
     *  ],
     *  'fer' => 'foo'
     * ].
     *
     * will flatten complex key and create the following:
     * [
     *  'foo' => [
     *      'bar' => 'baz'
     *  ],
     *  'fer' => 'foo',
     *  'foo.bar' => 'baz',
     * ]
     *
     * @param array  $arrayIn   The array to collapse
     * @param string $parentKey [optional] A key to use as a prefix for the collapsed entries of the input array
     *
     * @return array
     */
    private function flattenConfig(array $array, string $prefix = ''): array
    {
        $flattenConfig = [];
        foreach ($array as $key => $value) {
            // Remove separator if exists in key
            $key = str_replace($this->separator, '', $key);
            $keyPrefix = ($prefix !== '' ? "$prefix{$this->separator}" : '');
            $flattenConfig[$keyPrefix.$key] = $value;
            if (is_array($value)) {
                $flattenConfig = array_merge($flattenConfig, $this->flattenConfig($value, "$keyPrefix$key"));
            }
        }

        return $flattenConfig;
    }

    /**
     * Register signals for configuration reloading
     */
    private function registerSignals(): void
    {
        pcntl_async_signals(true);
        pcntl_signal(SIGUSR2, function ($signo) {
            $this->reload();
        });
    }
}
