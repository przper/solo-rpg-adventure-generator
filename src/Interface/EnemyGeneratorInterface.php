<?php

namespace App\Interface;

interface EnemyGeneratorInterface
{
    public function generate(): EnemyInterface;

    /**
     * @param int $enemiesCount
     * 
     * @return EnemyInterface[]
     */
    public function generateMany(int $enemiesCount): array;
}