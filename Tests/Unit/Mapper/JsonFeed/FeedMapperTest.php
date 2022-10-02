<?php

declare(strict_types=1);

/*
 * This file is part of the "feed_generator" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\FeedGenerator\Tests\Unit\Mapper\JsonFeed;

use Brotkrueml\FeedGenerator\Mapper\JsonFeed\AuthorMapper;
use Brotkrueml\FeedGenerator\Mapper\JsonFeed\FeedMapper;
use Brotkrueml\FeedGenerator\Mapper\JsonFeed\ItemMapper;
use Brotkrueml\FeedGenerator\Tests\Fixtures\FeedConfiguration\EmptyFeed;
use Brotkrueml\FeedGenerator\Tests\Fixtures\FeedConfiguration\SomeFeed;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Brotkrueml\FeedGenerator\Mapper\JsonFeed\FeedMapper
 */
final class FeedMapperTest extends TestCase
{
    private FeedMapper $subject;

    protected function setUp(): void
    {
        $authorMapper = new AuthorMapper();
        $itemMapper = new ItemMapper($authorMapper);
        $this->subject = new FeedMapper($authorMapper, $itemMapper);
    }

    /**
     * @test
     */
    public function mapWithEmptyFeed(): void
    {
        $actual = $this->subject->map('https://example.org/empty-feed', new EmptyFeed());

        self::assertSame('https://example.org/empty-feed', $actual->getFeedUrl());
        self::assertSame('', $actual->getTitle());
        self::assertNull($actual->getHomepageUrl());
        self::assertNull($actual->getDescription());
        self::assertNull($actual->getUserComment());
        self::assertNull($actual->getNextUrl());
        self::assertNull($actual->getIcon());
        self::assertNull($actual->getFavicon());
        self::assertNull($actual->getAuthor());
        self::assertNull($actual->isExpired());
        self::assertSame([], $actual->getHubs());
        self::assertSame([], $actual->getItems());
    }

    /**
     * @test
     */
    public function mapWithTitle(): void
    {
        $actual = $this->subject->map('https://example.org/some-feed', new SomeFeed());

        self::assertSame('some title', $actual->getTitle());
    }

    /**
     * @test
     */
    public function mapWithDescription(): void
    {
        $actual = $this->subject->map('https://example.org/some-feed', new SomeFeed());

        self::assertSame('some description', $actual->getDescription());
    }

    /**
     * @test
     */
    public function mapWithLink(): void
    {
        $actual = $this->subject->map('https://example.org/some-feed', new SomeFeed());

        self::assertSame('some link', $actual->getHomepageUrl());
    }

    /**
     * @test
     */
    public function mapWithAuthors(): void
    {
        $actual = $this->subject->map('https://example.org/some-feed', new SomeFeed());

        self::assertSame('some author', $actual->getAuthor()->getName());
    }

    /**
     * @test
     */
    public function mapWithItems(): void
    {
        $actual = $this->subject->map('https://example.org/some-feed', new SomeFeed());

        self::assertCount(2, $actual->getItems());
        self::assertSame('some title', $actual->getItems()[0]->getTitle());
        self::assertSame('another title', $actual->getItems()[1]->getTitle());
    }
}
