<?php

declare(strict_types=1);

/*
 * This file is part of the "feed_generator" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\FeedGenerator\Tests\Unit\Renderer;

use Brotkrueml\FeedGenerator\Configuration\ExtensionRegistryInterface;
use Brotkrueml\FeedGenerator\Contract\ExtensionContentInterface;
use Brotkrueml\FeedGenerator\Contract\FeedInterface;
use Brotkrueml\FeedGenerator\Contract\JsonExtensionInterface;
use Brotkrueml\FeedGenerator\Contract\XmlExtensionInterface;
use Brotkrueml\FeedGenerator\Format\FeedFormat;
use Brotkrueml\FeedGenerator\Renderer\JsonRenderer;
use Brotkrueml\FeedGenerator\Renderer\MissingRequiredPropertyException;
use Brotkrueml\FeedGenerator\Tests\Fixtures\Renderer\Json\FeedWithEmptyItems;
use Brotkrueml\FeedGenerator\Tests\Fixtures\Renderer\Json\FeedWithEmptyTitle;
use Brotkrueml\FeedGenerator\Tests\Fixtures\Renderer\Json\FullFeed;
use Brotkrueml\FeedGenerator\Tests\Fixtures\Renderer\Json\FullItems;
use Brotkrueml\FeedGenerator\Tests\Fixtures\Renderer\Json\ItemWithEmptyId;
use Brotkrueml\FeedGenerator\Tests\Fixtures\Renderer\Json\MinimumFeed;
use PHPUnit\Framework\TestCase;

final class JsonRendererTest extends TestCase
{
    private JsonRenderer $subject;

    protected function setUp(): void
    {
        $extensionRegistryDummy = new class() implements ExtensionRegistryInterface {
            public function getExtensionForContent(FeedFormat $format, ExtensionContentInterface $content): JsonExtensionInterface|XmlExtensionInterface
            {
                throw new \Exception('unused');
            }

            /**
             * @return array{}
             */
            public function getAllExtensions(): iterable
            {
                return [];
            }
        };

        $this->subject = new JsonRenderer($extensionRegistryDummy);
    }

    /**
     * @test
     */
    public function emptyFeedTitleThrowsException(): void
    {
        $this->expectException(MissingRequiredPropertyException::class);
        $this->expectExceptionMessage('Required property "title" is missing.');

        $this->subject->render(new FeedWithEmptyTitle(), 'https://example.org/feed.json');
    }

    /**
     * @test
     */
    public function emptyFeedItemsThrowsException(): void
    {
        $this->expectException(MissingRequiredPropertyException::class);
        $this->expectExceptionMessage('Required property "items" is missing.');

        $this->subject->render(new FeedWithEmptyItems(), 'https://example.org/feed.json');
    }

    /**
     * @test
     */
    public function emptyItemIdThrowsException(): void
    {
        $this->expectException(MissingRequiredPropertyException::class);
        $this->expectExceptionMessage('Required property "item/id" is missing.');

        $this->subject->render(new ItemWithEmptyId(), 'https://example.org/feed.json');
    }

    /**
     * @test
     * @dataProvider providerForFeed
     */
    public function differentScenarios(FeedInterface $feed, string $expectedFile): void
    {
        $actual = $this->subject->render($feed, 'https://example.org/feed.json');

        self::assertJsonStringEqualsJsonFile($expectedFile, $actual);
    }

    public function providerForFeed(): iterable
    {
        yield 'Minimum feed' => [
            'feed' => new MinimumFeed(),
            'expectedFile' => __DIR__ . '/../../Fixtures/Renderer/Json/MinimumFeed.json',
        ];

        yield 'Full feed' => [
            'feed' => new FullFeed(),
            'expectedFile' => __DIR__ . '/../../Fixtures/Renderer/Json/FullFeed.json',
        ];

        yield 'Full items' => [
            'feed' => new FullItems(),
            'expectedFile' => __DIR__ . '/../../Fixtures/Renderer/Json/FullItems.json',
        ];
    }
}
