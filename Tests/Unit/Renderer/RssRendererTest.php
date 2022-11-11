<?php

declare(strict_types=1);

/*
 * This file is part of the "feed_generator" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\FeedGenerator\Tests\Unit\Renderer;

use Brotkrueml\FeedGenerator\Contract\FeedInterface;
use Brotkrueml\FeedGenerator\Renderer\MissingRequiredPropertyException;
use Brotkrueml\FeedGenerator\Renderer\MissingRequiredRssFeedProperties;
use Brotkrueml\FeedGenerator\Renderer\RssRenderer;
use Brotkrueml\FeedGenerator\Renderer\WrongImageDimensionException;
use Brotkrueml\FeedGenerator\Tests\Fixtures\Renderer\Rss\CategoryFeed;
use Brotkrueml\FeedGenerator\Tests\Fixtures\Renderer\Rss\FeedImageWithEmptyLink;
use Brotkrueml\FeedGenerator\Tests\Fixtures\Renderer\Rss\FeedImageWithEmptyTitle;
use Brotkrueml\FeedGenerator\Tests\Fixtures\Renderer\Rss\FeedImageWithEmptyUri;
use Brotkrueml\FeedGenerator\Tests\Fixtures\Renderer\Rss\FeedImageWithWidthIsGreaterThanAllowed;
use Brotkrueml\FeedGenerator\Tests\Fixtures\Renderer\Rss\FeedWithEmptyDescription;
use Brotkrueml\FeedGenerator\Tests\Fixtures\Renderer\Rss\FeedWithEmptyLink;
use Brotkrueml\FeedGenerator\Tests\Fixtures\Renderer\Rss\FeedWithEmptyTitle;
use Brotkrueml\FeedGenerator\Tests\Fixtures\Renderer\Rss\FeedWithMinimumImageProperties;
use Brotkrueml\FeedGenerator\Tests\Fixtures\Renderer\Rss\FullFeed;
use Brotkrueml\FeedGenerator\Tests\Fixtures\Renderer\Rss\FullItems;
use Brotkrueml\FeedGenerator\Tests\Fixtures\Renderer\Rss\ItemAttachmentWithEmptyLength;
use Brotkrueml\FeedGenerator\Tests\Fixtures\Renderer\Rss\ItemAttachmentWithEmptyType;
use Brotkrueml\FeedGenerator\Tests\Fixtures\Renderer\Rss\ItemAttachmentWithEmptyUri;
use Brotkrueml\FeedGenerator\Tests\Fixtures\Renderer\Rss\ItemWithEmptyTitleAndDescription;
use Brotkrueml\FeedGenerator\Tests\Fixtures\Renderer\Rss\ItemWithUndefinedIdButLink;
use Brotkrueml\FeedGenerator\Tests\Fixtures\Renderer\Rss\MinimumFeed;
use Brotkrueml\FeedGenerator\Tests\Fixtures\Renderer\Rss\MinimumItems;
use PHPUnit\Framework\TestCase;

final class RssRendererTest extends TestCase
{
    private RssRenderer $subject;

    protected function setUp(): void
    {
        $this->subject = new RssRenderer();
    }

    /**
     * @test
     */
    public function emptyFeedTitleThrowsException(): void
    {
        $this->expectException(MissingRequiredPropertyException::class);
        $this->expectExceptionMessage('Required property "title" is missing.');

        $this->subject->render(new FeedWithEmptyTitle(), 'https://example.org/feed.rss');
    }

    /**
     * @test
     */
    public function emptyFeedLinkThrowsException(): void
    {
        $this->expectException(MissingRequiredPropertyException::class);
        $this->expectExceptionMessage('Required property "link" is missing.');

        $this->subject->render(new FeedWithEmptyLink(), 'https://example.org/feed.rss');
    }

    /**
     * @test
     */
    public function emptyFeedDescriptionThrowsException(): void
    {
        $this->expectException(MissingRequiredPropertyException::class);
        $this->expectExceptionMessage('Required property "description" is missing.');

        $this->subject->render(new FeedWithEmptyDescription(), 'https://example.org/feed.rss');
    }

    /**
     * @test
     */
    public function emptyTitleAndDescriptionInItemThrowsException(): void
    {
        $this->expectException(MissingRequiredRssFeedProperties::class);
        $this->expectExceptionMessage('At least one of title or description must be present in an item element.');

        $this->subject->render(new ItemWithEmptyTitleAndDescription(), 'https://example.org/feed.rss');
    }

    /**
     * @test
     */
    public function emptyAttachmentUriInItemThrowsException(): void
    {
        $this->expectException(MissingRequiredPropertyException::class);
        $this->expectExceptionMessage('Required property "item/enclosure/uri" is missing.');

        $this->subject->render(new ItemAttachmentWithEmptyUri(), 'https://example.org/feed.rss');
    }

    /**
     * @test
     */
    public function emptyAttachmentTypeInItemThrowsException(): void
    {
        $this->expectException(MissingRequiredPropertyException::class);
        $this->expectExceptionMessage('Required property "item/enclosure/type" is missing.');

        $this->subject->render(new ItemAttachmentWithEmptyType(), 'https://example.org/feed.rss');
    }

    /**
     * @test
     */
    public function emptyAttachmentLengthInItemThrowsException(): void
    {
        $this->expectException(MissingRequiredPropertyException::class);
        $this->expectExceptionMessage('Required property "item/enclosure/length" is missing.');

        $this->subject->render(new ItemAttachmentWithEmptyLength(), 'https://example.org/feed.rss');
    }

    /**
     * @test
     * @dataProvider providerForIncorrectFeedImageProperties
     */
    public function emptyUriInFeedImageThrowsException(FeedInterface $feed, string $expectedException, string $expectedExceptionMessage): void
    {
        $this->expectException($expectedException);
        $this->expectExceptionMessage($expectedExceptionMessage);

        $this->subject->render($feed, 'https://example.org/feed.rss');
    }

    public function providerForIncorrectFeedImageProperties(): iterable
    {
        yield 'Empty uri' => [
            'feed' => new FeedImageWithEmptyUri(),
            'expectedException' => MissingRequiredPropertyException::class,
            'expectedExceptionMessage' => 'Required property "channel/image/uri" is missing.',
        ];

        yield 'Empty title' => [
            'feed' => new FeedImageWithEmptyTitle(),
            'expectedException' => MissingRequiredPropertyException::class,
            'expectedExceptionMessage' => 'Required property "channel/image/title" is missing.',
        ];

        yield 'Empty link' => [
            'feed' => new FeedImageWithEmptyLink(),
            'expectedException' => MissingRequiredPropertyException::class,
            'expectedExceptionMessage' => 'Required property "channel/image/link" is missing.',
        ];

        yield 'Width is greater than allowed' => [
            'feed' => new FeedImageWithWidthIsGreaterThanAllowed(),
            'expectedException' => WrongImageDimensionException::class,
            'expectedExceptionMessage' => 'The width of an image is "145" which is higher than the maximum allowed (144).',
        ];
    }

    /**
     * @test
     * @dataProvider providerForFeed
     */
    public function differentScenarios(FeedInterface $feed, string $expectedFile): void
    {
        $actual = $this->subject->render($feed, 'https://example.org/feed.rss');

        self::assertXmlStringEqualsXmlFile($expectedFile, $actual);
    }

    public function providerForFeed(): iterable
    {
        yield 'Minimum feed' => [
            'feed' => new MinimumFeed(),
            'expectedFile' => __DIR__ . '/../../Fixtures/Renderer/Rss/MinimumFeed.xml',
        ];

        yield 'Feed with category' => [
            'feed' => new CategoryFeed(),
            'expectedFile' => __DIR__ . '/../../Fixtures/Renderer/Rss/CategoryFeed.xml',
        ];

        yield 'Feed with mimimum image properties' => [
            'feed' => new FeedWithMinimumImageProperties(),
            'expectedFile' => __DIR__ . '/../../Fixtures/Renderer/Rss/FeedWithMinimumImageProperties.xml',
        ];

        yield 'Full feed' => [
            'feed' => new FullFeed(),
            'expectedFile' => __DIR__ . '/../../Fixtures/Renderer/Rss/FullFeed.xml',
        ];

        yield 'Minimum items' => [
            'feed' => new MinimumItems(),
            'expectedFile' => __DIR__ . '/../../Fixtures/Renderer/Rss/MinimumItems.xml',
        ];

        yield 'Item with undefined ID but link' => [
            'feed' => new ItemWithUndefinedIdButLink(),
            'expectedFile' => __DIR__ . '/../../Fixtures/Renderer/Rss/ItemWithUndefinedIdButLink.xml',
        ];

        yield 'Full items' => [
            'feed' => new FullItems(),
            'expectedFile' => __DIR__ . '/../../Fixtures/Renderer/Rss/FullItems.xml',
        ];
    }
}
