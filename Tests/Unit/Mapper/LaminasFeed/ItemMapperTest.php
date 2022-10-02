<?php

declare(strict_types=1);

/*
 * This file is part of the "feed_generator" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\FeedGenerator\Tests\Unit\Mapper\LaminasFeed;

use Brotkrueml\FeedGenerator\Entity\Author;
use Brotkrueml\FeedGenerator\Entity\Item;
use Brotkrueml\FeedGenerator\Mapper\LaminasFeed\AuthorMapper;
use Brotkrueml\FeedGenerator\Mapper\LaminasFeed\ItemMapper;
use Laminas\Feed\Writer\Feed as LaminasFeed;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Brotkrueml\FeedGenerator\Mapper\LaminasFeed\ItemMapper
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
    public function mapReturnsLaminasEntryCorrectlyWhenAllPropertiesAreSet(): void
    {
        $dateCreated = new \DateTimeImmutable('2022-08-01 11:11:11');
        $dateModified = new \DateTimeImmutable('2022-08-01 12:12:12');

        $item = (new Item())
            ->setId('some id')
            ->setTitle('some title')
            ->setDescription('some description')
            ->setContent('some content')
            ->setLink('some link')
            ->setAuthors(new Author('some author'))
            ->setDatePublished($dateCreated)
            ->setDateModified($dateModified);

        $actual = $this->subject->map($item, new LaminasFeed());

        self::assertSame('some id', $actual->getId());
        self::assertSame('some title', $actual->getTitle());
        self::assertSame('some description', $actual->getDescription());
        self::assertSame('some content', $actual->getContent());
        self::assertSame('some link', $actual->getLink());
        self::assertSame([[
            'name' => 'some author',
        ]], $actual->getAuthors());
        self::assertSame($dateCreated, $actual->getDateCreated());
        self::assertSame($dateModified, $actual->getDateModified());
    }

    /**
     * @test
     */
    public function mapReturnsLaminasEntryCorrectlyWhenNoPropertiesAreSet(): void
    {
        $actual = $this->subject->map(new Item(), new LaminasFeed());

        self::assertNull($actual->getId());
        self::assertNull($actual->getTitle());
        self::assertNull($actual->getDescription());
        self::assertNull($actual->getContent());
        self::assertNull($actual->getLink());
        self::assertNull($actual->getAuthors());
        self::assertNull($actual->getDateCreated());
        self::assertNull($actual->getDateModified());
    }

    /**
     * @test
     */
    public function mapReturnsLaminasEntryCorrectlyWhenTwoAuthorsAreSet(): void
    {
        $author1 = new Author('some author');
        $author2 = new Author('another author');

        $item = (new Item())
            ->setAuthors($author1, $author2);

        $actual = $this->subject->map($item, new LaminasFeed());

        self::assertSame(
            [
                [
                    'name' => 'some author',
                ],
                [
                    'name' => 'another author',
                ],
            ],
            $actual->getAuthors()
        );
    }
}
