<?php

declare(strict_types=1);

namespace Zhineng\RegionChina\Build;

use Symfony\Component\VarExporter\VarExporter;

final class CodeToNameHandler implements RegionHandler
{
    /**
     * @var array<int, string>
     */
    private array $data = [];

    public function handle(string $code, string $name): void
    {
        $this->data['_'.$code] = $name;
    }

    public function export(string $destination): void
    {
        $exported = VarExporter::export($this->data);

        file_put_contents($destination, '<?php return '.$exported.';');
    }
}
