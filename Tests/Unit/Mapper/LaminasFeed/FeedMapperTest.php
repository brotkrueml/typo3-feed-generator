<?php

declare(strict_types=1);

/*
 * This file is part of the "feed_generator" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\FeedGenerator\Tests\Unit\Mapper\LaminasFeed;

use Brotkrueml\FeedGenerator\Contract\ItemInterface;
use Brotkrueml\FeedGenerator\Format\FeedFormat;
use Brotkrueml\FeedGenerator\Mapper\LaminasFeed\AuthorMapper;
use Brotkrueml\FeedGenerator\Mapper\LaminasFeed\CategoryMapper;
use Brotkrueml\FeedGenerator\Mapper\LaminasFeed\FeedMapper;
use Brotkrueml\FeedGenerator\Mapper\LaminasFeed\ImageMapper;
use Brotkrueml\FeedGenerator\Mapper\LaminasFeed\ItemMapper;
use Brotkrueml\FeedGenerator\Tests\Fixtures\FeedConfiguration\EmptyFeed;
use Brotkrueml\FeedGenerator\Tests\Fixtures\FeedConfiguration\FeedCategoryFeed;
use Brotkrueml\FeedGenerator\Tests\Fixtures\FeedConfiguration\SomeFeed;
use Laminas\Feed\Writer\Entry as LaminasEntry;
use Laminas\Feed\Writer\Feed as LaminasFeed;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Brotkrueml\FeedGenerator\Mapper\LaminasFeed\FeedMapper
 */
final class FeedMapperTest extends TestCase
{
    private FeedMapper $subject;

    protected function setUp(): void
    {
        $itemMapper = new class() extends ItemMapper {
            /**
             * @noinspection PhpMissingParentConstructorInspection
             */
            public function __construct()
            {
            }

            public function map(ItemInterface $item, LaminasFeed $laminasFeed): LaminasEntry
            {
                $laminasEntry = $laminasFeed->createEntry();
                $laminasEntry->setTitle($item->getTitle());

                return $laminasEntry;
            }
        };

        $this->subject = new FeedMapper(
            new AuthorMapper(),
            new CategoryMapper(),
            new ImageMapper(),
            $itemMapper
        );
    }

    /**
     * @test
     */
    public function mapReturnsLaminasEntryCorrectlyWhenAllArgumentsAreGiven(): void
    {
        $actual = $this->subject->map(
            'https://example.org/some-feed-link',
            new SomeFeed(),
            FeedFormat::ATOM,
        );

        self::assertSame('some id', $actual->getId());
        self::assertSame('some title', $actual->getTitle());
        self::assertSame('some link', $actual->getLink());
        self::assertSame([
            'atom' => 'https://example.org/some-feed-link',
        ], $actual->getFeedLinks());
        self::assertSame('some description', $actual->getDescription());
        self::assertSame('01.08.2022 11:11:11', $actual->getDateCreated()->format('d.m.Y H:i:s'));
        self::assertSame('01.08.2022 12:12:12', $actual->getDateModified()->format('d.m.Y H:i:s'));
        self::assertSame('01.08.2022 13:13:13', $actual->getLastBuildDate()->format('d.m.Y H:i:s'));
        self::assertSame('some language', $actual->getLanguage());
        self::assertSame('some copyright', $actual->getCopyright());
        self::assertSame([
            'uri' => 'some uri',
        ], $actual->getImage());
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
        self::assertCount(2, $actual);

        self::assertArrayHasKey('name', $actual->getGenerator());
        self::assertSame('TYPO3 Feed Generator', $actual->getGenerator()['name']);
        self::assertArrayHasKey('uri', $actual->getGenerator());
        self::assertSame('https://extensions.typo3.org/extension/feed_generator', $actual->getGenerator()['uri']);
    }

    /**
     * @test
     */
    public function mapReturnsLaminasEntryCorrectlyWhenNoArgumentsAreGiven(): void
    {
        $actual = $this->subject->map(
            'https://example.org/empty-feed-link',
            new EmptyFeed(),
            FeedFormat::ATOM,
        );

        self::assertNull($actual->getId());
        self::assertNull($actual->getTitle());
        self::assertNull($actual->getLink());
        self::assertSame([
            'atom' => 'https://example.org/empty-feed-link',
        ], $actual->getFeedLinks());
        self::assertNull($actual->getDescription());
        self::assertInstanceOf(\DateTimeInterface::class, $actual->getDateCreated());
        self::assertInstanceOf(\DateTimeInterface::class, $actual->getDateModified());
        self::assertInstanceOf(\DateTimeInterface::class, $actual->getLastBuildDate());
        self::assertNull($actual->getLanguage());
        self::assertNull($actual->getCopyright());
        self::assertNull($actual->getImage());
        self::assertCount(0, $actual);
    }

    /**
     * @test
     */
    public function mapReturnsLaminasEntryCorrectlyWhenCategoryAwareInterfaceIsImplemented(): void
    {
        $actual = $this->subject->map(
            'https://example.org/some-feed-link',
            new FeedCategoryFeed(),
            FeedFormat::ATOM,
        );

        self::assertCount(2, $actual->getCategories());
        self::assertSame([
            'term' => 'some-term',
        ], $actual->getCategories()[0]);
        self::assertSame([
            'term' => 'another-term',
        ], $actual->getCategories()[1]);
    }
}
