<?php

declare(strict_types=1);

namespace Zhineng\Region;

final class RegionManager
{
    /**
     * @param  array<string, string>  $codeToName
     * @param  array<string, int[]>  $relationships
     * @param  array<int, int[]>  $topLevels
     */
    public function __construct(
        private array $codeToName,
        private array $relationships,
        private array $topLevels
    ) {
        //
    }

    public static function createFromBuiltIn(): static
    {
        return new self(
            require __DIR__.'/../resources/code-to-name.php',
            require __DIR__.'/../resources/relationships.php',
            require __DIR__.'/../resources/top-levels.php'
        );
    }

    public function getName(int $code): string
    {
        return $this->codeToName[$this->groupKey($code)]
            ?? throw new RegionException('The region code does not exist.');
    }

    /**
     * @return int[]
     */
    public function getTopLevelNodes(): array
    {
        return $this->topLevels;
    }

    /**
     * @return int[]
     */
    public function getNodes(int $code): array
    {
        return $this->relationships[$this->groupKey($code)]
            ?? throw new RegionException('Could not find any nodes under the region code.');
    }

    private function groupKey(int $code): string
    {
        return '_'.$code;
    }
}
