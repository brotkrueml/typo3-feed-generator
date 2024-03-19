<?php

declare(strict_types=1);

/*
 * This file is part of the "feed_generator" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\FeedGenerator\Tests\Unit\Collection;

use Brotkrueml\FeedGenerator\Collection\IndexNotFoundException;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class IndexNotFoundExceptionTest extends TestCase
{
    #[Test]
    public function forIndex(): void
    {
        $actual = IndexNotFoundException::forIndex(42);

        self::assertInstanceOf(IndexNotFoundException::class, $actual);
        self::assertSame(1668412374, $actual->getCode());
        self::assertSame('Index "42" not found for collection', $actual->getMessage());
    }
}
