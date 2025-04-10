<?php

namespace App\EncountersPlanning\Shadowdark;

use App\Core\Encounter\Treasure;

class TreasureGenerator
{
    public function getRandomTreasure(int $level): Treasure
    {
        return $this->rollOnFirstTable();
    }

    /** Table for level 0 - 3 */
    private function rollOnFirstTable(): Treasure
    {
        $rolledTreasure = match (rand(1, 100)) {
            1 => 'Bent tin fork (1 cp)',
            2, 3 => 'Muddy torch (2 cp)',
            4, 5 => 'Bag of smooth pebbles (2 cp)',
            6, 7 => '10 cp in a greasy pouch',
            8, 9 => 'Rusty lantern with shattered glass (1 gp)',
            10, 11 => 'Silver tooth (1 gp)',
            12, 13 => 'Dull dagger (1 gp)',
            14, 15 => 'Two empty glass vials (6 gp)',
            16, 17 => '60 sp in a rotten boot',
            18, 19 => 'Cracked, handheld mirror (8 gp)',
            20, 21 => 'Chipped greataxe (9 gp)',
            22, 23 => '10 gp in a moldy, wood box',
            24, 25 => 'Chip of an emerald (10 gp)',
            26, 27 => 'Longbow and bundle of 40 arrows (10 gp)',
            28, 29 => 'Dusty, leather armor dyed black (10 gp)',
            30, 31 => 'Scuffed, heavy shield (10 gp)',
            32, 33 => 'Simple, well-made bastard sword (10 gp)',
            34, 35 => '12 gp in the pocket of a ripped cloak',
            36, 37 => 'Wavy-bladed greatsword (12 gp)',
            38, 39 => 'Pair of elf-forged shortswords (14 gp)',
            40, 41 => 'Golden bowl (15 gp)',
            42, 43 => 'Obsidian statuette of Shune the Vile (15 gp)',
            44, 45 => 'Undersized pearl (20 gp)',
            46, 47 => 'Jade-and-gold scarab pin (20 gp)',
            48, 49 => 'Bag of 10 silver spikes (2 gp each)',
            50, 51, 52, 53 => 'Mithral locket with a painting of a halfling (20 gp)',
            54, 55 => 'Two finely forged dwarven shields (20 gp)',
            56, 57 => 'Pair of silvered daggers (10 gp each)',
            58, 59 => 'Copper-and-gold mead tankard (20 gp)',
            60, 61 => 'Bundle of five red dragon scales (5 gp each)',
            62, 63 => 'Light, warm cloak woven of spidersilk (25 gp)',
            64, 65 => 'Fine set of ivory game pieces (25 gp)',
            66, 67 => 'Half-finished suit of chainmail (30 gp)',
            68, 69 => 'Matched trio of warhammers (10 gp each)',
            70, 71 => 'Fragment of a sapphire (30 gp)',
            72, 73 => 'Set of silk slippers and a robe (35 gp)',
            74, 75 => 'Silver-and-gold circlet (40 gp)',
            76, 77 => 'Radiant, polished pearl (40 gp)',
            78, 79 => 'Mithral shield etched with soaring dragons (40 gp)',
            80, 81 => 'Gold monkey idol with a ruby gripped in its teeth (60 gp)',
            82, 83 => 'Fine suit of chainmail (60 gp)',
            84, 85 => 'Cracked emerald (60 gp)',
            86, 87 => 'Two lustrous pearls (40 gp each)',
            88, 89 => '1st-tier spell scroll (80 gp)',
            90, 91 => 'Potion of Invisibility (80 gp)',
            92, 93 => 'Magic wand, 2nd-tier spell (100 gp)',
            94, 95 => 'Egg of The Cockatrice (100 gp)',
            96, 97 => '+1 armor (benefit, curse) (150 gp)',
            98, 99 => 'Bag of Holding (virtue, flaw) (150 gp)',
            100 => '+1 magic weapon (benefit) (200 gp)',
        };

        return new Treasure($rolledTreasure);
    }
}
