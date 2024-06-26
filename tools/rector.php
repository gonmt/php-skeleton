<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector;
use Rector\Set\ValueObject\LevelSetList;
use Rector\Set\ValueObject\SetList;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->paths([
        __DIR__ . '/../apps',
        __DIR__ . '/../src',
        __DIR__ . '/../tests',
    ]);

    $rectorConfig->sets([
        LevelSetList::UP_TO_PHP_83,
        SetList::TYPE_DECLARATION
    ]);

    $rectorConfig->skip([
        __DIR__ . '/../apps/firstapp/backend/var',
    ]);

    $rectorConfig->skip([AddOverrideAttributeToOverriddenMethodsRector::class]);
};
