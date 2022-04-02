<?php

declare(strict_types=1);


namespace Brotkrueml\FeedGenerator\Tests\Unit\Feed;

use Brotkrueml\FeedGenerator\Feed\Item;
use Brotkrueml\FeedGenerator\Feed\Media;
use PHPUnit\Framework\TestCase;

final class ItemTest extends TestCase
{
    /**
     * @test
     */
    public function propertiesAreInitialisedCorrectlyWhenNotGiven(): void
    {
        $subject = new Item();

        self::assertSame('', $subject->getTitle());
        self::assertSame('', $subject->getPublicId());
        self::assertNull($subject->getLastModified());
        self::assertSame('', $subject->getLink());
        self::assertSame('', $subject->getSummary());
        self::assertSame('', $subject->getContent());
        self::assertSame([], $subject->getMedias());
    }

    /**
     * @test
     */
    public function getTitleReturnsTitleCorrectly(): void
    {
        $subject = new Item(title: 'some title');

        self::assertSame('some title', $subject->getTitle());
    }

    /**
     * @test
     */
    public function getPublicIdReturnsPublicIdCorrectly(): void
    {
        $subject = new Item(publicId: 'some public id');

        self::assertSame('some public id', $subject->getPublicId());
    }

    /**
     * @test
     */
    public function getLastModifiedReturnsLastModifiedCorrectly(): void
    {
        $lastModified = new \DateTimeImmutable();

        $subject = new Item(lastModified: $lastModified);

        self::assertSame($lastModified, $subject->getLastModified());
    }

    /**
     * @test
     */
    public function getLinkReturnsLinkCorrectly(): void
    {
        $subject = new Item(link: 'some link');

        self::assertSame('some link', $subject->getLink());
    }

    /**
     * @test
     */
    public function getSummaryReturnsSummaryCorrectly(): void
    {
        $subject = new Item(summary: 'some summary');

        self::assertSame('some summary', $subject->getSummary());
    }

    /**
     * @test
     */
    public function getContentReturnsContentCorrectly(): void
    {
        $subject = new Item(content: 'some content');

        self::assertSame('some content', $subject->getContent());
    }

    /**
     * @test
     */
    public function getMediasReturnsMediasAsArrayCorrectly(): void
    {
        $media1 = new Media('some type', 'some url');
        $media2 = new Media('another type', 'another url');

        $subject = new Item(medias: [$media1, $media2]);

        self::assertSame([$media1, $media2], $subject->getMedias());
    }

    /**
     * @test
     */
    public function getMediasReturnMediasAsArrayIteratorCorrectly(): void
    {
        $media1 = new Media('some type', 'some url');
        $media2 = new Media('another type', 'another url');
        $iterator = new \ArrayIterator([$media1, $media2]);

        $subject = new Item(medias: $iterator);

        self::assertSame($iterator, $subject->getMedias());
    }
}
