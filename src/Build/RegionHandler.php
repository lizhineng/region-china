<?php

declare(strict_types=1);

namespace Zhineng\RegionChina\Build;

interface RegionHandler
{
    /**
     * Process the region code and name pair.
     */
    public function handle(string $code, string $name): void;
}
