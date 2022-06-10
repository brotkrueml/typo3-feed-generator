<?php

declare(strict_types=1);

/*
 * This file is part of the "feed_generator" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\FeedGenerator\Tests\Unit\Mapper;

use Brotkrueml\FeedGenerator\Feed\Author;
use Brotkrueml\FeedGenerator\Feed\Item;
use Brotkrueml\FeedGenerator\Feed\Media;
use Brotkrueml\FeedGenerator\Mapper\AuthorMapper;
use Brotkrueml\FeedGenerator\Mapper\ItemMapper;
use Brotkrueml\FeedGenerator\Mapper\MediaMapper;
use PHPUnit\Framework\TestCase;

final class ItemMapperTest extends TestCase
{
    private ItemMapper $subject;

    protected function setUp(): void
    {
        $this->subject = new ItemMapper(new AuthorMapper(), new MediaMapper());
    }

    /**
     * @test
     */
    public function mapReturnsFeedIoItemCorrectlyWhenOnlyStringArgumentsAreGiven(): void
    {
        $item = new Item(
            title: 'some title',
            publicId: 'some public id',
            link: 'some link',
            summary: 'some summary',
            content: 'some content',
        );

        $actual = $this->subject->map($item);

        self::assertSame('some title', $actual->getTitle());
        self::assertSame('some public id', $actual->getPublicId());
        self::assertSame('some link', $actual->getLink());
        self::assertSame('some summary', $actual->getSummary());
        self::assertSame('some content', $actual->getContent());
        self::assertNull($actual->getLastModified());
        self::assertNull($actual->getAuthor());
        self::assertCount(0, $actual->getMedias());
    }

    /**
     * @test
     */
    public function mapReturnsFeedIoItemCorrectlyWhenLastModifiedAsDateTimeIsGiven(): void
    {
        $lastModified = '2022-04-02 20:22:02';
        $item = new Item(
            lastModified: new \DateTime($lastModified),
        );

        $actual = $this->subject->map($item);

        self::assertSame($lastModified, $actual->getLastModified()->format('Y-m-d H:i:s'));
    }

    /**
     * @test
     */
    public function mapReturnsFeedIoItemCorrectlyWhenLastModifiedAsDateTimeImmutableIsGiven(): void
    {
        $lastModified = '2022-04-02 20:22:02';
        $item = new Item(
            lastModified: new \DateTimeImmutable($lastModified),
        );

        $actual = $this->subject->map($item);

        self::assertSame($lastModified, $actual->getLastModified()->format('Y-m-d H:i:s'));
    }

    /**
     * @test
     */
    public function mapReturnsFeedIoItemCorrectlyWhenAuthorIsGiven(): void
    {
        $author = new Author('some name');

        $item = new Item(
            author: $author,
        );

        $actual = $this->subject->map($item);

        self::assertInstanceOf(\FeedIo\Feed\Item\AuthorInterface::class, $actual->getAuthor());
        self::assertSame('some name', $actual->getAuthor()->getName());
    }

    /**
     * @test
     */
    public function mapReturnsFeedIoItemCorrectlyWhenOneMediaIsGiven(): void
    {
        $media = new Media('some type', 'some url');

        $item = new Item(
            medias: [$media],
        );

        $actual = $this->subject->map($item);

        self::assertCount(1, $actual->getMedias());
        self::assertInstanceOf(\FeedIo\Feed\Item\Media::class, $actual->getMedias()->current());
        self::assertSame('some type', $actual->getMedias()->current()->getType());
    }

    /**
     * @test
     */
    public function mapReturnsFeedIoItemCorrectlyWhenTwoMediaAreGiven(): void
    {
        $media1 = new Media('some type 1', 'some url 1');
        $media2 = new Media('some type 2', 'some url 2');

        $item = new Item(
            medias: [$media1, $media2],
        );

        $actual = $this->subject->map($item);

        $medias = $actual->getMedias();
        self::assertCount(2, $actual->getMedias());
        self::assertInstanceOf(\FeedIo\Feed\Item\Media::class, $medias->current());
        self::assertSame('some type 1', $medias->current()->getType());
        $medias->next();
        self::assertInstanceOf(\FeedIo\Feed\Item\Media::class, $medias->current());
        self::assertSame('some type 2', $medias->current()->getType());
    }
}
