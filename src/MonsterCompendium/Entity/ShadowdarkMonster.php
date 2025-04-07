<?php

namespace App\MonsterCompendium\Entity;

use App\MonsterCompendium\ShadowdarkMonsterRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ShadowdarkMonsterRepository::class)]
#[ORM\Table(name: 'monster_shadowdark')]
class ShadowdarkMonster extends Monster
{

}
