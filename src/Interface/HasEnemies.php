<?php

namespace App\Interface;

interface HasEnemies
{
    /** @return EnemyInterface[] */
    public function getEnemies(): array;
}