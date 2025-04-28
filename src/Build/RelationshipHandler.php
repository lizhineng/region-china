<?php

declare(strict_types=1);

namespace Zhineng\Region\Build;

use Symfony\Component\VarExporter\VarExporter;

final class RelationshipHandler implements RegionHandler
{
    /**
     * @var array<string, int[]>
     */
    private array $data = [];

    public function handle(string $code, string $name): void
    {
        if ($this->belongsToGroup($code)) {
            $this->data[$this->groupKey($code)] = [];
        }

        if ($this->isProvince($code)) {
            return;
        }

        $group = $this->resolveGroupFor($code);

        $this->data[$group][] = (int) $code;
    }

    private function belongsToGroup(string $code): bool
    {
        return $this->isProvince($code)
            || $this->isCity($code);
    }

    private function isProvince(string $code): bool
    {
        return substr($code, -4) === '0000';
    }

    private function groupKey(string $code): string
    {
        return '_'.$code;
    }

    private function isCity(string $code): bool
    {
        return substr($code, -2) === '00';
    }

    private function isDistrict(string $code): bool
    {
        return substr($code, -2) !== '00';
    }

    private function resolveGroupFor(string $code): string
    {
        if ($this->isDistrict($code)) {
            return $this->resolveGroupForDistrict($code);
        }

        return $this->resolveGroupForCity($code);
    }

    private function resolveGroupForDistrict(string $code): string
    {
        if ($this->hasCityGroup($code)) {
            return $this->groupKey($this->cityKey($code));
        }

        if ($this->hasProvinceGroup($code)) {
            return $this->groupKey($this->provinceKey($code));
        }

        throw new \InvalidArgumentException(sprintf(
            'Could not resolve the group for key [%s].', $code
        ));
    }

    private function resolveGroupForCity(string $code): string
    {
        if ($this->hasProvinceGroup($code)) {
            return $this->groupKey($this->provinceKey($code));
        }

        throw new \InvalidArgumentException(sprintf(
            'Could not resolve the group for key [%s].', $code
        ));
    }

    private function hasCityGroup(string $code): bool
    {
        $key = $this->groupKey($this->cityKey($code));

        return isset($this->data[$key]);
    }

    private function cityKey(string $code): string
    {
        return substr($code, 0, 4).'00';
    }

    private function hasProvinceGroup(string $code): bool
    {
        $key = $this->groupKey($this->provinceKey($code));

        return isset($this->data[$key]);
    }

    private function provinceKey(string $code): string
    {
        return substr($code, 0, 2).'0000';
    }

    public function export(string $destination): void
    {
        $exported = VarExporter::export($this->data);

        file_put_contents($destination, '<?php return '.$exported.';');
    }
}
