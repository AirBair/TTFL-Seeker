<?php

declare(strict_types=1);

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__)
    ->exclude('assets')
    ->exclude('config')
    ->exclude('node_modules')
    ->exclude('public')
    ->exclude('var')
    ->exclude('vendor')
    ->exclude('translations');

return (new PhpCsFixer\Config())
    ->setRiskyAllowed(true)
    ->setRules([
        '@PhpCsFixer' => true,
        '@PhpCsFixer:risky' => true,
        '@autoPHPUnitMigration:risky' => true,
        '@autoPHPMigration' => true,
        '@autoPHPMigration:risky' => true,
        'multiline_whitespace_before_semicolons' => ['strategy' => 'no_multi_line'],
    ])
    ->setFinder($finder);
