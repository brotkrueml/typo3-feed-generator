<?php

declare(strict_types=1);

/*
 * This file is part of the "feed_generator" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\FeedGenerator\Tests\Unit\Entity;

use Brotkrueml\FeedGenerator\Entity\AbstractFeed;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Brotkrueml\FeedGenerator\Entity\AbstractFeed
 */
final class AbstractFeedTest extends TestCase
{
    /**
     * @test
     */
    public function ensureDefaultValuesAreSetCorrectly(): void
    {
        $subject = new class() extends AbstractFeed {
        };

        self::assertSame('', $subject->getId());
        self::assertSame('', $subject->getTitle());
        self::assertSame('', $subject->getDescription());
        self::assertSame('', $subject->getLink());
        self::assertSame([], $subject->getAuthors());
        self::assertNull($subject->getDatePublished());
        self::assertNull($subject->getDateModified());
        self::assertNull($subject->getLastBuildDate());
        self::assertSame('', $subject->getLanguage());
        self::assertSame('', $subject->getCopyright());
        self::assertNull($subject->getImage());
        self::assertSame([], $subject->getCategories());
        self::assertSame([], $subject->getItems());
    }
}
