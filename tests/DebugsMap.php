<?php

namespace App\Tests;

use App\Core\Map\Map;

trait DebugsMap
{
    public function debugMap(Map $map): string
    {
        $result = "";

        foreach ($map->tiles as $column) {
            foreach ($column as $tile) {
                $result .= is_null($tile) ? "#" : strtoupper(substr($tile->type->name, 0, 1));
            }
            $result .= "\n";
        }

        return $result;
    }
}
