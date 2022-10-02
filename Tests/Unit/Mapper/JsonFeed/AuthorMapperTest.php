<?php

declare(strict_types=1);

/*
 * This file is part of the "feed_generator" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\FeedGenerator\Tests\Unit\Mapper\JsonFeed;

use Brotkrueml\FeedGenerator\Entity\Author;
use Brotkrueml\FeedGenerator\Mapper\JsonFeed\AuthorMapper;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Brotkrueml\FeedGenerator\Mapper\JsonFeed\AuthorMapper
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
    public function mapWithNameOnly(): void
    {
        $author = new Author('John Doe');

        $actual = $this->subject->map($author);

        self::assertSame('John Doe', $actual->getName());
        self::assertNull($actual->getUrl());
        self::assertNull($actual->getAvatar());
    }

    /**
     * @test
     */
    public function mapWithNameAndUrl(): void
    {
        $author = new Author('John Doe', uri: 'https://example.org/');

        $actual = $this->subject->map($author);

        self::assertSame('John Doe', $actual->getName());
        self::assertSame('https://example.org/', $actual->getUrl());
        self::assertNull($actual->getAvatar());
    }
}
