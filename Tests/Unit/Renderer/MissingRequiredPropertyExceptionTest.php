<?php

declare(strict_types=1);

/*
 * This file is part of the "feed_generator" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\FeedGenerator\Tests\Unit\Renderer;

use Brotkrueml\FeedGenerator\Renderer\MissingRequiredPropertyException;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class MissingRequiredPropertyExceptionTest extends TestCase
{
    #[Test]
    public function forProperty(): void
    {
        $actual = MissingRequiredPropertyException::forProperty('some_property');

        self::assertInstanceOf(MissingRequiredPropertyException::class, $actual);
        self::assertSame('Required property "some_property" is missing.', $actual->getMessage());
        self::assertSame(1668153106, $actual->getCode());
    }

    #[Test]
    public function forProperties(): void
    {
        $actual = MissingRequiredPropertyException::forProperties(['some_property', 'another_property']);

        self::assertInstanceOf(MissingRequiredPropertyException::class, $actual);
        self::assertSame('At least one of some_property or another_property must be present.', $actual->getMessage());
        self::assertSame(1668434363, $actual->getCode());
    }
}
