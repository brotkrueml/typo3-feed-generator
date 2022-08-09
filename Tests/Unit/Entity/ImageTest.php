<?php

declare(strict_types=1);

/*
 * This file is part of the "feed_generator" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\FeedGenerator\Tests\Unit\Entity;

use Brotkrueml\FeedGenerator\Entity\Image;
use PHPUnit\Framework\TestCase;

final class ImageTest extends TestCase
{
    /**
     * @test
     */
    public function propertiesAreInitialisedCorrectlyWhenNotGiven(): void
    {
        $subject = new Image('Some URI');

        self::assertSame('Some URI', $subject->getUri());
        self::assertSame('', $subject->getTitle());
        self::assertSame('', $subject->getLink());
        self::assertSame(0, $subject->getWidth());
        self::assertSame(0, $subject->getHeight());
        self::assertSame('', $subject->getDescription());
    }

    /**
     * @test
     */
    public function getTitleReturnsTitleCorrectly(): void
    {
        $subject = new Image('Some URI', title: 'Some title');

        self::assertSame('Some title', $subject->getTitle());
    }

    /**
     * @test
     */
    public function getLinkReturnsLinkCorrectly(): void
    {
        $subject = new Image('Some URI', link: 'Some link');

        self::assertSame('Some link', $subject->getLink());
    }

    /**
     * @test
     */
    public function getWidthReturnsWidthCorrectly(): void
    {
        $subject = new Image('Some URI', width: 123);

        self::assertSame(123, $subject->getWidth());
    }

    /**
     * @test
     */
    public function getHeightReturnsHeightCorrectly(): void
    {
        $subject = new Image('Some URI', height: 234);

        self::assertSame(234, $subject->getHeight());
    }

    /**
     * @test
     */
    public function getDescriptionReturnsDescriptionCorrectly(): void
    {
        $subject = new Image('Some URI', description: 'Some description');

        self::assertSame('Some description', $subject->getDescription());
    }
}
