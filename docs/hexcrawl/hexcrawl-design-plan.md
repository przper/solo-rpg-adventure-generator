# Hexcrawl World Map — Design & Implementation Plan

## Context

This document summarizes design decisions for adding hexcrawl world maps to the Solo RPG Adventure Generator. It is intended as guidance for Claude Code when implementing this feature.

## Concept

Hexcrawl maps are a new feature operating at a **different scale** than existing dungeon maps. Dungeons are tactical, tile-by-tile exploration of rooms and corridors. Hexcrawl is overworld wilderness exploration where each hex represents a large area (typically ~6 miles). The two systems **coexist** — a hex with a feature (ruin, lair, etc.) can contain a generated dungeon inside it.

## Key Design Decisions

### Separate Domain, Not Refactored Existing Code

The hexcrawl system lives in its own domain namespace. Do NOT refactor or generalize existing dungeon classes (`Map`, `Tile`, `Room`, `Corridor`, `Coordinates`, `PersistentFogOfWar`) to handle both grid types. The reasons:

- `Map` uses a 2D matrix (`$tiles[$y][$x]`) with width/height bounds. Hex maps are sparse collections keyed by coordinate.
- `Coordinates` has `moveBy(deltaX, deltaY)` which is a square grid concept. Hex movement depends on whether the current column is odd/even.
- `getNearbyTiles()` returns 4 neighbors. Hex has 6 with offset-dependent logic.
- `PersistentFogOfWar` references `Room` and `Corridor` directly in its reveal logic. Hex fog has no Room/Corridor distinction.
- `Movement` / `MovementDirection` has 4 directions with integer deltas. Hex needs 6 directions with coordinate-system-aware offsets.

Attempting to unify these would mean rewriting nearly every class for a shared interface so thin ("a map has tiles with coordinates") that it saves no meaningful code. The existing dungeon code is well-tested and would get worse from the abstraction.

### Domain Structure

**Future namespace plan** (implement incrementally, not all at once):

- **`Core`** — truly shared RPG primitives: `DiceStack`, `Encounter`, `Enemy`, `EncounterDifficulty`. These work at any scale.
- **`Dungeon`** — existing indoor tile-level system: `Map`, `Tile`, `TileType`, `Room`, `Corridor`, `MapElement`, `Coordinates`, `PersistentFogOfWar`, grid/railroad builders.
- **`World`** — new hex-scale overworld: `HexMap`, `Hex`, `HexCoordinate`, `HexTerrain`, `HexFeature`, `HexFogOfWar`.

**For the initial implementation**: create new `World` (or `Hexcrawl`) namespace for all hex code. Do NOT move existing dungeon classes into a `Dungeon` namespace yet — that refactor can happen later when the integration point between World and Dungeon is clear.

### Coordinate System

Use **offset coordinates** (column, row) — the classic tabletop hexcrawl convention. NOT axial/cube coordinates from game programming. Reasons:

- Matches how published hex modules number hexes (e.g., hex "0305" = column 3, row 5)
- Intuitive for the RPG domain
- The neighbor calculation requiring odd/even column check is a one-time helper method, not worth adopting an unintuitive coordinate system to avoid

Display format: 4-digit string `"0305"` (column 3, row 5) for the classic feel.

### Hex Orientation

Use **flat-top** hexes. This matches the column/row labeling convention where columns are vertical stacks of hexes.

### Fog of War

Each domain owns its own fog of war. Do NOT share an interface between Dungeon and World fog of war. The behaviors are too different:

| Aspect | Dungeon | World |
|---|---|---|
| Reveal rules | Room-aware (entering a Room reveals it fully, Corridor reveals only current tile) | Simple per-hex reveal |
| Visibility states | 2 (known, visited) | 3 (fog, terrain visible, feature discovered) |
| Scope | Per game session | Per character |
| Dependencies | References `Map`, `Room`, `Corridor` | References `HexMap`, `HexTerrain`, `HexFeature` |

The *concept* is shared but the *behavior* and *data model* are different enough that a shared interface would be forced and unhelpful.

### Rendering

Use **SVG** generated server-side via Twig templates. Each hex is a `<polygon>` element positioned using hex-to-pixel math. CSS classes control appearance based on fog state (`.hex-fog`, `.hex-forest`, `.hex-mountain`, etc.).

Hex-to-pixel formula (flat-top):
```
centerX = col * (hexWidth * 0.75)
centerY = row * hexHeight + (col is odd ? 0 : hexHeight * 0.5)
```
Where `hexHeight = hexWidth * sqrt(3) / 2`.

A working HTML prototype exists demonstrating this approach with per-character fog of war views. The hex-to-pixel math and SVG generation will translate directly into a Twig template/extension.

### Minimal Changes to Existing Code

The only existing code that needs modification:

1. **`MapType` enum** — add `case Hexcrawl = 'Hexcrawl';`
2. **`GameFactory`** — add branch for hexcrawl game creation
3. **`PlayController`** — routing branch for hexcrawl vs dungeon game type
4. **`routes.yaml`** — add world/hexcrawl controller routes

Everything else is new code in new namespaces.

## New Classes to Create

### Core hex domain (`src/World/` or `src/Hexcrawl/`)

- **`HexCoordinate`** — value object (col, row). Methods: `getNeighbors(): HexCoordinate[]` (6 neighbors with odd/even column offset), `toLabel(): string` ("0305" format), `__toString()`, `isSame()`. Immutable, readonly.
- **`HexTerrain`** — enum: `Plains`, `Forest`, `Mountain`, `Swamp`, `Desert`, `Water`
- **`HexFeature`** — enum or small value object: `Village`, `Ruin`, `Shrine`, `Lair`, etc. Expandable later.
- **`Hex`** — readonly class holding `HexCoordinate`, `HexTerrain`, optional `?HexFeature`
- **`HexMap`** — collection of `Hex` objects keyed by coordinate string. Methods: `getHex(HexCoordinate): ?Hex`, `getAllHexes(): Hex[]`. The authoritative "GM truth" of the full map.

### Fog of war (`src/World/FogOfWar/` or `src/Hexcrawl/FogOfWar/`)

- **`HexVisibility`** — enum: `Fog`, `Terrain`, `Feature`
- **`HexFogOfWar`** — per-character fog state. Methods: `visit(HexCoordinate): void`, `getVisibility(HexCoordinate): HexVisibility`. Entering a hex reveals terrain; if the hex has a feature, marks it as discovered.

### Rendering (`src/World/Rendering/`)

- **`HexMapRenderer`** — takes `HexMap` + character's `HexFogOfWar`, produces SVG via Twig. Contains hex-to-pixel math.
- **Twig templates** in `templates/hex_map/` — SVG hex grid with CSS classes per visibility state.

### Game integration

- **`HexcrawlGame`** — separate from existing `Game` class. Holds `HexMap`, collection of per-character `HexFogOfWar` states, hex movement logic. Do NOT try to generalize existing `Game`.

## MVP Scope

1. `HexCoordinate`, `HexTerrain`, `HexFeature`, `Hex`, `HexMap` — data model
2. `HexFogOfWar` with per-character visibility — fog mechanics
3. `HexMapRenderer` with Twig SVG template — display
4. Hardcoded or simple random hex map data source (no procedural generation needed yet)
5. Simple HTML views showing different characters' perspectives of the same map

No UI for map editing, no procedural hex generation, no dungeon-inside-hex integration, no movement UI, no encounter planning for hexes. Those come later.

## Open Questions for Future Iterations

- **Map generation**: Hand-authored (loaded from data), procedurally generated, or a mix?
- **Persistence**: Per-character fog will eventually need database storage. Design the interface to be storage-agnostic.
- **Movement rules**: Do different terrains cost different movement points? Can mountains block movement?
- **Map boundaries**: Fixed size or expandable/lazy generation as players explore?
- **Party view**: Merged view combining all characters' explorations?
- **Dungeon integration**: How does entering a hex with a feature trigger a dungeon? This is the main connection point between World and Dungeon domains.
