<?php

declare(strict_types=1);

namespace Zhineng\Region\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Zhineng\Region\RegionException;
use Zhineng\Region\RegionManager;

#[CoversClass(RegionManager::class)]
final class RegionManagerTest extends TestCase
{
    public function test_name_resolution(): void
    {
        $manager = new RegionManager(
            ['_110000' => '北京市'],
            [],
            []
        );
        $this->assertSame('北京市', $manager->getName(110000));
    }

    public function test_name_resolution_with_non_existent_code(): void
    {
        $this->expectException(RegionException::class);
        $this->expectExceptionMessage('The region code does not exist.');
        $manager = new RegionManager([], [], []);
        $manager->getName(110000);
    }

    public function test_top_level_nodes_resolution(): void
    {
        $manager = new RegionManager([], [], [1, 2, 3]);
        $this->assertSame([1, 2, 3], $manager->getTopLevelNodes());
    }

    public function test_nodes_resolution(): void
    {
        $manager = new RegionManager([], ['_1' => [2]], []);
        $this->assertSame([2], $manager->getNodes(1));
    }

    public function test_nodes_resolution_with_non_existent_code(): void
    {
        $this->expectException(RegionException::class);
        $this->expectExceptionMessage('Could not find any nodes under the region code.');
        $manager = new RegionManager([], [], []);
        $manager->getNodes(1);
    }
}
