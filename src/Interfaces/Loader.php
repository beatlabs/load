<?php

declare(strict_types=1);

namespace BeatLabs\Load\Interfaces;

interface Loader
{
    /**
     * Load configuration
     *
     * @return array
     */
    public function load(): array;
}
