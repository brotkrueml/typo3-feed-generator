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
use Brotkrueml\FeedGenerator\Entity\Item;
use Brotkrueml\FeedGenerator\Mapper\JsonFeed\AuthorMapper;
use Brotkrueml\FeedGenerator\Mapper\JsonFeed\ItemMapper;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Brotkrueml\FeedGenerator\Mapper\JsonFeed\ItemMapper
 */
final class ItemMapperTest extends TestCase
{
    private ItemMapper $subject;

    protected function setUp(): void
    {
        $this->subject = new ItemMapper(new AuthorMapper());
    }

    /**
     * @test
     */
    public function mapWithEmptyId(): void
    {
        $item = new Item();

        $actual = $this->subject->map($item);

        self::assertSame('', $actual->getId());
    }

    /**
     * @test
     */
    public function mapWithIdOnly(): void
    {
        $item = new Item();
        $item->setId('some id');

        $actual = $this->subject->map($item);

        self::assertSame('some id', $actual->getId());
        self::assertNull($actual->getUrl());
        self::assertNull($actual->getExternalUrl());
        self::assertNull($actual->getTitle());
        self::assertNull($actual->getContentHtml());
        self::assertNull($actual->getContentText());
        self::assertNull($actual->getSummary());
        self::assertNull($actual->getImage());
        self::assertNull($actual->getBannerImage());
        self::assertNull($actual->getDatePublished());
        self::assertNull($actual->getDateModified());
        self::assertNull($actual->getAuthor());
        self::assertSame([], $actual->getTags());
        self::assertSame([], $actual->getAttachments());
        self::assertSame([], $actual->getExtensions());
    }

    /**
     * @test
     */
    public function mapWithTitle(): void
    {
        $item = new Item();
        $item->setTitle('some title');

        $actual = $this->subject->map($item);

        self::assertSame('some title', $actual->getTitle());
    }

    /**
     * @test
     */
    public function mapWithContent(): void
    {
        $item = new Item();
        $item->setContent('some content');

        $actual = $this->subject->map($item);

        self::assertSame('some content', $actual->getContentHtml());
    }

    /**
     * @test
     */
    public function mapWithDescription(): void
    {
        $item = new Item();
        $item->setDescription('some description');

        $actual = $this->subject->map($item);

        self::assertSame('some description', $actual->getSummary());
    }

    /**
     * @test
     */
    public function mapWithDateCreatedInstanceOfDateTime(): void
    {
        $dateCreated = new \DateTime();

        $item = new Item();
        $item->setDatePublished($dateCreated);

        $actual = $this->subject->map($item);

        self::assertSame($dateCreated->format('c'), $actual->getDatePublished()->format('c'));
    }

    /**
     * @test
     */
    public function mapWithDateCreatedInstanceOfDateTimeImmutable(): void
    {
        $dateCreated = new \DateTimeImmutable();

        $item = new Item();
        $item->setDatePublished($dateCreated);

        $actual = $this->subject->map($item);

        self::assertSame($dateCreated->format('c'), $actual->getDatePublished()->format('c'));
    }

    /**
     * @test
     */
    public function mapWithDateModifiedInstanceOfDateTime(): void
    {
        $dateModified = new \DateTime();

        $item = new Item();
        $item->setDateModified($dateModified);

        $actual = $this->subject->map($item);

        self::assertSame($dateModified->format('c'), $actual->getDateModified()->format('c'));
    }

    /**
     * @test
     */
    public function mapWithDateModifiedInstanceOfDateTimeImmutable(): void
    {
        $dateModified = new \DateTimeImmutable();

        $item = new Item();
        $item->setDateModified($dateModified);

        $actual = $this->subject->map($item);

        self::assertSame($dateModified->format('c'), $actual->getDateModified()->format('c'));
    }

    /**
     * @test
     */
    public function mapWithAuthorsOnlyFirstAuthorIsAvailable(): void
    {
        $author1 = new Author('some author');
        $author2 = new Author('another author');

        $item = new Item();
        $item->setAuthors($author1, $author2);

        $actual = $this->subject->map($item);

        self::assertSame('some author', $actual->getAuthor()->getName());
    }
}
