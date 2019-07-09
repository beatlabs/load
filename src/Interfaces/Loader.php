<?php

declare(strict_types=1);

namespace BeatLabs\Interfaces;

interface Loader
{
    /**
     * Load configuration
     *
     * @return array
     */
    public function load(): array;
}