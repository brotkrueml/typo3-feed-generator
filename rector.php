<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Core\ValueObject\PhpVersion;
use Rector\DeadCode\Rector\If_\RemoveAlwaysTrueIfConditionRector;
use Rector\Php74\Rector\LNumber\AddLiteralSeparatorToNumberRector;
use Rector\PHPUnit\Set\PHPUnitSetList;
use Rector\Set\ValueObject\LevelSetList;
use Rector\Set\ValueObject\SetList;
use Rector\TypeDeclaration\Rector\FunctionLike\AddReturnTypeDeclarationFromYieldsRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->phpVersion(PhpVersion::PHP_81);

    $rectorConfig->sets([
        LevelSetList::UP_TO_PHP_81,
        PHPUnitSetList::PHPUNIT_CODE_QUALITY,
        PHPUnitSetList::PHPUNIT_EXCEPTION,
        PHPUnitSetList::PHPUNIT_SPECIFIC_METHOD,
        PHPUnitSetList::PHPUNIT_YIELD_DATA_PROVIDER,
        SetList::CODE_QUALITY,
        SetList::DEAD_CODE,
        SetList::EARLY_RETURN,
        SetList::TYPE_DECLARATION,
    ]);

    $rectorConfig->autoloadPaths([
        __DIR__ . '/.Build/vendor/autoload.php',
    ]);
    $rectorConfig->importShortClasses(false);
    $rectorConfig->importNames();
    $rectorConfig->paths([
        __DIR__ . '/Classes',
        __DIR__ . '/Tests',
    ]);
    $rectorConfig->skip([
        AddLiteralSeparatorToNumberRector::class,
        AddReturnTypeDeclarationFromYieldsRector::class => [
            __DIR__ . '/Tests',
        ],
        RemoveAlwaysTrueIfConditionRector::class => [
            __DIR__ . '/Classes/Renderer/JsonRenderer.php',
        ],
    ]);
};
