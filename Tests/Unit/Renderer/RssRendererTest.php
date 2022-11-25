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
use Brotkrueml\FeedGenerator\Renderer\PathResolver;
use Brotkrueml\FeedGenerator\Renderer\RssRenderer;
use Brotkrueml\FeedGenerator\Renderer\Xml\XmlExtensionProcessor;
use Brotkrueml\FeedGenerator\Tests\Fixtures\Renderer\Rss\FeedWithEmptyDescription;
use Brotkrueml\FeedGenerator\Tests\Fixtures\Renderer\Rss\FeedWithEmptyLink;
use Brotkrueml\FeedGenerator\Tests\Fixtures\Renderer\Rss\FeedWithEmptyTitle;
use Brotkrueml\FeedGenerator\Tests\Fixtures\Renderer\Rss\FeedWithStyleSheet;
use Brotkrueml\FeedGenerator\Tests\Fixtures\Renderer\Rss\FullFeed;
use Brotkrueml\FeedGenerator\Tests\Fixtures\Renderer\Rss\FullItems;
use Brotkrueml\FeedGenerator\Tests\Fixtures\Renderer\Rss\ItemAttachmentWithEmptyType;
use Brotkrueml\FeedGenerator\Tests\Fixtures\Renderer\Rss\ItemAttachmentWithEmptyUri;
use Brotkrueml\FeedGenerator\Tests\Fixtures\Renderer\Rss\ItemWithEmptyTitleAndDescription;
use Brotkrueml\FeedGenerator\Tests\Fixtures\Renderer\Rss\MinimumFeed;
use Brotkrueml\FeedGenerator\Tests\Fixtures\Renderer\Rss\MinimumItems;
use PHPUnit\Framework\TestCase;

final class RssRendererTest extends TestCase
{
    private RssRenderer $subject;

    protected function setUp(): void
    {
        $extensionProcessorDummy = $this->createStub(XmlExtensionProcessor::class);

        $pathResolverStub = $this->createStub(PathResolver::class);
        $pathResolverStub
            ->method('getWebPath')
            ->willReturnCallback(static fn ($path) => $path);

        $this->subject = new RssRenderer($extensionProcessorDummy, $pathResolverStub);
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
        $this->expectException(MissingRequiredPropertyException::class);
        $this->expectExceptionMessage('At least one of item/title or item/description must be present.');

        $this->subject->render(new ItemWithEmptyTitleAndDescription(), 'https://example.org/feed.rss');
    }

    /**
     * @test
     */
    public function emptyAttachmentUriInItemThrowsException(): void
    {
        $this->expectException(MissingRequiredPropertyException::class);
        $this->expectExceptionMessage('Required property "item/enclosure/url" is missing.');

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

        yield 'Full feed' => [
            'feed' => new FullFeed(),
            'expectedFile' => __DIR__ . '/../../Fixtures/Renderer/Rss/FullFeed.xml',
        ];

        yield 'Feed with stylesheet' => [
            'feed' => new FeedWithStyleSheet(),
            'expectedFile' => __DIR__ . '/../../Fixtures/Renderer/Rss/FeedWithStyleSheet.xml',
        ];

        yield 'Minimum items' => [
            'feed' => new MinimumItems(),
            'expectedFile' => __DIR__ . '/../../Fixtures/Renderer/Rss/MinimumItems.xml',
        ];

        yield 'Full items' => [
            'feed' => new FullItems(),
            'expectedFile' => __DIR__ . '/../../Fixtures/Renderer/Rss/FullItems.xml',
        ];
    }
}
