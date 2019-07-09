<?php

declare(strict_types=1);

namespace BeatLabs\Interfaces;

interface Cache
{
    /**
     * Sets or updates a value in cache
     *
     * @param string $key
     * @param $value
     */
    public function set(string $key, $value): void;

    /**
     * Return value of key if exists, else returns null
     *
     * @param string $key
     *
     * @return null|mixed
     */
    public function get(string $key);

    /**
     * Returns if cache has specific key
     *
     * @param string $key
     *
     * @return bool
     */
    public function has(string $key): bool;

    /**
     * Delete value from cache
     *
     * @param string $key
     */
    public function delete(string $key): void;
}
