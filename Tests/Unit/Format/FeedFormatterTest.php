<?php

declare(strict_types=1);

/*
 * This file is part of the "feed_generator" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\FeedGenerator\Tests\Unit\Format;

use Brotkrueml\FeedGenerator\Format\FeedFormat;
use Brotkrueml\FeedGenerator\Format\FeedFormatter;
use Brotkrueml\FeedGenerator\Mapper\JsonFeed\AuthorMapper as JsonAuthorMapper;
use Brotkrueml\FeedGenerator\Mapper\JsonFeed\FeedMapper as JsonFeedMapper;
use Brotkrueml\FeedGenerator\Mapper\JsonFeed\ItemMapper as JsonItemMapper;
use Brotkrueml\FeedGenerator\Mapper\LaminasFeed\AuthorMapper as LaminasAuthorMapper;
use Brotkrueml\FeedGenerator\Mapper\LaminasFeed\CategoryMapper as LaminasCategoryMapper;
use Brotkrueml\FeedGenerator\Mapper\LaminasFeed\EnclosureMapper as LaminasEnclosureMapper;
use Brotkrueml\FeedGenerator\Mapper\LaminasFeed\FeedMapper as LaminasFeedMapper;
use Brotkrueml\FeedGenerator\Mapper\LaminasFeed\ImageMapper as LaminasImageMapper;
use Brotkrueml\FeedGenerator\Mapper\LaminasFeed\ItemMapper as LaminasItemMapper;
use Brotkrueml\FeedGenerator\Package\PackageCheckerInterface;
use Brotkrueml\FeedGenerator\Package\PackageNotInstalledException;
use Brotkrueml\FeedGenerator\Tests\Fixtures\FeedConfiguration\FullFeed;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Brotkrueml\FeedGenerator\Format\FeedFormatter
 */
final class FeedFormatterTest extends TestCase
{
    private JsonFeedMapper $jsonFeedMapper;
    private LaminasFeedMapper $laminasFeedMapper;
    private FeedFormatter $subject;

    protected function setUp(): void
    {
        $packageCheckerStub = new class() implements PackageCheckerInterface {
            public function isPackageInstalledForFormat(FeedFormat $format): bool
            {
                return true;
            }
        };

        $jsonAuthorMapper = new JsonAuthorMapper();
        $this->jsonFeedMapper = new JsonFeedMapper($jsonAuthorMapper, new JsonItemMapper($jsonAuthorMapper));

        $laminasAuthorMapper = new LaminasAuthorMapper();
        $this->laminasFeedMapper = new LaminasFeedMapper(
            $laminasAuthorMapper,
            new LaminasCategoryMapper(),
            new LaminasImageMapper(),
            new LaminasItemMapper($laminasAuthorMapper, new LaminasEnclosureMapper())
        );

        $this->subject = new FeedFormatter(
            $packageCheckerStub,
            $this->jsonFeedMapper,
            $this->laminasFeedMapper,
        );
    }

    /**
     * @test
     */
    public function formatWithFormatJsonReturnsCorrectContent(): void
    {
        $expected = <<< JSON
{
    "version": "https://jsonfeed.org/version/1",
    "title": "some title",
    "home_page_url": "https://example.org/home",
    "feed_url": "https://example.org/some/feed.json",
    "description": "some description",
    "author": {
        "name": "some author",
        "url": "https://example.org/some-author"
    },
    "items": [
        {
            "id": "some item id",
            "url": "https://example.org/some-item",
            "title": "some title",
            "content_html": "some content",
            "summary": "some description",
            "date_published": "2022-11-01T12:34:56+00:00",
            "date_modified": "2022-11-02T01:02:03+00:00",
            "author": {
                "name": "some author 1",
                "url": "https://example.org/some-author-1"
            }
        },
        {
            "id": "another item id",
            "url": "https://example.org/another-item",
            "title": "another title",
            "content_html": "another content",
            "summary": "another description",
            "date_published": "2022-11-03T12:00:00+00:00",
            "date_modified": "2022-11-04T09:08:07+00:00",
            "author": {
                "name": "another author",
                "url": "https://example.org/another-author"
            }
        }
    ]
}
JSON;

        $actual = $this->subject->format(
            'https://example.org/some/feed.json',
            new FullFeed(),
            FeedFormat::JSON
        );

        self::assertJsonStringEqualsJsonString($expected, $actual);
    }

    /**
     * @test
     */
    public function formatWithFormatAtomReturnsCorrectContent(): void
    {
        $expected = <<< ATOM
<?xml version="1.0" encoding="UTF-8"?>
<feed xmlns="http://www.w3.org/2005/Atom" xml:lang="some language">
  <title type="text">some title</title>
  <subtitle type="text">some description</subtitle>
  <logo>https://example.org/some-image</logo>
  <updated>2022-08-01T12:12:12+00:00</updated>
  <generator uri="https://extensions.typo3.org/extension/feed_generator">TYPO3 Feed Generator</generator>
  <link href="https://example.org/home" rel="alternate" type="text/html"/>
  <link href="https://example.org/some/feed.atom" rel="self" type="application/atom+xml"/>
  <id>some id</id>
  <author>
    <name>some author</name>
    <email>some-author@example.org</email>
    <uri>https://example.org/some-author</uri>
  </author>
  <author>
    <name>another author</name>
    <email>another-author@example.org</email>
    <uri>https://example.org/another-author</uri>
  </author>
  <rights>some copyright</rights>
  <entry xmlns:xhtml="http://www.w3.org/1999/xhtml">
    <title type="html">some title</title>
    <summary type="html">some description</summary>
    <published>2022-11-01T12:34:56+00:00</published>
    <updated>2022-11-02T01:02:03+00:00</updated>
    <link href="https://example.org/some-item" rel="alternate" type="text/html"/>
    <id>some item id</id>
    <author>
      <name>some author 1</name>
      <email>some-author-1@example.org</email>
      <uri>https://example.org/some-author-1</uri>
    </author>
    <author>
      <name>some author 2</name>
      <email>some-author-2@example.org</email>
      <uri>https://example.org/some-author-2</uri>
    </author>
    <link href="https://example.org/some-enclosure" length="123456" rel="enclosure" type="some/enclosure"/>
    <content type="xhtml">
      <xhtml:div>some content</xhtml:div>
    </content>
  </entry>
  <entry xmlns:xhtml="http://www.w3.org/1999/xhtml">
    <title type="html">another title</title>
    <summary type="html">another description</summary>
    <published>2022-11-03T12:00:00+00:00</published>
    <updated>2022-11-04T09:08:07+00:00</updated>
    <link href="https://example.org/another-item" rel="alternate" type="text/html"/>
    <id>another item id</id>
    <author>
      <name>another author</name>
      <email>another-author@example.org</email>
      <uri>https://example.org/another-author</uri>
    </author>
    <link href="https://example.org/another-enclosure" length="987654" rel="enclosure" type="another/enclosure"/>
    <content type="xhtml">
      <xhtml:div>another content</xhtml:div>
    </content>
  </entry>
</feed>
ATOM;

        $actual = $this->subject->format('https://example.org/some/feed.atom', new FullFeed(), FeedFormat::ATOM);

        self::assertXmlStringEqualsXmlString($expected, $actual);
    }

    /**
     * @test
     */
    public function formatWithFormatRssReturnsCorrectContent(): void
    {
        $expected = <<< RSS
<?xml version="1.0" encoding="UTF-8"?>
<rss xmlns:atom="http://www.w3.org/2005/Atom" xmlns:content="http://purl.org/rss/1.0/modules/content/" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:slash="http://purl.org/rss/1.0/modules/slash/" version="2.0">
  <channel>
    <language>some language</language>
    <title>some title</title>
    <description>some description</description>
    <image>
      <url>https://example.org/some-image</url>
      <title>some image title</title>
      <link>https://example.org/some-image-link</link>
      <height>234</height>
      <width>123</width>
      <description>some image description</description>
    </image>
    <pubDate>Mon, 01 Aug 2022 12:12:12 +0000</pubDate>
    <lastBuildDate>Mon, 01 Aug 2022 13:13:13 +0000</lastBuildDate>
    <generator>TYPO3 Feed Generator (https://extensions.typo3.org/extension/feed_generator)</generator>
    <link>https://example.org/home</link>
    <author>some-author@example.org (some author)</author>
    <author>another-author@example.org (another author)</author>
    <copyright>some copyright</copyright>
    <dc:creator>some author</dc:creator>
    <dc:creator>another author</dc:creator>
    <atom:link href="https://example.org/some/feed.rss" rel="self" type="application/rss+xml"/>
    <item>
      <title>some title</title>
      <description>some description</description>
      <pubDate>Wed, 02 Nov 2022 01:02:03 +0000</pubDate>
      <link>https://example.org/some-item</link>
      <guid isPermaLink="false">some item id</guid>
      <author>some-author-1@example.org (some author 1)</author>
      <author>some-author-2@example.org (some author 2)</author>
      <enclosure length="123456" type="some/enclosure" url="https://example.org/some-enclosure"/>
      <dc:creator>some author 1</dc:creator>
      <dc:creator>some author 2</dc:creator>
      <content:encoded>some content</content:encoded>
      <slash:comments>0</slash:comments>
    </item>
    <item>
      <title>another title</title>
      <description>another description</description>
      <pubDate>Fri, 04 Nov 2022 09:08:07 +0000</pubDate>
      <link>https://example.org/another-item</link>
      <guid isPermaLink="false">another item id</guid>
      <author>another-author@example.org (another author)</author>
      <enclosure length="987654" type="another/enclosure" url="https://example.org/another-enclosure"/>
      <dc:creator>another author</dc:creator>
      <content:encoded>another content</content:encoded>
      <slash:comments>0</slash:comments>
    </item>
  </channel>
</rss>
RSS;

        $actual = $this->subject->format('https://example.org/some/feed.rss', new FullFeed(), FeedFormat::RSS);

        self::assertXmlStringEqualsXmlString($expected, $actual);
    }

    /**
     * @test
     * @dataProvider providerForPackageNotInstalledThrowsException
     */
    public function packageNotInstalledThrowsException(FeedFormat $format): void
    {
        $packageCheckerStub = new class() implements PackageCheckerInterface {
            public function isPackageInstalledForFormat(FeedFormat $format): bool
            {
                return false;
            }
        };

        $subject = new FeedFormatter(
            $packageCheckerStub,
            $this->jsonFeedMapper,
            $this->laminasFeedMapper,
        );

        $this->expectException(PackageNotInstalledException::class);

        $subject->format(
            'https://example.org/some-feed',
            new FullFeed(),
            $format
        );
    }

    public function providerForPackageNotInstalledThrowsException(): iterable
    {
        yield 'Library for rendering ATOM feed not installed' => [
            'format' => FeedFormat::ATOM,
        ];

        yield 'Library for rendering JSON feed not installed' => [
            'format' => FeedFormat::JSON,
        ];

        yield 'Library for rendering RSS feed not installed' => [
            'format' => FeedFormat::RSS,
        ];
    }
}
