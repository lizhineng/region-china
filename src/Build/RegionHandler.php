<?php

declare(strict_types=1);

namespace Zhineng\Region\Build;

interface RegionHandler
{
    /**
     * Process the region code and name pair.
     */
    public function handle(string $code, string $name): void;
}
