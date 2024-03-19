<?php

declare(strict_types=1);

/*
 * This file is part of the "feed_generator" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\FeedGenerator\Tests\Unit\Entity;

use Brotkrueml\FeedGenerator\Entity\Item;
use Brotkrueml\FeedGenerator\ValueObject\Attachment;
use Brotkrueml\FeedGenerator\ValueObject\Author;
use Brotkrueml\FeedGenerator\ValueObject\Category;
use Brotkrueml\FeedGenerator\ValueObject\Text;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(Item::class)]
final class ItemTest extends TestCase
{
    #[Test]
    public function propertiesAreInitialisedCorrectlyWhenNotGiven(): void
    {
        $subject = new Item();

        self::assertSame('', $subject->getId());
        self::assertSame('', $subject->getTitle());
        self::assertSame('', $subject->getDescription());
        self::assertSame('', $subject->getContent());
        self::assertSame('', $subject->getLink());
        self::assertTrue($subject->getAuthors()->isEmpty());
        self::assertNull($subject->getDatePublished());
        self::assertNull($subject->getDateModified());
        self::assertTrue($subject->getAttachments()->isEmpty());
        self::assertTrue($subject->getCategories()->isEmpty());
        self::assertTrue($subject->getExtensionContents()->isEmpty());
    }

    #[Test]
    public function getIdReturnsPublicIdCorrectly(): void
    {
        $subject = (new Item())
            ->setId('some id');

        self::assertSame('some id', $subject->getId());
    }

    #[Test]
    public function getTitleReturnsTitleCorrectly(): void
    {
        $subject = (new Item())
            ->setTitle('some title');

        self::assertSame('some title', $subject->getTitle());
    }

    #[Test]
    public function getDescriptionReturnsDescriptionAsStringCorrectly(): void
    {
        $subject = (new Item())
            ->setDescription('some description');

        self::assertSame('some description', $subject->getDescription());
    }

    #[Test]
    public function getDescriptionReturnsDescriptionAsTextObjectCorrectly(): void
    {
        $description = new Text('some description');

        $subject = (new Item())
            ->setDescription($description);

        self::assertSame($description, $subject->getDescription());
    }

    #[Test]
    public function getContentReturnsContentCorrectly(): void
    {
        $subject = (new Item())
            ->setContent('some content');

        self::assertSame('some content', $subject->getContent());
    }

    #[Test]
    public function getLinkReturnsLinkCorrectly(): void
    {
        $subject = (new Item())
            ->setLink('some link');

        self::assertSame('some link', $subject->getLink());
    }

    #[Test]
    public function getAuthorsReturnsOneAuthorCorrectly(): void
    {
        $author = new Author('Some author');

        $subject = (new Item())
            ->addAuthors($author);

        self::assertSame($author, $subject->getAuthors()->get(0));
    }

    #[Test]
    public function getAuthorsReturnsTwoAuthorsCorrectly(): void
    {
        $author1 = new Author('Some author');
        $author2 = new Author('Another author');

        $subject = (new Item())
            ->addAuthors($author1, $author2);

        self::assertSame($author1, $subject->getAuthors()->get(0));
        self::assertSame($author2, $subject->getAuthors()->get(1));
    }

    #[Test]
    public function getDatePublishedReturnsDatePublishedCorrectly(): void
    {
        $datePublished = new \DateTimeImmutable();

        $subject = (new Item())
            ->setDatePublished($datePublished);

        self::assertSame($datePublished, $subject->getDatePublished());
    }

    #[Test]
    public function getDateModifiedReturnsDateModifiedCorrectly(): void
    {
        $dateModified = new \DateTimeImmutable();

        $subject = (new Item())
            ->setDateModified($dateModified);

        self::assertSame($dateModified, $subject->getDateModified());
    }

    #[Test]
    public function getAttachmentsReturnsAttachmentsCorrectly(): void
    {
        $attachment1 = new Attachment('https://example.org/video.mp4');
        $attachment2 = new Attachment('https://example.org/audio.mp3');

        $subject = (new Item())
            ->addAttachments($attachment1, $attachment2);

        self::assertContains($attachment1, $subject->getAttachments());
        self::assertContains($attachment2, $subject->getAttachments());
    }

    #[Test]
    public function getCategoriesReturnsAttachmentsCorrectly(): void
    {
        $category1 = new Category('some category');
        $category2 = new Category('another category');

        $subject = (new Item())
            ->addCategories($category1, $category2);

        self::assertContains($category1, $subject->getCategories());
        self::assertContains($category2, $subject->getCategories());
    }
}
