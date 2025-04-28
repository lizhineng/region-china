<?php

declare(strict_types=1);

namespace Zhineng\RegionChina\Build;

use Symfony\Component\VarExporter\VarExporter;

final class TopLevelHandler implements RegionHandler
{
    /**
     * @var int[]
     */
    private array $data = [];

    public function handle(string $code, string $name): void
    {
        if (substr($code, -4) !== '0000') {
            return;
        }

        $this->data[] = (int) $code;
    }

    public function export(string $destination): void
    {
        $exported = VarExporter::export($this->data);

        file_put_contents($destination, '<?php return '.$exported.';');
    }
}
