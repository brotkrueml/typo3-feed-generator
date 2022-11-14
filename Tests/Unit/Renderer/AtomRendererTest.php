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
use Brotkrueml\FeedGenerator\Renderer\AtomRenderer;
use Brotkrueml\FeedGenerator\Renderer\MissingRequiredPropertyException;
use Brotkrueml\FeedGenerator\Tests\Fixtures\Renderer\Atom\FeedWithEmptyDateModified;
use Brotkrueml\FeedGenerator\Tests\Fixtures\Renderer\Atom\FeedWithEmptyId;
use Brotkrueml\FeedGenerator\Tests\Fixtures\Renderer\Atom\FeedWithEmptyTitle;
use Brotkrueml\FeedGenerator\Tests\Fixtures\Renderer\Atom\FullFeed;
use Brotkrueml\FeedGenerator\Tests\Fixtures\Renderer\Atom\FullItems;
use Brotkrueml\FeedGenerator\Tests\Fixtures\Renderer\Atom\ItemWithEmptyDateModified;
use Brotkrueml\FeedGenerator\Tests\Fixtures\Renderer\Atom\ItemWithEmptyId;
use Brotkrueml\FeedGenerator\Tests\Fixtures\Renderer\Atom\ItemWithEmptyTitle;
use Brotkrueml\FeedGenerator\Tests\Fixtures\Renderer\Atom\MinimumCategoryFeed;
use Brotkrueml\FeedGenerator\Tests\Fixtures\Renderer\Atom\MinimumFeed;
use Brotkrueml\FeedGenerator\Tests\Fixtures\Renderer\Atom\MinimumFeedAuthor;
use Brotkrueml\FeedGenerator\Tests\Fixtures\Renderer\Atom\MinimumItems;
use PHPUnit\Framework\TestCase;

final class AtomRendererTest extends TestCase
{
    private AtomRenderer $subject;

    protected function setUp(): void
    {
        $this->subject = new AtomRenderer();
    }

    /**
     * @test
     */
    public function emptyFeedIdThrowsException(): void
    {
        $this->expectException(MissingRequiredPropertyException::class);
        $this->expectExceptionMessage('Required property "id" is missing.');

        $this->subject->render(new FeedWithEmptyId(), 'https://example.org/feed.atom');
    }

    /**
     * @test
     */
    public function emptyFeedTitleThrowsException(): void
    {
        $this->expectException(MissingRequiredPropertyException::class);
        $this->expectExceptionMessage('Required property "title" is missing.');

        $this->subject->render(new FeedWithEmptyTitle(), 'https://example.org/feed.atom');
    }

    /**
     * @test
     */
    public function emptyFeedDateModifiedThrowsException(): void
    {
        $this->expectException(MissingRequiredPropertyException::class);
        $this->expectExceptionMessage('Required property "updated" is missing.');

        $this->subject->render(new FeedWithEmptyDateModified(), 'https://example.org/feed.atom');
    }

    /**
     * @test
     */
    public function emptyItemIdThrowsException(): void
    {
        $this->expectException(MissingRequiredPropertyException::class);
        $this->expectExceptionMessage('Required property "entry/id" is missing.');

        $this->subject->render(new ItemWithEmptyId(), 'https://example.org/feed.atom');
    }

    /**
     * @test
     */
    public function emptyItemTitleThrowsException(): void
    {
        $this->expectException(MissingRequiredPropertyException::class);
        $this->expectExceptionMessage('Required property "entry/title" is missing.');

        $this->subject->render(new ItemWithEmptyTitle(), 'https://example.org/feed.atom');
    }

    /**
     * @test
     */
    public function emptyItemDateModifiedThrowsException(): void
    {
        $this->expectException(MissingRequiredPropertyException::class);
        $this->expectExceptionMessage('Required property "entry/updated" is missing.');

        $this->subject->render(new ItemWithEmptyDateModified(), 'https://example.org/feed.atom');
    }

    /**
     * @test
     * @dataProvider providerForFeed
     */
    public function differentScenarios(FeedInterface $feed, string $expectedFile): void
    {
        $actual = $this->subject->render($feed, 'https://example.org/feed.atom');

        self::assertXmlStringEqualsXmlFile($expectedFile, $actual);
    }

    public function providerForFeed(): iterable
    {
        yield 'Minimum feed' => [
            'feed' => new MinimumFeed(),
            'expectedFile' => __DIR__ . '/../../Fixtures/Renderer/Atom/MinimumFeed.xml',
        ];

        yield 'Feed author with only name' => [
            'feed' => new MinimumFeedAuthor(),
            'expectedFile' => __DIR__ . '/../../Fixtures/Renderer/Atom/MinimumFeedAuthor.xml',
        ];

        yield 'Minimum category feed' => [
            'feed' => new MinimumCategoryFeed(),
            'expectedFile' => __DIR__ . '/../../Fixtures/Renderer/Atom/MinimumCategoryFeed.xml',
        ];

        yield 'Full feed' => [
            'feed' => new FullFeed(),
            'expectedFile' => __DIR__ . '/../../Fixtures/Renderer/Atom/FullFeed.xml',
        ];

        yield 'Minimum items' => [
            'feed' => new MinimumItems(),
            'expectedFile' => __DIR__ . '/../../Fixtures/Renderer/Atom/MinimumItems.xml',
        ];

        yield 'Full items' => [
            'feed' => new FullItems(),
            'expectedFile' => __DIR__ . '/../../Fixtures/Renderer/Atom/FullItems.xml',
        ];
    }
}