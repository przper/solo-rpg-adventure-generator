# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Overview
A Symfony application for generating solo tabletop RPG adventures with fog-of-war mechanics. Dungeons are generated with encounters hidden from the player until explored.

## Commands
Commands must run inside the Docker container. Use `docker compose exec web <command>`:
- **Run all tests**: `docker compose exec web make test`
- **Run single test file**: `docker compose exec web vendor/bin/phpunit tests/Unit/Core/MapTest.php`
- **Run single test method**: `docker compose exec web vendor/bin/phpunit --filter it_method_name`
- **Run test suite**: `docker compose exec web make unit-tests` (also `integration-tests`, `application-tests`)
- **Static analysis**: `docker compose exec web make lint` (PHPStan)
- **Install deps**: `docker compose exec web make`
- **Build frontend**: `docker compose exec web npm run build`
- **Run Symfony console**: `docker compose exec web bin/console <command>`
- **Enter container shell**: `make sh` (for interactive work)

## Environment
- PHP 8.3+ in Docker, PostgreSQL 17 with pgvector
- Access at `http://rpg.localhost` (requires external Traefik network named `web`)
- Start with `docker compose up -d`

## Architecture

### Strategy Pattern with Tagged Services
The application uses `#[TaggedIterator]` for pluggable strategies with `supports()` methods:

**Map Building** (`src/MapBuilding/`) - Tagged `map_building.strategy`:
- `RailroadMapBuildingStrategy`: Linear room-corridor-room layout
- `GridMapBuildingStrategy`: Grid-based dungeon layout

**Encounters Planning** (`src/EncountersPlanning/`) - Tagged `encounters_planning.strategy`:
- `DungeonsAndDragons5EncountersPlanningStrategy`: D&D 5E encounter balancing by CR
- `ShadowdarkEncountersPlanningStrategy`: 8 encounter types (solo monster, mob, boss, treasure, trap, hazards, NPC)

### Core Domain (`src/Core/`)
- **Map**: Grid with Rooms and Corridors
- **Encounter/Enemy**: Combat events with stats (CR, HP, AC)
- **DiceStack**: Dice notation parser (e.g., "2d6+3")

### Game Flow (`src/Game/`)
`GameFactory` → builds `Map` + `EncountersPlan` using strategies
`Game` → manages player movement via `Movement` enum
`FogOfWarInterface` → tracks explored tiles
`EncountersInterface` → places/triggers encounters

### Monster Compendium (`src/MonsterCompendium/`)
Doctrine entities with vector embeddings (OpenAI) for semantic monster search. Abstract `Monster` base class, `ShadowdarkMonster` implementation.

### Frontend
Webpack Encore + TailwindCSS. Twig templates with CSS fog-of-war and form submissions.

## Code Style
- Strict types, constructor property promotion, `final` classes, `readonly` properties
- Test methods: `it_*` or `test_*` pattern
- Test structure mirrors src: `tests/Unit/`, `tests/Integration/`, `tests/Application/`
