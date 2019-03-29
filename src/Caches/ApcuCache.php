<?php

declare(strict_types=1);

namespace MOP\Caches;

use MOP\Interfaces\Cache;

class ApcuCache implements Cache
{

    /**
     * Sets or updates a value in cache
     *
     * @param string $key
     * @param $value
     */
    public function set(string $key, $value): void
    {
        apcu_store($key, $value);
    }

    /**
     * Return value of key if exists, else returns null
     *
     * @param string $key
     *
     * @return null|mixed
     */
    public function get(string $key)
    {
        $value = apcu_fetch($key);
        if ($value === false) {
            return null;
        }

        return $value;
    }

    /**
     * Returns if cache has specific key
     *
     * @param string $key
     *
     * @return bool
     */
    public function has(string $key): bool
    {
        return apcu_exists($key);
    }

    /**
     * Delete value from cache
     *
     * @param string $key
     */
    public function delete(string $key): void
    {
        apcu_delete($key);
    }
}