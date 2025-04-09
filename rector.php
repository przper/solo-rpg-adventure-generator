<?php

use Rector\Config\RectorConfig;
use Rector\Symfony\Set\SymfonySetList;

return RectorConfig::configure()
    ->withPaths([
        __DIR__.'/src',
        __DIR__.'/tests',
    ])
    ->withSets([
//        SymfonySetList::SYMFONY_62, applied 2025-04-09
//        SymfonySetList::SYMFONY_63, applied 2025-04-09
//        SymfonySetList::SYMFONY_64, applied 2025-04-09
//        SymfonySetList::SYMFONY_70, applied 2025-04-09
//        SymfonySetList::SYMFONY_71, applied 2025-04-09
    ])
;
