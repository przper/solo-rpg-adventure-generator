# Solo RPG Adventure Generator - Development Guide

## Commands
- **Run all tests**: `make test` or `./bin/phpunit`
- **Run specific test**: `make test filter=TestName testsuite=unit|integration|application`
- **Static analysis**: `vendor/bin/phpstan analyse src tests`
- **Start dev server**: `make start` (starts Docker containers)
- **Build assets**: `npm run dev`
- **Watch assets**: `npm run watch`
- **Clear cache**: `make cc`
- **Run Symfony command**: `make sf c='command'`
- **Run in container**: `make sh`

## Code Style Guidelines
- **Naming**: Classes use PascalCase, methods/properties use camelCase
- **Interfaces**: Named with `Interface` suffix and implemented by concrete classes
- **Types**: Always use strict type declarations for parameters and return types
- **Classes**: One class per file, match filename to class name
- **Imports**: Organize by PSR standards (PHP core, external, project)
- **Formatting**: Use 4 spaces for indentation, PHP 8.2+ syntax
- **Testing**: Test method names use `it_*` pattern for readability
- **Error handling**: Use exceptions with meaningful messages
- **Architecture**: Follow Symfony best practices and dependency injection