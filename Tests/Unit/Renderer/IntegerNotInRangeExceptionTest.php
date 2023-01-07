<?php

declare(strict_types=1);

/*
 * This file is part of the "feed_generator" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\FeedGenerator\Tests\Unit\Renderer;

use Brotkrueml\FeedGenerator\Renderer\IntegerNotInRangeException;
use PHPUnit\Framework\TestCase;

final class IntegerNotInRangeExceptionTest extends TestCase
{
    /**
     * @test
     */
    public function forProperty(): void
    {
        $actual = IntegerNotInRangeException::forProperty('some_property', 42, 50, 100);

        self::assertInstanceOf(IntegerNotInRangeException::class, $actual);
        self::assertSame(
            'The value of "some_property" must be between 50 and 100, 42 given.',
            $actual->getMessage(),
        );
        self::assertSame(1668153593, $actual->getCode());
    }
}
