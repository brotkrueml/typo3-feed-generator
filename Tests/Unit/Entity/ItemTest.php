<?php

declare(strict_types=1);

/*
 * This file is part of the "feed_generator" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\FeedGenerator\Tests\Unit\Entity;

use Brotkrueml\FeedGenerator\Entity\Author;
use Brotkrueml\FeedGenerator\Entity\Item;
use PHPUnit\Framework\TestCase;

final class ItemTest extends TestCase
{
    /**
     * @test
     */
    public function propertiesAreInitialisedCorrectlyWhenNotGiven(): void
    {
        $subject = new Item();

        self::assertSame('', $subject->getId());
        self::assertSame('', $subject->getTitle());
        self::assertSame('', $subject->getDescription());
        self::assertSame('', $subject->getContent());
        self::assertSame('', $subject->getLink());
        self::assertSame([], $subject->getAuthors());
        self::assertNull($subject->getDateCreated());
        self::assertNull($subject->getDateModified());
    }

    /**
     * @test
     */
    public function getIdReturnsPublicIdCorrectly(): void
    {
        $subject = (new Item())
            ->setId('some id');

        self::assertSame('some id', $subject->getId());
    }

    /**
     * @test
     */
    public function getTitleReturnsTitleCorrectly(): void
    {
        $subject = (new Item())
            ->setTitle('some title');

        self::assertSame('some title', $subject->getTitle());
    }

    /**
     * @test
     */
    public function getDescriptionReturnsDescriptionCorrectly(): void
    {
        $subject = (new Item())
            ->setDescription('some description');

        self::assertSame('some description', $subject->getDescription());
    }

    /**
     * @test
     */
    public function getContentReturnsContentCorrectly(): void
    {
        $subject = (new Item())
            ->setContent('some content');

        self::assertSame('some content', $subject->getContent());
    }

    /**
     * @test
     */
    public function getLinkReturnsLinkCorrectly(): void
    {
        $subject = (new Item())
            ->setLink('some link');

        self::assertSame('some link', $subject->getLink());
    }

    /**
     * @test
     */
    public function getAuthorsReturnsOneAuthorCorrectly(): void
    {
        $author = new Author('Some author');

        $subject = (new Item())
            ->setAuthors($author);

        self::assertSame([$author], $subject->getAuthors());
    }

    /**
     * @test
     */
    public function getAuthorsReturnsTwoAuthorsCorrectly(): void
    {
        $author1 = new Author('Some author');
        $author2 = new Author('Another author');

        $subject = (new Item())
            ->setAuthors($author1, $author2);

        self::assertSame([$author1, $author2], $subject->getAuthors());
    }

    /**
     * @test
     */
    public function getDateCreatedReturnsDateCreatedCorrectly(): void
    {
        $dateCreated = new \DateTimeImmutable();

        $subject = (new Item())
            ->setDateCreated($dateCreated);

        self::assertSame($dateCreated, $subject->getDateCreated());
    }

    /**
     * @test
     */
    public function getDateModifiedReturnsDateModifiedCorrectly(): void
    {
        $dateModified = new \DateTimeImmutable();

        $subject = (new Item())
            ->setDateModified($dateModified);

        self::assertSame($dateModified, $subject->getDateModified());
    }
}
