<?php

declare(strict_types=1);

/*
 * This file is part of the "feed_generator" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\FeedGenerator\Tests\Unit\ValueObject;

use Brotkrueml\FeedGenerator\ValueObject\Attachment;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class AttachmentTest extends TestCase
{
    #[Test]
    public function propertiesAreInitialisedCorrectlyWhenNotGiven(): void
    {
        $subject = new Attachment('https://example.org/video.mp4');

        self::assertSame('https://example.org/video.mp4', $subject->getUrl());
        self::assertSame('', $subject->getType());
        self::assertSame(0, $subject->getLength());
    }

    #[Test]
    public function getTypeReturnsTypeCorrectly(): void
    {
        $subject = new Attachment('https://example.org/video.mp4', 'video/mp4');

        self::assertSame('video/mp4', $subject->getType());
    }

    #[Test]
    public function getLengthReturnsLengthCorrectly(): void
    {
        $subject = new Attachment('https://example.org/video.mp4', length: 1234567);

        self::assertSame(1234567, $subject->getLength());
    }
}
