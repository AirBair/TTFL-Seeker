<?php

declare(strict_types=1);

use Rector\Caching\ValueObject\Storage\FileCacheStorage;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withPaths([
        __DIR__.'/migrations',
        __DIR__.'/src',
        __DIR__.'/tests',
    ])
    ->withCache(cacheDirectory: './var/cache/rector', cacheClass: FileCacheStorage::class)
    ->withImportNames(importShortClasses: false)
    // Sync on PHP version in composer.json
    ->withPhpSets()
    ->withComposerBased(
        twig: true,
        phpunit: true,
        symfony: true,
    );
