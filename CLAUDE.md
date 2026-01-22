# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

# Solo RPG Adventure Generator - Development Guide

## Overview
A Symfony 7.2 application for generating solo tabletop RPG adventures with fog-of-war mechanics. The app generates dungeons with encounters while hiding content from the player until they explore.

## Commands
- **Run all tests**: `make test`
- **Run specific test suite**: `make unit-tests`, `make integration-tests`, `make application-tests`
- **Run single test**: `make exec COMMAND="vendor/bin/phpunit --filter=test_name"`
- **Static analysis**: `make lint` (runs PHPStan)
- **Install dependencies**: `make` (runs composer install in container)
- **Build frontend assets**: `npm run dev`
- **Watch frontend assets**: `npm run watch`
- **Database migrations**: `make exec COMMAND="bin/console doctrine:migrations:migrate"`

## Environment Setup
- **PHP 8.3+** running in Docker container
- **PostgreSQL 17** with pgvector extension for vector embeddings
- **Docker Compose** for local development
- All commands run inside container via `docker compose exec web`
- Access app at `http://rpg.localhost` (requires Traefik proxy)

## Architecture

### Core Domain Model (`src/Core/`)
The foundation classes representing RPG concepts:
- **Map**: Grid-based dungeon layout with Rooms and Corridors
- **Encounter**: Combat/obstacle/treasure events placed on the map
- **Enemy**: Monster instances with stats (CR, HP, AC, attacks)
- **DiceStack**: Dice notation parser/roller (e.g., "2d6+3")

### Strategy Pattern Implementation
The application uses tagged services with Symfony's `#[TaggedIterator]` to implement pluggable strategies:

**Map Building** (`src/MapBuilding/`):
- `MapBuildingStrategy` interface with `supports()` method
- `RailroadMapBuildingStrategy`: Linear room-corridor-room layout
- `GridMapBuildingStrategy`: Grid-based dungeon layout
- Tagged with `map_building.strategy`

**Encounters Planning** (`src/EncountersPlanning/`):
- `EncountersPlanningStrategy` interface
- `DungeonsAndDragons5EncountersPlanningStrategy`: D&D 5E encounter balancing
- `ShadowdarkEncountersPlanningStrategy`: Shadowdark RPG system with 8 encounter types
- Tagged with `encounters_planning.strategy`
- Each strategy calculates appropriate encounters based on party level/CR

### Game Flow (`src/Game/`)
1. **GameFactory**: Uses strategies to build `Map` and `EncountersPlan`
2. **Game**: Core game loop managing player movement and state
3. **FogOfWarInterface**: Tracks explored vs unexplored tiles (implements hiding mechanic)
4. **EncountersInterface**: Places encounters on map based on strategy plan
5. Player moves via `Movement` enum, revealing tiles and triggering encounters

### Monster Compendium (`src/MonsterCompendium/`)
- Doctrine entities for storing monster stats in PostgreSQL
- Vector embeddings for semantic monster search (OpenAI integration)
- `Monster` is abstract base class, `ShadowdarkMonster` is concrete implementation
- Repositories query by CR, level range, and vector similarity

### Frontend
- **Webpack Encore** + **TailwindCSS** for styling
- Twig templates render map grid with CSS-based fog-of-war
- HTMX-style form submissions for game state updates

## Code Style Guidelines
- **Naming**: Classes use PascalCase, methods/properties use camelCase
- **Types**: Always use strict type declarations for parameters and return types
- **Modern PHP**: Use constructor property promotion, mark classes as `final` when appropriate, and use `readonly` properties for immutable data
- **Testing**: Test method names use `it_*` or `test_*` pattern
- **Architecture**: Follow Symfony best practices and dependency injection

## Important Notes

### Test Environment
- PHPUnit sets `APP_ENV=test` in `phpunit.xml.dist`
- `tests/bootstrap.php` ensures this is respected by copying `$_SERVER['APP_ENV']` to `$_ENV` and `putenv()` before `Dotenv::bootEnv()` loads `.env`

### Docker Volume Issues (macOS)
- Uses `:rw,cached` volume mounts for performance
- Monolog configured with `use_locking: false` to prevent file lock errors on macOS Docker volumes
- All Makefile commands use `DOCKER_EXEC = docker compose exec web` prefix

### Entity Changes
- `Enemy` class previously had `DiceStack $hitDice` parameter - this was removed in favor of `int $totalHitPoints`
- `Monster` entity still supports both `hitDice` and `totalHitPoints` (constructor validates at least one is set)
- Attack format is `string[]` like `["Dagger: 1x 1d6", "Club: 2x 1d8"]`
