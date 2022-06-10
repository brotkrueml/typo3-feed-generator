<?php

declare(strict_types=1);

/*
 * This file is part of the "feed_generator" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\FeedGenerator\Tests\Unit\Feed;

use Brotkrueml\FeedGenerator\Feed\Media;
use PHPUnit\Framework\TestCase;

final class MediaTest extends TestCase
{
    /**
     * @test
     */
    public function propertiesAreInitialisedCorrectlyWhenNotGiven(): void
    {
        $subject = new Media('some type', 'some url');

        self::assertSame('some type', $subject->getType());
        self::assertSame('some url', $subject->getUrl());
        self::assertSame(0, $subject->getLength());
        self::assertSame('', $subject->getTitle());
    }

    /**
     * @test
     */
    public function getLengthReturnsLengthCorrectly(): void
    {
        $subject = new Media('some type', 'some url', length: 42);

        self::assertSame(42, $subject->getLength());
    }

    /**
     * @test
     */
    public function getTitleReturnsTitleCorrectly(): void
    {
        $subject = new Media('some type', 'some url', title: 'some title');

        self::assertSame('some title', $subject->getTitle());
    }
}
