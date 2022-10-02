<?php

declare(strict_types=1);

/*
 * This file is part of the "feed_generator" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\FeedGenerator\Tests\Unit\Mapper\LaminasFeed;

use Brotkrueml\FeedGenerator\Contract\AuthorInterface;
use Brotkrueml\FeedGenerator\Mapper\LaminasFeed\AuthorMapper;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Brotkrueml\FeedGenerator\Mapper\LaminasFeed\AuthorMapper
 */
final class AuthorMapperTest extends TestCase
{
    private AuthorMapper $subject;

    protected function setUp(): void
    {
        $this->subject = new AuthorMapper();
    }

    /**
     * @test
     */
    public function mapReturnsLaminasAuthorWithAllPropertiesGivenCorrectly(): void
    {
        $author = new class() implements AuthorInterface {
            public function getName(): string
            {
                return 'some name';
            }

            public function getUri(): string
            {
                return 'some uri';
            }

            public function getEmail(): string
            {
                return 'some email';
            }
        };

        $actual = $this->subject->map($author);

        self::assertCount(3, $actual);
        self::assertArrayHasKey('name', $actual);
        self::assertSame('some name', $actual['name']);
        self::assertArrayHasKey('email', $actual);
        self::assertSame('some email', $actual['email']);
        self::assertArrayHasKey('uri', $actual);
        self::assertSame('some uri', $actual['uri']);
    }

    /**
     * @test
     */
    public function mapReturnsLaminasAuthorWithOnlyRequiredPropertiesGivenCorrectly(): void
    {
        $author = new class() implements AuthorInterface {
            public function getName(): string
            {
                return 'some name';
            }

            public function getUri(): string
            {
                return '';
            }

            public function getEmail(): string
            {
                return '';
            }
        };

        $actual = $this->subject->map($author);

        self::assertCount(1, $actual);
        self::assertArrayHasKey('name', $actual);
        self::assertSame('some name', $actual['name']);
    }
}
