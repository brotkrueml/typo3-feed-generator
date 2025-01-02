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
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(RssItemNode::class)]
final class RssItemNodeTest extends TestCase
{
    private \DOMDocument $document;
    private \DOMElement $rootElement;
    private RssItemNode $subject;

    protected function setUp(): void
    {
        $this->document = new \DOMDocument('1.0', 'utf-8');
        $this->document->formatOutput = true;

        $this->rootElement = $this->document->appendChild($this->document->createElement('root'));

        $extensionProcessorDummy = self::createStub(XmlExtensionProcessor::class);

        $this->subject = new RssItemNode($this->document, $this->rootElement, $extensionProcessorDummy, new XmlNamespaceCollection());
    }

    #[Test]
    public function titleAndDescriptionAsStringAreEmptyThenExceptionIsThrown(): void
    {
        $this->expectException(MissingRequiredPropertyException::class);
        $this->expectExceptionMessageMatches('#item/title.+item/description#');

        $this->subject->add(new Item());
    }

    #[Test]
    public function titleAndDescriptionAsTextObjectAreEmptyThenExceptionIsThrown(): void
    {
        $this->expectException(MissingRequiredPropertyException::class);
        $this->expectExceptionMessageMatches('#item/title.+item/description#');

        $this->subject->add((new Item())->setDescription(new Text('')));
    }

    #[Test]
    #[DataProvider('provider')]
    public function someScenarios(ItemInterface $item, array $namespaces, string $expected): void
    {
        foreach ($namespaces as $prefix => $uri) {
            $this->rootElement->setAttribute('xmlns:' . $prefix, $uri);
        }

        $this->subject->add($item);

        self::assertXmlStringEqualsXmlString($expected, $this->document->saveXML());
    }

    public static function provider(): iterable
    {
        yield 'Only title is given' => [
            'item' => (new Item())
                ->setTitle('Some title'),
            'namespaces' => [],
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
            'namespaces' => [],
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
            'namespaces' => [],
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
            'namespaces' => [],
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
            'namespaces' => [],
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
            'namespaces' => [
                'dc' => 'http://purl.org/dc/elements/1.1/',
            ],
            'expected' => <<<XML
<root xmlns:dc="http://purl.org/dc/elements/1.1/">
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
            'namespaces' => [
                'dc' => 'http://purl.org/dc/elements/1.1/',
            ],
            'expected' => <<<XML
<root xmlns:dc="http://purl.org/dc/elements/1.1/">
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
            'namespaces' => [],
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
                    new Attachment('https://example.come/', 'another/type', 234),
                ),
            'namespaces' => [],
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
            'namespaces' => [],
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
            'namespaces' => [],
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
            'namespaces' => [],
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
