<?php

declare(strict_types=1);


namespace Brotkrueml\FeedGenerator\Tests\Unit\Mapper;

use Brotkrueml\FeedGenerator\Feed\Author;
use Brotkrueml\FeedGenerator\Feed\AuthorInterface;
use Brotkrueml\FeedGenerator\Feed\FeedInterface;
use Brotkrueml\FeedGenerator\Feed\Item;
use Brotkrueml\FeedGenerator\Feed\ItemInterface;
use Brotkrueml\FeedGenerator\Mapper\AuthorMapper;
use Brotkrueml\FeedGenerator\Mapper\FeedMapper;
use Brotkrueml\FeedGenerator\Mapper\ItemMapper;
use PHPUnit\Framework\TestCase;

final class FeedMapperTest extends TestCase
{
    private FeedMapper $subject;

    protected function setUp(): void
    {
        $itemMapper = new class() extends ItemMapper {
            public function __construct()
            {
            }

            public function map(ItemInterface $item): \FeedIo\Feed\Item
            {
                $feedIoItem = new \FeedIo\Feed\Item();
                $feedIoItem->setTitle($item->getTitle());

                return $feedIoItem;
            }
        };

        $this->subject = new FeedMapper(new AuthorMapper(), $itemMapper);
    }

    /**
     * @test
     */
    public function mapReturnsFeedIoItemCorrectlyWhenOnlyStringArgumentsAreGiven(): void
    {
        $actual = $this->subject->map($this->buildFeed());

        self::assertSame('some description', $actual->getDescription());
        self::assertSame('some language', $actual->getLanguage());
        self::assertSame('some logo', $actual->getLogo());
        self::assertCount(0, $actual);
        self::assertNull($actual->getLastModified());
        self::assertSame('some title', $actual->getTitle());
        self::assertSame('some public id', $actual->getPublicId());
        self::assertSame('some link', $actual->getLink());
        self::assertNull($actual->getAuthor());
    }

    /**
     * @test
     */
    public function mapReturnsFeedIoItemCorrectlyWhenOneItemIsGiven(): void
    {
        $feed = $this->buildFeed(items: [new Item(title: 'some title')]);

        $actual = $this->subject->map($feed);

        self::assertCount(1, $actual);
        self::assertSame('some title', $actual->current()->getTitle());
    }

    /**
     * @test
     */
    public function mapReturnsFeedIoItemCorrectlyWhenTwoItemsAreGiven(): void
    {
        $feed = $this->buildFeed(items: [
            new Item(title: 'some title 1'),
            new Item(title: 'some title 2'),
        ]);

        $actual = $this->subject->map($feed);

        self::assertCount(2, $actual);
        self::assertSame('some title 1', $actual->current()->getTitle());
        $actual->next();
        self::assertSame('some title 2', $actual->current()->getTitle());
    }

    /**
     * @test
     */
    public function mapReturnsFeedIoItemCorrectlyWhenLastModifiedIsAsDateTimeGiven(): void
    {
        $date = '2022-04-02 21:11:11';
        $feed = $this->buildFeed(lastModified: new \DateTime($date));

        $actual = $this->subject->map($feed);

        self::assertSame($date, $actual->getLastModified()->format('Y-m-d H:i:s'));
    }

    /**
     * @test
     */
    public function mapReturnsFeedIoItemCorrectlyWhenLastModifiedIsAsDateTimeImmutableGiven(): void
    {
        $date = '2022-04-02 21:11:11';
        $feed = $this->buildFeed(lastModified: new \DateTimeImmutable($date));

        $actual = $this->subject->map($feed);

        self::assertSame($date, $actual->getLastModified()->format('Y-m-d H:i:s'));
    }

    /**
     * @test
     */
    public function mapReturnsFeedIoItemCorrectlyWhenAuthorIsGiven(): void
    {
        $feed = $this->buildFeed(author: new Author('some author'));

        $actual = $this->subject->map($feed);

        self::assertSame('some author', $actual->getAuthor()->getName());
    }

    private function buildFeed(
        array $items = [],
        ?\DateTimeInterface $lastModified = null,
        ?AuthorInterface $author = null,
    ): FeedInterface
    {
        return new class($items, $lastModified, $author) implements FeedInterface {
            public function __construct(
                private readonly array $items,
                private readonly ?\DateTimeInterface $lastModified,
                private readonly ?AuthorInterface $author,
            )
            {
            }

            public function getDescription(): string
            {
                return 'some description';
            }

            public function getLanguage(): string
            {
                return 'some language';
            }

            public function getLogo(): string
            {
                return 'some logo';
            }

            /**
             * @return ItemInterface[]
             */
            public function getItems(): array
            {
                return $this->items;
            }

            public function getLastModified(): ?\DateTimeInterface
            {
                return $this->lastModified;
            }

            public function getTitle(): string
            {
                return 'some title';
            }

            public function getPublicId(): string
            {
                return 'some public id';
            }

            public function getLink(): string
            {
                return 'some link';
            }

            public function getAuthor(): ?AuthorInterface
            {
                return $this->author;
            }
        };
    }
}
