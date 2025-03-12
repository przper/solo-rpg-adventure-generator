<?php

namespace App\Interface;

use App\Service\Map\Core\Map;

interface MapGeneratorInterface
{
    public function create(): Map;
}
