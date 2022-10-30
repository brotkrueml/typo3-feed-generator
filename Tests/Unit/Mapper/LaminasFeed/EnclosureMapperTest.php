<?php

declare(strict_types=1);

/*
 * This file is part of the "feed_generator" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\FeedGenerator\Tests\Unit\Mapper\LaminasFeed;

use Brotkrueml\FeedGenerator\Contract\EnclosureInterface;
use Brotkrueml\FeedGenerator\Mapper\LaminasFeed\EnclosureMapper;
use PHPUnit\Framework\TestCase;

final class EnclosureMapperTest extends TestCase
{
    private EnclosureMapper $subject;

    protected function setUp(): void
    {
        $this->subject = new EnclosureMapper();
    }

    /**
     * @test
     */
    public function mapReturnsEnclosureWithAllPropertiesGivenCorrectly(): void
    {
        $enclosure = new class() implements EnclosureInterface {
            public function getUri(): string
            {
                return 'https://example.org/video.mp4';
            }

            public function getType(): string
            {
                return 'video/mp4';
            }

            public function getLength(): int
            {
                return 12345678;
            }
        };

        $actual = $this->subject->map($enclosure);

        self::assertCount(3, $actual);
        self::assertArrayHasKey('uri', $actual);
        self::assertSame('https://example.org/video.mp4', $actual['uri']);
        self::assertArrayHasKey('type', $actual);
        self::assertSame('video/mp4', $actual['type']);
        self::assertArrayHasKey('length', $actual);
        self::assertSame('12345678', $actual['length']);
    }

    /**
     * @test
     */
    public function mapReturnsLaminasEnclosureWithOnlyRequiredPropertiesGivenCorrectly(): void
    {
        $enclosure = new class() implements EnclosureInterface {
            public function getUri(): string
            {
                return 'https://example.org/video.mp4';
            }

            public function getType(): string
            {
                return '';
            }

            public function getLength(): int
            {
                return 0;
            }
        };

        $actual = $this->subject->map($enclosure);

        self::assertCount(1, $actual);
        self::assertArrayHasKey('uri', $actual);
        self::assertSame('https://example.org/video.mp4', $actual['uri']);
    }
}
