<?php

declare(strict_types=1);

/*
 * This file is part of the "feed_generator" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\FeedGenerator\Tests\Unit\Collection;

use Brotkrueml\FeedGenerator\Collection\MixedItemsException;
use Brotkrueml\FeedGenerator\Contract\CategoryInterface;
use Brotkrueml\FeedGenerator\Tests\Fixtures\ValueObject\AuthorFixture;
use PHPUnit\Framework\TestCase;

final class MixedItemsExceptionTest extends TestCase
{
    /**
     * @test
     */
    public function forItemTypes(): void
    {
        $actual = MixedItemsException::forItemTypes(
            AuthorFixture::class,
            CategoryInterface::class
        );

        self::assertInstanceOf(MixedItemsException::class, $actual);
        self::assertSame(1668414319, $actual->getCode());
        self::assertSame('Class of type "Brotkrueml\FeedGenerator\Contract\CategoryInterface" expected, "Brotkrueml\FeedGenerator\Tests\Fixtures\ValueObject\AuthorFixture" given', $actual->getMessage());
    }
}
