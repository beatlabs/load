<?php

declare(strict_types=1);

namespace MOP\Interfaces;

interface Loader
{
    /**
     * Load configuration
     *
     * @return array
     */
    public function load(): array;
}