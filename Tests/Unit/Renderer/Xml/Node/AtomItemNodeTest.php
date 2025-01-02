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
use Brotkrueml\FeedGenerator\Entity\Item;
use Brotkrueml\FeedGenerator\Renderer\MissingRequiredPropertyException;
use Brotkrueml\FeedGenerator\Renderer\Xml\Node\AtomItemNode;
use Brotkrueml\FeedGenerator\Renderer\Xml\XmlExtensionProcessor;
use Brotkrueml\FeedGenerator\ValueObject\Author;
use Brotkrueml\FeedGenerator\ValueObject\Category;
use Brotkrueml\FeedGenerator\ValueObject\Text;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(AtomItemNode::class)]
final class AtomItemNodeTest extends TestCase
{
    private \DOMDocument $document;
    private AtomItemNode $subject;

    protected function setUp(): void
    {
        $this->document = new \DOMDocument('1.0', 'utf-8');
        $this->document->formatOutput = true;

        $rootElement = $this->document->appendChild($this->document->createElement('root'));

        $extensionProcessorDummy = self::createStub(XmlExtensionProcessor::class);

        $this->subject = new AtomItemNode($this->document, $rootElement, $extensionProcessorDummy, new XmlNamespaceCollection());
    }

    #[Test]
    public function idIsEmptyThenAnExceptionIsThrown(): void
    {
        $this->expectException(MissingRequiredPropertyException::class);
        $this->expectExceptionMessageMatches('#entry/id#');

        $item = (new Item())
            ->setId('')
            ->setTitle('some title')
            ->setDateModified(new \DateTimeImmutable());

        $this->subject->add($item);
    }

    #[Test]
    public function titleIsEmptyThenAnExceptionIsThrown(): void
    {
        $this->expectException(MissingRequiredPropertyException::class);
        $this->expectExceptionMessageMatches('#entry/title#');

        $item = (new Item())
            ->setId('some-id')
            ->setTitle('')
            ->setDateModified(new \DateTimeImmutable());

        $this->subject->add($item);
    }

    #[Test]
    public function dateModifiedIsEmptyThenAnExceptionIsThrown(): void
    {
        $this->expectException(MissingRequiredPropertyException::class);
        $this->expectExceptionMessageMatches('#entry/updated#');

        $item = (new Item())
            ->setId('some-id')
            ->setTitle('some title')
            ->setDateModified(null);

        $this->subject->add($item);
    }

    #[Test]
    public function linkAndContentAreEmptyThenAnExceptionIsThrown(): void
    {
        $this->expectException(MissingRequiredPropertyException::class);
        $this->expectExceptionMessageMatches('#entry/link.+entry/content#');

        $item = (new Item())
            ->setId('some-id')
            ->setTitle('some title')
            ->setDateModified(new \DateTimeImmutable());

        $this->subject->add($item);
    }

    #[Test]
    public function minimumItemWithLinkGiven(): void
    {
        $item = (new Item())
            ->setId('some-id')
            ->setTitle('some title')
            ->setDateModified(new \DateTimeImmutable('2022-11-23 12:13:14'))
            ->setLink('https://example.org/some-link');

        $this->subject->add($item);

        $expected = <<<XML
<?xml version="1.0" encoding="utf-8"?>
<root>
  <entry>
    <id>some-id</id>
    <title>some title</title>
    <updated>2022-11-23T12:13:14+00:00</updated>
    <link href="https://example.org/some-link" rel="alternate" type="text/html"/>
  </entry>
</root>
XML;

        self::assertXmlStringEqualsXmlString($expected, $this->document->saveXML());
    }

    #[Test]
    public function minimumItemWithContentGiven(): void
    {
        $item = (new Item())
            ->setId('some-id')
            ->setTitle('some title')
            ->setDateModified(new \DateTimeImmutable('2022-11-23 12:13:14'))
            ->setContent('<p>some content</p>');

        $this->subject->add($item);

        $expected = <<<XML
<?xml version="1.0" encoding="utf-8"?>
<root>
  <entry>
    <id>some-id</id>
    <title>some title</title>
    <updated>2022-11-23T12:13:14+00:00</updated>
    <content>&lt;p&gt;some content&lt;/p&gt;</content>
  </entry>
</root>
XML;

        self::assertXmlStringEqualsXmlString($expected, $this->document->saveXML());
    }

    #[Test]
    public function oneAuthorGiven(): void
    {
        $item = (new Item())
            ->setId('some-id')
            ->setTitle('some title')
            ->setDateModified(new \DateTimeImmutable('2022-11-23 12:13:14'))
            ->setLink('https://example.org/some-link')
            ->addAuthors(new Author('John Doe'));

        $this->subject->add($item);

        $expected = <<<XML
<?xml version="1.0" encoding="utf-8"?>
<root>
  <entry>
    <id>some-id</id>
    <title>some title</title>
    <updated>2022-11-23T12:13:14+00:00</updated>
    <link href="https://example.org/some-link" rel="alternate" type="text/html"/>
    <author>
      <name>John Doe</name>
    </author>
  </entry>
</root>
XML;

        self::assertXmlStringEqualsXmlString($expected, $this->document->saveXML());
    }

    #[Test]
    public function twoAuthorsGiven(): void
    {
        $item = (new Item())
            ->setId('some-id')
            ->setTitle('some title')
            ->setDateModified(new \DateTimeImmutable('2022-11-23 12:13:14'))
            ->setLink('https://example.org/some-link')
            ->addAuthors(new Author('John Doe'), new Author('Jan Novak'));

        $this->subject->add($item);

        $expected = <<<XML
<?xml version="1.0" encoding="utf-8"?>
<root>
  <entry>
    <id>some-id</id>
    <title>some title</title>
    <updated>2022-11-23T12:13:14+00:00</updated>
    <link href="https://example.org/some-link" rel="alternate" type="text/html"/>
    <author>
      <name>John Doe</name>
    </author>
    <author>
      <name>Jan Novak</name>
    </author>
  </entry>
</root>
XML;

        self::assertXmlStringEqualsXmlString($expected, $this->document->saveXML());
    }

    #[Test]
    public function descriptionAsStringGiven(): void
    {
        $item = (new Item())
            ->setId('some-id')
            ->setTitle('some title')
            ->setDateModified(new \DateTimeImmutable('2022-11-23 12:13:14'))
            ->setLink('https://example.org/some-link')
            ->setDescription('some description');

        $this->subject->add($item);

        $expected = <<<XML
<?xml version="1.0" encoding="utf-8"?>
<root>
  <entry>
    <id>some-id</id>
    <title>some title</title>
    <updated>2022-11-23T12:13:14+00:00</updated>
    <link href="https://example.org/some-link" rel="alternate" type="text/html"/>
    <summary>some description</summary>
  </entry>
</root>
XML;

        self::assertXmlStringEqualsXmlString($expected, $this->document->saveXML());
    }

    #[Test]
    public function descriptionAsTextObjectGiven(): void
    {
        $item = (new Item())
            ->setId('some-id')
            ->setTitle('some title')
            ->setDateModified(new \DateTimeImmutable('2022-11-23 12:13:14'))
            ->setLink('https://example.org/some-link')
            ->setDescription(new Text('some description'));

        $this->subject->add($item);

        $expected = <<<XML
<?xml version="1.0" encoding="utf-8"?>
<root>
  <entry>
    <id>some-id</id>
    <title>some title</title>
    <updated>2022-11-23T12:13:14+00:00</updated>
    <link href="https://example.org/some-link" rel="alternate" type="text/html"/>
    <summary type="text">some description</summary>
  </entry>
</root>
XML;

        self::assertXmlStringEqualsXmlString($expected, $this->document->saveXML());
    }

    #[Test]
    public function contentIsNotEmpty(): void
    {
        $item = (new Item())
            ->setId('some-id')
            ->setTitle('some title')
            ->setDateModified(new \DateTimeImmutable('2022-11-23 12:13:14'))
            ->setLink('https://example.org/some-link')
            ->setContent('some content');

        $this->subject->add($item);

        $expected = <<<XML
<?xml version="1.0" encoding="utf-8"?>
<root>
  <entry>
    <id>some-id</id>
    <title>some title</title>
    <updated>2022-11-23T12:13:14+00:00</updated>
    <link href="https://example.org/some-link" rel="alternate" type="text/html"/>
    <content>some content</content>
  </entry>
</root>
XML;

        self::assertXmlStringEqualsXmlString($expected, $this->document->saveXML());
    }

    #[Test]
    public function oneCategoryGiven(): void
    {
        $item = (new Item())
            ->setId('some-id')
            ->setTitle('some title')
            ->setDateModified(new \DateTimeImmutable('2022-11-23 12:13:14'))
            ->setLink('https://example.org/some-link')
            ->addCategories(new Category('some category'));

        $this->subject->add($item);

        $expected = <<<XML
<?xml version="1.0" encoding="utf-8"?>
<root>
  <entry>
    <id>some-id</id>
    <title>some title</title>
    <updated>2022-11-23T12:13:14+00:00</updated>
    <link href="https://example.org/some-link" rel="alternate" type="text/html"/>
    <category term="some category"/>
  </entry>
</root>
XML;

        self::assertXmlStringEqualsXmlString($expected, $this->document->saveXML());
    }

    #[Test]
    public function twoCategoriesGiven(): void
    {
        $item = (new Item())
            ->setId('some-id')
            ->setTitle('some title')
            ->setDateModified(new \DateTimeImmutable('2022-11-23 12:13:14'))
            ->setLink('https://example.org/some-link')
            ->addCategories(new Category('some category'), new Category('another category'));

        $this->subject->add($item);

        $expected = <<<XML
<?xml version="1.0" encoding="utf-8"?>
<root>
  <entry>
    <id>some-id</id>
    <title>some title</title>
    <updated>2022-11-23T12:13:14+00:00</updated>
    <link href="https://example.org/some-link" rel="alternate" type="text/html"/>
    <category term="some category"/>
    <category term="another category"/>
  </entry>
</root>
XML;

        self::assertXmlStringEqualsXmlString($expected, $this->document->saveXML());
    }

    #[Test]
    public function datePublishedIsNotEmpty(): void
    {
        $item = (new Item())
            ->setId('some-id')
            ->setTitle('some title')
            ->setDateModified(new \DateTimeImmutable('2022-11-23 12:13:14'))
            ->setLink('https://example.org/some-link')
            ->setDatePublished(new \DateTimeImmutable('2022-11-11 11:11:11'));

        $this->subject->add($item);

        $expected = <<<XML
<?xml version="1.0" encoding="utf-8"?>
<root>
  <entry>
    <id>some-id</id>
    <title>some title</title>
    <updated>2022-11-23T12:13:14+00:00</updated>
    <link href="https://example.org/some-link" rel="alternate" type="text/html"/>
    <published>2022-11-11T11:11:11+00:00</published>
  </entry>
</root>
XML;

        self::assertXmlStringEqualsXmlString($expected, $this->document->saveXML());
    }
}
