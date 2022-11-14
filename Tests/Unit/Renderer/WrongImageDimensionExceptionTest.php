<?php

declare(strict_types=1);

/*
 * This file is part of the "feed_generator" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\FeedGenerator\Tests\Unit\Renderer;

use Brotkrueml\FeedGenerator\Renderer\WrongImageDimensionException;
use PHPUnit\Framework\TestCase;

final class WrongImageDimensionExceptionTest extends TestCase
{
    /**
     * @test
     */
    public function forProperty(): void
    {
        $actual = WrongImageDimensionException::forProperty('some_property', 42, 21);

        self::assertInstanceOf(WrongImageDimensionException::class, $actual);
        self::assertSame('The some_property of an image is "42" which is higher than the maximum allowed (21).', $actual->getMessage());
        self::assertSame(1668153593, $actual->getCode());
    }
}
