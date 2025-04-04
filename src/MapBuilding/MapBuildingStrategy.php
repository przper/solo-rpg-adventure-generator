<?php

namespace App\MapBuilding;

use App\Core\Map\DungeonLength;
use App\Core\Map\Map;
use App\Core\Map\MapType;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag('map_building.strategy')]
interface MapBuildingStrategy
{
    public function supports(): MapType;

    public function create(DungeonLength $dungeonLength): Map;
}
