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
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(AbstractFeed::class)]
final class AbstractFeedTest extends TestCase
{
    #[Test]
    public function ensureDefaultValuesAreSetCorrectly(): void
    {
        $subject = new class extends AbstractFeed {};

        self::assertSame('', $subject->getId());
        self::assertSame('', $subject->getTitle());
        self::assertSame('', $subject->getDescription());
        self::assertSame('', $subject->getLink());
        self::assertTrue($subject->getAuthors()->isEmpty());
        self::assertNull($subject->getDatePublished());
        self::assertNull($subject->getDateModified());
        self::assertNull($subject->getLastBuildDate());
        self::assertSame('', $subject->getLanguage());
        self::assertSame('', $subject->getCopyright());
        self::assertNull($subject->getImage());
        self::assertTrue($subject->getCategories()->isEmpty());
        self::assertTrue($subject->getItems()->isEmpty());
        self::assertTrue($subject->getExtensionContents()->isEmpty());
    }
}
