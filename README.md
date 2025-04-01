# Solo RPG Adventure Generator

This idea behind the project is to allow solo player to run solo RPG adventures.

There is a number of free Dungeon Generators, but they don't **hide their content to the player**. They are a great tool for the Dungeon Master, not for a person who wants/needs to play alone. This application main goal is to generate a Dungeon and store it hidden in memory. The player would be presented with description of the next room only if he decides to enter it. Otherwise it will be hidden.

## Getting Started
1. Run `make` to build project
2. Run `docker compose up -d` to launch docker
3. Go to [localhost](https://rpg.localhost/)

## How To Play
1. Configure Dungeon at [New Dungeon page](https://rpg.localhost/play/new)
2. Move using buttons and explore the generated dungeon. Resolve encounters manually
3. Remember that confidence is slow but insidious killer...

## Features

- Dungeon Generation
    - Room & Corridors **(DONE)**
    - Enemies **(DONE)**: using DnD 5E rools, and soon Shadowdark
    - Treasure (Planned)
    - Traps (Planned)
    - Descriptions (...maybe)
- For of War **(DONE)**: hide Dungeon layout, reveal with player advancement
- Simple Diceroller (Planned)
