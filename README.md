# Solo RPG Adventure Generator

This idea behind the project is to allow solo player to run solo RPG adventures.

There is a number of free Dungeon Generators, but they don't **hide their content to the player**. They are a great tool for the Dungeon Master, not for a person who wants/needs to play alone. This application main goal is to generate a Dungeon and store it hidden in memory. The player would be presented with description of the next room only if he decides to enter it. Otherwise it will be hidden.

## Getting Started

1. If not already done, [install Docker Compose](https://docs.docker.com/compose/install/) (v2.10+)
2. Run `make build` to build fresh images
3. Run `make up` (the logs will be displayed in the current shell)
4. Open `https://localhost` in your favorite web browser and [accept the auto-generated TLS certificate](https://stackoverflow.com/a/15076602/1352334)
5. Run `make down` to stop the Docker containers.

## How To Play
1. Select Dungeon Type at `https://localhost/`
2. ???

## Features

- Hide Dungeon layout, reveal with advancement (Planned)
- Railroad Dungeon Generation **(WIP)**
    - Room & Corridors **(DONE)**
    - Treasure **(WIP)**
    - Enemies (Planned)
    - Traps (Planned)
    - Descriptions (Planned)
- Roguelike Dungeon Generation (Planned)