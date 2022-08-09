<?php

declare(strict_types=1);

/*
 * This file is part of the "feed_generator" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\FeedGenerator\Tests\Unit\Mapper;

use Brotkrueml\FeedGenerator\Entity\CategoryInterface;
use Brotkrueml\FeedGenerator\Mapper\CategoryMapper;
use PHPUnit\Framework\TestCase;

final class CategoryMapperTest extends TestCase
{
    private CategoryMapper $subject;

    protected function setUp(): void
    {
        $this->subject = new CategoryMapper();
    }

    /**
     * @test
     */
    public function mapReturnsLaminasCategoryWithAllPropertiesGivenCorrectly(): void
    {
        $category = new class() implements CategoryInterface {
            public function getTerm(): string
            {
                return 'some-term';
            }

            public function getScheme(): string
            {
                return 'https://example.org/some-term';
            }

            public function getLabel(): string
            {
                return 'Some Label';
            }
        };

        $actual = $this->subject->map($category);

        self::assertCount(3, $actual);
        self::assertArrayHasKey('term', $actual);
        self::assertSame('some-term', $actual['term']);
        self::assertArrayHasKey('scheme', $actual);
        self::assertSame('https://example.org/some-term', $actual['scheme']);
        self::assertArrayHasKey('label', $actual);
        self::assertSame('Some Label', $actual['label']);
    }

    /**
     * @test
     */
    public function mapReturnsLaminasCategoryWithOnlyRequiredPropertiesGivenCorrectly(): void
    {
        $category = new class() implements CategoryInterface {
            public function getTerm(): string
            {
                return 'some-term';
            }

            public function getScheme(): string
            {
                return '';
            }

            public function getLabel(): string
            {
                return '';
            }
        };

        $actual = $this->subject->map($category);

        self::assertCount(1, $actual);
        self::assertArrayHasKey('term', $actual);
        self::assertSame('some-term', $actual['term']);
    }
}
