INSERT INTO monster_shadowdark (
    id,
    name,
    challenge_rating,
    experience_points,
    total_hit_points,
    armor_class,
    attacks,
    specials,
    description,
    attributes
) VALUES
-- Aboleth
(
    gen_random_uuid(),
    'Aboleth',
    8.0,
    0,
    '39',
    16,
    json_build_array('2 tentacle (near) +5 (1d8 + curse)', '1 tail +5 (3d6)'),
    json_build_array('Curse: DC 15 CON or target gains a magical curse, turning into a deep one over 2d10 days', 
                     'Enslave: In place of attacks, one creature within far DC 15 WIS or aboleth controls for 1d4 rounds', 
                     'Telepathic: Read the thoughts of all creatures within far'),
    'Enormous, antediluvian catfish covered in slime and tentacles. They hate all intelligent beings.',
    '{"strength": 4, "dexterity": -1, "constitution": 3, "intelligence": 4, "wisdom": 2, "charisma": 2, "alignment": "C", "movement": "near (swim)"}'
),

-- Ankheg
(
    gen_random_uuid(),
    'Ankheg',
    3.0,
    0,
    '14',
    14,
    json_build_array('1 bite +4 (1d6)', '1 acid spray (near) +4 (2d6)'),
    json_build_array(),
    'Horse-sized, rust-brown insects. They burrow vast, underground warrens into the bedrock.',
    '{"strength": 2, "dexterity": 2, "constitution": 1, "intelligence": -2, "wisdom": 1, "charisma": -2, "alignment": "N", "movement": "near (burrow)"}'
),

-- Apprentice
(
    gen_random_uuid(),
    'Apprentice',
    1.0,
    0,
    '3',
    11,
    json_build_array('1 dagger (close/near) +1 (1d4)', '1 spell +2'),
    json_build_array('Beguile (INT Spell): DC 11. Focus. One target in near of LV 2 or less is stupefied for the duration.', 'Magic Bolt (INT Spell): DC 11. 1d4 damage to one target within far.'),
    'A cloaked magician with a thin, freshly bound spellbook.',
    '{"strength": -1, "dexterity": 1, "constitution": -1, "intelligence": 2, "wisdom": 0, "charisma": 0, "alignment": "N", "movement": "near"}'
),

-- Badger
(
    gen_random_uuid(),
    'Badger',
    1.0,
    0,
    '5',
    11,
    json_build_array('2 claw +2 (1d4)'),
    json_build_array('Rage: 1/day, immune to morale checks, +1d4 damage (3 rounds).'),
    'Fierce, clawed burrowers with black-and-white face stripes.',
    '{"strength": 2, "dexterity": 0, "constitution": 1, "intelligence": -3, "wisdom": 1, "charisma": -2, "alignment": "N", "movement": "near (burrow)"}'
),

-- Bandit
(
    gen_random_uuid(),
    'Bandit',
    1.0,
    0,
    '4',
    13,
    json_build_array('1 club +1 (1d4)', '1 shortbow (far) +0 (1d4)'),
    json_build_array('Ambush: Deal an extra die of damage when undetected'),
    'Hard-bitten rogue in tattered leathers and a hooded cloak.',
    '{"strength": 1, "dexterity": 0, "constitution": 0, "intelligence": -1, "wisdom": 0, "charisma": -1, "alignment": "C", "movement": "near"}'
),

-- Giant Bat
(
    gen_random_uuid(),
    'Bat, Giant',
    2.0,
    0,
    '9',
    12,
    json_build_array('1 bite +2 (1d6)'),
    json_build_array(),
    'Leathery, eagle-sized mammal with a taste for flesh.',
    '{"strength": -1, "dexterity": 2, "constitution": 0, "intelligence": -3, "wisdom": 1, "charisma": -3, "alignment": "N", "movement": "near (fly)"}'
),

-- Bat Swarm
(
    gen_random_uuid(),
    'Bat, Swarm',
    4.0,
    0,
    '18',
    12,
    json_build_array('3 bite +2 (1d6)'),
    json_build_array(),
    'A whirling cloud of screeching, bloodthirsty bats.',
    '{"strength": -3, "dexterity": 2, "constitution": 0, "intelligence": -3, "wisdom": 1, "charisma": -3, "alignment": "N", "movement": "near (fly)"}'
),

-- Berserker
(
    gen_random_uuid(),
    'Berserker',
    2.0,
    0,
    '10',
    12,
    json_build_array('1 greataxe +2 (1d10)', '1 spear (close/near) +2 (1d6)'),
    json_build_array('Rage: 1/day, immune to morale checks, +1d4 damage (3 rounds).'),
    'Howling, battleraging warriors.',
    '{"strength": 2, "dexterity": 1, "constitution": 1, "intelligence": 0, "wisdom": 1, "charisma": 0, "alignment": "N", "movement": "near"}'
),

-- Boar
(
    gen_random_uuid(),
    'Boar',
    3.0,
    0,
    '14',
    12,
    json_build_array('2 tusk +3 (1d6)'),
    json_build_array('Gore: Deals an extra die of damage if it hits the same target with both tusks.'),
    'Ornery wild pig with bristly, russet hair and yellowed tusks.',
    '{"strength": 3, "dexterity": 0, "constitution": 1, "intelligence": -2, "wisdom": 1, "charisma": -2, "alignment": "N", "movement": "near"}'
),

-- Bugbear
(
    gen_random_uuid(),
    'Bugbear',
    3.0,
    0,
    '14',
    13,
    json_build_array('2 spiked mace +3 (1d6)'),
    json_build_array('Stealthy: ADV on checks to sneak and hide.'),
    'Brutish, bat-eared goblinoids covered in brown fur.',
    '{"strength": 3, "dexterity": 0, "constitution": 1, "intelligence": -1, "wisdom": 0, "charisma": -2, "alignment": "C", "movement": "near"}'
),

-- Cultist
(
    gen_random_uuid(),
    'Cultist',
    2.0,
    0,
    '9',
    14,
    json_build_array('1 longsword +1 (1d8)', '1 spell +2'),
    json_build_array('Fearless: Immune to morale checks.', 'Deathtouch (WIS Spell): DC 12. 2d4 damage to one creature within close.'),
    'A cloaked, wild-eyed zealot chanting the guttural prayers of a dark god.',
    '{"strength": 1, "dexterity": -1, "constitution": 0, "intelligence": -1, "wisdom": 2, "charisma": 0, "alignment": "C", "movement": "near"}'
),

-- Darkmantle
(
    gen_random_uuid(),
    'Darkmantle',
    1.0,
    0,
    '4',
    13,
    json_build_array('1 bite +3 (1d4)', '1 darkness'),
    json_build_array('Darkness: Extinguish all light sources in near.'),
    'A floating, black octopus with rows of red eyes and a webbed skirt of tentacles.',
    '{"strength": -2, "dexterity": 3, "constitution": 0, "intelligence": -3, "wisdom": 0, "charisma": -3, "alignment": "N", "movement": "near (fly)"}'
),

-- Deep One
(
    gen_random_uuid(),
    'Deep One',
    2.0,
    0,
    '10',
    13,
    json_build_array('2 spear (close/near) +2 (1d6)'),
    json_build_array('Sunblind: Blinded in bright light.'),
    'Cultish, amphibious fish-people with bulbous eyes. They lurk in deep water and sunless caverns.',
    '{"strength": 2, "dexterity": 1, "constitution": 1, "intelligence": -2, "wisdom": 0, "charisma": -2, "alignment": "C", "movement": "near (swim)"}'
),

-- Drow
(
    gen_random_uuid(),
    'Drow',
    2.0,
    0,
    '9',
    16,
    json_build_array('1 poison dart (near) +3 (1d4 + poison)', '1 longsword +1 (1d8)'),
    json_build_array('Poison: DC 15 CON or sleep.', 'Sunblind: Blinded in bright light.'),
    'A graceful, shadowy elf that pounces like a spider.',
    '{"strength": 0, "dexterity": 3, "constitution": 0, "intelligence": 1, "wisdom": 1, "charisma": 1, "alignment": "C", "movement": "near"}'
),

-- Drow Priestess
(
    gen_random_uuid(),
    'Drow, Priestess',
    6.0,
    0,
    '28',
    16,
    json_build_array('3 snake whip (near) +4 (1d8 + poison)', '1 spell +4'),
    json_build_array('Poison: DC 15 CON or paralyzed 1d4 rounds.', 'Sunblind: Blinded in bright light.', 'Snuff (WIS Spell): DC 12. Extinguish all light sources (even magical) within near.', 'Summon Spiders (WIS Spell): DC 14. Summon 2d4 loyal giant spiders that appear within near. They stay for 5 rounds.', 'Web (WIS Spell): DC 13. A near-sized cube of webs within far immobilizes all inside it for 5 rounds. DC 15 STR on turn to break free.'),
    'A statuesque female drow with a crown of metal spider webs and an imperious gaze.',
    '{"strength": 2, "dexterity": 3, "constitution": 1, "intelligence": 3, "wisdom": 4, "charisma": 3, "alignment": "C", "movement": "near"}'
),

-- Drow Drider
(
    gen_random_uuid(),
    'Drow, Drider',
    6.0,
    0,
    '29',
    16,
    json_build_array('3 longsword +3 (1d8)', '2 longbow (far) +3 (1d8 + poison)'),
    json_build_array('Poison: DC 15 CON or paralyzed 1d4 rounds.', 'Sunblind: Blinded in bright light.'),
    'A monstrosity with the body of a giant spider and torso of a drow.',
    '{"strength": 3, "dexterity": 3, "constitution": 2, "intelligence": 2, "wisdom": 2, "charisma": 0, "alignment": "C", "movement": "near (climb)"}'
),

-- Duergar
(
    gen_random_uuid(),
    'Duergar',
    2.0,
    0,
    '12',
    15,
    json_build_array('1 war pick +2 (1d6)'),
    json_build_array('Enlarge: 1/day, +1d6 damage on melee attacks and ADV on STR checks for 3 rounds.', 'Invisibility: 1/day, turn invisible for 3 rounds. Ends if duergar attacks.', 'Sunblind: Blinded in bright light.'),
    'Gray-skinned, greedy dwarves with bald pates and white beards. They dwell in somber castles deep within the earth filled with stolen treasures and enslaved prisoners.',
    '{"strength": 2, "dexterity": 0, "constitution": 3, "intelligence": 0, "wisdom": -1, "charisma": -1, "alignment": "C", "movement": "near"}'
),

-- Ettercap
(
    gen_random_uuid(),
    'Ettercap',
    3.0,
    0,
    '14',
    12,
    json_build_array('2 bite +2 (1d6)', '1 poison web (near) +2'),
    json_build_array('Poison Web: One target stuck in place and 1d4 damage/round. DC 12 DEX on turn to escape.'),
    'Bipedal, eight-eyed spiderfolk with spindly legs and purple fur.',
    '{"strength": 0, "dexterity": 2, "constitution": 1, "intelligence": 0, "wisdom": 0, "charisma": -1, "alignment": "C", "movement": "near (climb)"}'
),

-- Ghoul
(
    gen_random_uuid(),
    'Ghoul',
    2.0,
    0,
    '11',
    11,
    json_build_array('1 claw +2 (1d6 + paralyze)'),
    json_build_array('Undead: Immune to morale checks.', 'Paralyze: DC 12 CON or paralyzed 1d4 rounds.'),
    'Gray-skinned, slavering undead with whipping tongues and flat, reptilian faces.',
    '{"strength": 2, "dexterity": 1, "constitution": 2, "intelligence": -3, "wisdom": -1, "charisma": 0, "alignment": "C", "movement": "near"}'
),

-- Gladiator
(
    gen_random_uuid(),
    'Gladiator',
    3.0,
    0,
    '15',
    16,
    json_build_array('2 longsword +3 (1d8)', '1 spear (close/near) +3 (1d6)'),
    json_build_array(),
    'Veteran warriors seasoned in arena fights to the death.',
    '{"strength": 2, "dexterity": 1, "constitution": 2, "intelligence": 0, "wisdom": 0, "charisma": 1, "alignment": "N", "movement": "near"}'
),

-- Gnoll
(
    gen_random_uuid(),
    'Gnoll',
    2.0,
    0,
    '10',
    12,
    json_build_array('1 spear (close/near) +1 (1d6)', '1 longbow (far) +1 (1d8)'),
    json_build_array('Rage: 1/day, immune to morale checks, +1d4 damage (3 rounds).'),
    'Barbaric, opportunistic hyena-folk who range in large packs.',
    '{"strength": 1, "dexterity": 1, "constitution": 1, "intelligence": -1, "wisdom": 0, "charisma": -1, "alignment": "C", "movement": "near"}'
),

-- Deep Gnome
(
    gen_random_uuid(),
    'Gnome, Deep',
    3.0,
    0,
    '14',
    14,
    json_build_array('1 pick +3 (1d6)', '1 dart (near) +2 (1d4)'),
    json_build_array('Stone Meld: 2/day, underground only. Turn invisible for 3 rounds.'),
    'Gray-skinned, white-haired fey the size of halflings. They hunt for gems and rare cave flora.',
    '{"strength": 2, "dexterity": 1, "constitution": 1, "intelligence": 1, "wisdom": 1, "charisma": 1, "alignment": "L", "movement": "near"}'
),

-- Goblin
(
    gen_random_uuid(),
    'Goblin',
    1.0,
    0,
    '5',
    11,
    json_build_array('1 club +0 (1d4)', '1 shortbow (far) +1 (1d4)'),
    json_build_array('Keen Senses: Can''t be surprised.'),
    'A short, hairless humanoid with green skin and pointy ears.',
    '{"strength": 0, "dexterity": 1, "constitution": 1, "intelligence": -1, "wisdom": -1, "charisma": -2, "alignment": "C", "movement": "near"}'
),

-- Goblin Boss
(
    gen_random_uuid(),
    'Goblin, Boss',
    4.0,
    0,
    '20',
    14,
    json_build_array('1 spear (close/near) +3 (1d6)'),
    json_build_array('Keen Senses: Can''t be surprised.'),
    'A scarred goblin with knotted muscles and a crown of iron.',
    '{"strength": 2, "dexterity": 1, "constitution": 2, "intelligence": -1, "wisdom": 0, "charisma": 1, "alignment": "C", "movement": "near"}'
),

-- Goblin Shaman
(
    gen_random_uuid(),
    'Goblin, Shaman',
    4.0,
    0,
    '19',
    12,
    json_build_array('1 staff +0 (1d4)', '1 spell +3'),
    json_build_array('Keen Senses: Can''t be surprised.', 'Bug Brain (WIS Spell): DC 13. Near range, one target. Target''s INT drops to 1 for 1d4 rounds.', 'Skitter (WIS Spell): DC 12. Self. Climb like a spider for 5 rounds.', 'Stink Bomb (WIS Spell): DC 12. One target within far 2d4 damage and DC 12 CON or DISADV on next check/attack.'),
    'A swaying, chanting goblin wearing necklaces of teeth and a robe of musty rat pelts.',
    '{"strength": 0, "dexterity": 1, "constitution": 1, "intelligence": 0, "wisdom": 2, "charisma": 1, "alignment": "C", "movement": "near"}'
),

-- Knight
(
    gen_random_uuid(),
    'Knight',
    3.0,
    0,
    '14',
    17,
    json_build_array('2 bastard sword +3 (1d8)'),
    json_build_array('Oath: 3/day, ADV on a roll made in service of knight''s order.'),
    'A warrior in shining plate mail and the surcoat of a knightly order.',
    '{"strength": 3, "dexterity": 0, "constitution": 1, "intelligence": 0, "wisdom": 0, "charisma": 1, "alignment": "L", "movement": "near"}'
),

-- Kobold
(
    gen_random_uuid(),
    'Kobold',
    0.0,
    0,
    '1',
    13,
    json_build_array('1 spear (close/near) +0 (1d6)'),
    json_build_array('Dodge: 1/day, an attack that would hit misses instead.'),
    'Puny, scaled coyote-lizards that dwell underground.',
    '{"strength": -2, "dexterity": 2, "constitution": 0, "intelligence": -1, "wisdom": 0, "charisma": -1, "alignment": "C", "movement": "near"}'
),

-- Lizardfolk
(
    gen_random_uuid(),
    'Lizardfolk',
    2.0,
    0,
    '11',
    14,
    json_build_array('1 spear (close/near) +2 (1d6)'),
    json_build_array(),
    'Crocodilian humanoids with scaly faces, claws, and tails. They dwell in swamps and rivers.',
    '{"strength": 1, "dexterity": 1, "constitution": 2, "intelligence": -1, "wisdom": 1, "charisma": -2, "alignment": "C", "movement": "near (swim)"}'
),

-- Mushroomfolk
(
    gen_random_uuid(),
    'Mushroomfolk',
    3.0,
    0,
    '15',
    13,
    json_build_array('2 slam +2 (1d6)'),
    json_build_array('Sunblind: Blinded in bright light.', 'Telepathic: Speak mentally with, creatures within double near.'),
    'Lumbering humanoids with spongy, elongated bodies and toadstools on their heads.',
    '{"strength": 2, "dexterity": -1, "constitution": 2, "intelligence": 0, "wisdom": 1, "charisma": 0, "alignment": "N", "movement": "near"}'
),

-- Orc
(
    gen_random_uuid(),
    'Orc',
    1.0,
    0,
    '4',
    15,
    json_build_array('1 greataxe +2 (1d8)'),
    json_build_array('Rage: 1/day, immune to morale checks, +1d4 damage (3 rounds).'),
    'A tusked, tall humanoid with gray skin and pointed ears.',
    '{"strength": 2, "dexterity": 0, "constitution": 0, "intelligence": -1, "wisdom": 0, "charisma": -1, "alignment": "C", "movement": "near"}'
),

-- Orc Chieftain
(
    gen_random_uuid(),
    'Orc, Chieftain',
    4.0,
    0,
    '19',
    14,
    json_build_array('2 greataxe +4 (1d10)'),
    json_build_array('Rage: 1/day, immune to morale checks, +1d4 damage (3 rounds).'),
    'An imposing orc with scars crisscrossing its body.',
    '{"strength": 2, "dexterity": 1, "constitution": 1, "intelligence": -1, "wisdom": 0, "charisma": -1, "alignment": "C", "movement": "near"}'
),

-- Giant Rat
( gen_random_uuid(),
  'Giant Rat',
  1.0,
  0,
  '5',
  11,
  json_build_array('1 bite +1 (1d4 + disease)'),
  json_build_array('Disease: DC 12 CON or 1d4 CON damage (can''t heal while ill). Repeat check once per day; ends on success. Die at 0 CON.'),
  'Cunning rats as large as cats. Mangy fur and wormlike tails.',
  '{"strength": -2, "dexterity": 1, "constitution": 1, "intelligence": -2, "wisdom": 1, "charisma": -2, "alignment": "N", "movement": "near"}'
),

-- Dire Rat
( gen_random_uuid(),
  'Dire Rat',
  2.0,
  0,
  '10',
  12,
  json_build_array('1 bite +2 (1d6 + disease)'),
  json_build_array('Disease: DC 12 CON or 1d4 CON damage (can''t heal while ill). Repeat check once per day; ends on success. Die at 0 CON.'),
  'Child-sized, savage rats bristling with bony face and spine ridges.',
  '{"strength": 1, "dexterity": 2, "constitution": 1, "intelligence": -2, "wisdom": 1, "charisma": -2, "alignment": "N", "movement": "near"}'
),

-- Rat Swarm
( gen_random_uuid(),
  'Rat Swarm',
  6.0,
  0,
  '28',
  10,
  json_build_array('4 bite +0 (1 + disease)'),
  json_build_array('Disease: DC 9 CON or 1d4 CON damage (can''t heal while ill). Repeat check once per day; ends on success. Die at 0 CON.'),
  'A screeching tidal wave of clawing and biting rats.',
  '{"strength": -3, "dexterity": 0, "constitution": 1, "intelligence": -3, "wisdom": 1, "charisma": -3, "alignment": "N", "movement": "near"}'
),

-- Giant Spider
(
    gen_random_uuid(),
    'Spider, Giant',
    3.0,
    0,
    '13',
    13,
    json_build_array('1 bite +3 (1d4 + poison)'),
    json_build_array('Poison: DC 12 CON or paralyzed 1d4 hours.'),
    'Bulbous abdomen and eight, spindly legs. Dwells high in trees or caves and ambushes prey, capturing them to eat later.',
    '{"strength": 2, "dexterity": 3, "constitution": 0, "intelligence": -2, "wisdom": 1, "charisma": -2, "alignment": "N", "movement": "near (climb)"}'
),

-- Spider Swarm
(
    gen_random_uuid(),
    'Spider, Swarm',
    2.0,
    0,
    '9',
    13,
    json_build_array('1 bite +3 (1d4 + poison)'),
    json_build_array('Poison: DC 12 CON or paralyzed 1d4 rounds.'),
    'A scurrying carpet of spiders.',
    '{"strength": -1, "dexterity": 3, "constitution": 0, "intelligence": -3, "wisdom": 1, "charisma": -3, "alignment": "N", "movement": "near (climb)"}'
),

-- Thug
(
    gen_random_uuid(),
    'Thug',
    1.0,
    0,
    '4',
    13,
    json_build_array('1 shortsword +1 (1d6)'),
    json_build_array(),
    'A bruised and boorish ruffian.',
    '{"strength": 1, "dexterity": 0, "constitution": 0, "intelligence": -1, "wisdom": 1, "charisma": -1, "alignment": "C", "movement": "near"}'
),

-- Wolf
(
    gen_random_uuid(),
    'Wolf',
    2.0,
    0,
    '10',
    12,
    json_build_array('1 bite +2 (1d6)'),
    json_build_array('Pack Hunter: Deals +1 damage while an ally is close.'),
    'A giant canine with a gray pelt, yellow eyes, and dripping jaws.',
    '{"strength": 2, "dexterity": 2, "constitution": 1, "intelligence": -2, "wisdom": 1, "charisma": 0, "alignment": "N", "movement": "double near"}'
),

-- Dire Wolf
(
    gen_random_uuid(),
    'Wolf, Dire',
    4.0,
    0,
    '19',
    12,
    json_build_array('2 bite +4 (1d8)'),
    json_build_array('Pack Hunter: Deals +1 damage while an ally is close.'),
    'A massive wolf with spines of black bone along its brow ridge and back.',
    '{"strength": 3, "dexterity": 2, "constitution": 1, "intelligence": -1, "wisdom": 1, "charisma": 0, "alignment": "N", "movement": "double near"}'
),

-- Winter Wolf
(
    gen_random_uuid(),
    'Wolf, Winter',
    5.0,
    0,
    '23',
    12,
    json_build_array('2 bite +4 (1d6)', '1 frost breath'),
    json_build_array('Impervious: Cold immune.', 'Frost Breath: Fills a near-sized cube extending from winter wolf. DC 15 DEX or 3d8 damage. Cannot use again for 1d4 rounds.'),
    'Sinister, white-pelted wolves with piercing blue eyes. From the fey realms of eternal winter.',
    '{"strength": 3, "dexterity": 2, "constitution": 1, "intelligence": 0, "wisdom": 1, "charisma": 0, "alignment": "C", "movement": "double near"}'
),

-- Worg
(
    gen_random_uuid(),
    'Worg',
    3.0,
    0,
    '14',
    11,
    json_build_array('1 bite +3 (1d6)'),
    json_build_array(),
    'Bat-faced wolves that speak Goblin and often serve as war mounts for goblinkind.',
    '{"strength": 2, "dexterity": 1, "constitution": 1, "intelligence": -2, "wisdom": 1, "charisma": -2, "alignment": "C", "movement": "double near"}'
),

-- Zombie
(
    gen_random_uuid(),
    'Zombie',
    2.0,
    0,
    '11',
    8,
    json_build_array('1 slam +2 (1d6)'),
    json_build_array('Undead: Immune to morale checks.', 'Relentless: If zombie reduced to 0 HP by a non-magical source, DC 15 CON to go to 1 HP instead.'),
    'Lurching and decomposed undead that hunt in mobs.',
    '{"strength": 2, "dexterity": -2, "constitution": 2, "intelligence": -2, "wisdom": -2, "charisma": -3, "alignment": "C", "movement": "near"}'
);
