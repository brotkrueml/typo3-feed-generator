<?php

declare(strict_types=1);

/*
 * This file is part of the "feed_generator" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\FeedGenerator\Tests\Unit\Renderer\Xml\Node;

use Brotkrueml\FeedGenerator\Collection\XmlNamespaceCollection;
use Brotkrueml\FeedGenerator\Contract\ItemInterface;
use Brotkrueml\FeedGenerator\Entity\Item;
use Brotkrueml\FeedGenerator\Format\TextFormat;
use Brotkrueml\FeedGenerator\Renderer\MissingRequiredPropertyException;
use Brotkrueml\FeedGenerator\Renderer\Xml\Node\RssItemNode;
use Brotkrueml\FeedGenerator\Renderer\Xml\XmlExtensionProcessor;
use Brotkrueml\FeedGenerator\ValueObject\Attachment;
use Brotkrueml\FeedGenerator\ValueObject\Author;
use Brotkrueml\FeedGenerator\ValueObject\Category;
use Brotkrueml\FeedGenerator\ValueObject\Text;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Brotkrueml\FeedGenerator\Renderer\Xml\Node\RssItemNode
 */
final class RssItemNodeTest extends TestCase
{
    private \DOMDocument $document;
    private RssItemNode $subject;

    protected function setUp(): void
    {
        $this->document = new \DOMDocument('1.0', 'utf-8');
        $this->document->formatOutput = true;

        $rootElement = $this->document->appendChild($this->document->createElement('root'));

        $extensionProcessorDummy = $this->createStub(XmlExtensionProcessor::class);

        $this->subject = new RssItemNode($this->document, $rootElement, $extensionProcessorDummy, new XmlNamespaceCollection());
    }

    /**
     * @test
     */
    public function titleAndDescriptionAsStringAreEmptyThenExceptionIsThrown(): void
    {
        $this->expectException(MissingRequiredPropertyException::class);
        $this->expectErrorMessageMatches('#item/title.+item/description#');

        $this->subject->add(new Item());
    }

    /**
     * @test
     */
    public function titleAndDescriptionAsTextObjectAreEmptyThenExceptionIsThrown(): void
    {
        $this->expectException(MissingRequiredPropertyException::class);
        $this->expectErrorMessageMatches('#item/title.+item/description#');

        $this->subject->add((new Item())->setDescription(new Text('')));
    }

    /**
     * @test
     * @dataProvider provider
     */
    public function someScenarios(ItemInterface $item, string $expected): void
    {
        $this->subject->add($item);

        self::assertXmlStringEqualsXmlString($expected, $this->document->saveXML());
    }

    public function provider(): iterable
    {
        yield 'Only title is given' => [
            'item' => (new Item())
                ->setTitle('Some title'),
            'expected' => <<<XML
<root>
  <item>
    <title>Some title</title>
  </item>
</root>
XML,
        ];

        yield 'Only description as string is given' => [
            'item' => (new Item())
                ->setDescription('Some description'),
            'expected' => <<<XML
<root>
  <item>
    <description>Some description</description>
  </item>
</root>
XML,
        ];

        yield 'Only description as Text object is given' => [
            'item' => (new Item())
                ->setDescription(new Text('Some description', TextFormat::HTML)),
            'expected' => <<<XML
<root>
  <item>
    <description>Some description</description>
  </item>
</root>
XML,
        ];

        yield 'Link is given' => [
            'item' => (new Item())
                ->setTitle('Some title')
                ->setLink('https://example.org/'),
            'expected' => <<<XML
<root>
  <item>
    <title>Some title</title>
    <link>https://example.org/</link>
    <guid isPermaLink="true">https://example.org/</guid>
  </item>
</root>
XML,
        ];

        yield 'Link and id are given' => [
            'item' => (new Item())
                ->setTitle('Some title')
                ->setLink('https://example.org/')
                ->setId('some-id'),
            'expected' => <<<XML
<root>
  <item>
    <title>Some title</title>
    <link>https://example.org/</link>
    <guid isPermaLink="false">some-id</guid>
  </item>
</root>
XML,
        ];

        yield 'One author is given' => [
            'item' => (new Item())
                ->setTitle('Some title')
                ->addAuthors(new Author('John Doe')),
            'expected' => <<<XML
<root>
  <item>
    <title>Some title</title>
    <dc:creator>John Doe</dc:creator>
  </item>
</root>
XML,
        ];

        yield 'Two authors are given' => [
            'item' => (new Item())
                ->setTitle('Some title')
                ->addAuthors(new Author('John Doe'), new Author('Jan Novak')),
            'expected' => <<<XML
<root>
  <item>
    <title>Some title</title>
    <dc:creator>John Doe</dc:creator>
    <dc:creator>Jan Novak</dc:creator>
  </item>
</root>
XML,
        ];

        yield 'One attachment is given' => [
            'item' => (new Item())
                ->setTitle('Some title')
                ->addAttachments(new Attachment('https://example.org/', 'some/type', 123)),
            'expected' => <<<XML
<root>
  <item>
    <title>Some title</title>
    <enclosure length="123" type="some/type" url="https://example.org/"/>
  </item>
</root>
XML,
        ];

        yield 'Two attachments are given, only one is taken' => [
            'item' => (new Item())
                ->setTitle('Some title')
                ->addAttachments(
                    new Attachment('https://example.org/', 'some/type', 123),
                    new Attachment('https://example.come/', 'another/type', 234)
                ),
            'expected' => <<<XML
<root>
  <item>
    <title>Some title</title>
    <enclosure length="123" type="some/type" url="https://example.org/"/>
  </item>
</root>
XML,
        ];

        yield 'One category is given' => [
            'item' => (new Item())
                ->setTitle('Some title')
                ->addCategories(new Category('some category')),
            'expected' => <<<XML
<root>
  <item>
    <title>Some title</title>
    <category>some category</category>
  </item>
</root>
XML,
        ];

        yield 'Two categories are given' => [
            'item' => (new Item())
                ->setTitle('Some title')
                ->addCategories(new Category('some category'), new Category('another category')),
            'expected' => <<<XML
<root>
  <item>
    <title>Some title</title>
    <category>some category</category>
    <category>another category</category>
  </item>
</root>
XML,
        ];

        yield 'Date published is given' => [
            'item' => (new Item())
                ->setTitle('Some title')
                ->setDatePublished(new \DateTimeImmutable('2022-11-24 20:20:20')),
            'expected' => <<<XML
<root>
  <item>
    <title>Some title</title>
    <pubDate>Thu, 24 Nov 2022 20:20:20 +0000</pubDate>
  </item>
</root>
XML,
        ];
    }
}
