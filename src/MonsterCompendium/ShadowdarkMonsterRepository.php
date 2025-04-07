<?php

namespace App\MonsterCompendium;

use App\EncountersPlanning\TTRPGSystem;
use App\MonsterCompendium\Entity\Monster;
use App\MonsterCompendium\Entity\ShadowdarkMonster;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Monster>
 *
 * @method Monster|null find($id, $lockMode = null, $lockVersion = null)
 * @method Monster|null findOneBy(array $criteria, array $orderBy = null)
 * @method Monster[]    findAll()
 * @method Monster[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ShadowdarkMonsterRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ShadowdarkMonster::class);
    }

    public function supports(): TTRPGSystem
    {
        return TTRPGSystem::Shadowdark;
    }
}
