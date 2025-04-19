<?php

declare(strict_types=1);

use Rector\Caching\ValueObject\Storage\FileCacheStorage;
use Rector\Config\RectorConfig;
use Rector\PHPUnit\Set\PHPUnitSetList;
use Rector\Symfony\Set\SymfonySetList;
use Rector\Symfony\Set\TwigSetList;

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
    // Do not use UP_TO_* level sets for frameworks or libraries for performance.
    // See https://github.com/rectorphp/rector-symfony/pull/572 for a great explanation.
    ->withSets([
        SymfonySetList::SYMFONY_72,
        PHPUnitSetList::PHPUNIT_110,
        TwigSetList::TWIG_24,
    ]);
