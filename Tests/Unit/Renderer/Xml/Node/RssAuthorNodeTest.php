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
use Brotkrueml\FeedGenerator\Contract\AuthorInterface;
use Brotkrueml\FeedGenerator\Renderer\MissingRequiredPropertyException;
use Brotkrueml\FeedGenerator\Renderer\Xml\Node\RssAuthorNode;
use Brotkrueml\FeedGenerator\ValueObject\Author;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(RssAuthorNode::class)]
final class RssAuthorNodeTest extends TestCase
{
    private \DOMDocument $document;
    private RssAuthorNode $subject;

    protected function setUp(): void
    {
        $this->document = new \DOMDocument('1.0', 'utf-8');
        $this->document->formatOutput = true;

        $rootElement = $this->document->appendChild($this->document->createElement('root'));
        $rootElement->setAttribute('xmlns:dc', 'http://purl.org/dc/elements/1.1/');

        $this->subject = new RssAuthorNode($this->document, $rootElement, new XmlNamespaceCollection());
    }

    #[Test]
    public function nameIsEmptyThenAnExceptionIsThrown(): void
    {
        self::expectException(MissingRequiredPropertyException::class);
        self::expectExceptionMessageMatches('#author#');

        $this->subject->add(new Author(''));
    }

    #[Test]
    #[DataProvider('provider')]
    public function authorNodeIsAddedCorrectly(AuthorInterface $author, string $expected): void
    {
        $this->subject->add($author);

        self::assertXmlStringEqualsXmlString($expected, $this->document->saveXML());
    }

    public static function provider(): iterable
    {
        yield 'Only name is given' => [
            'author' => new Author('John Doe'),
            'expected' => <<<XML
<?xml version="1.0" encoding="utf-8"?>
<root xmlns:dc="http://purl.org/dc/elements/1.1/">
<dc:creator>John Doe</dc:creator>
</root>
XML,
        ];

        yield 'Name and email are given' => [
            'author' => new Author('John Doe', 'john.doe@example.org'),
            'expected' => <<<XML
<?xml version="1.0" encoding="utf-8"?>
<root xmlns:dc="http://purl.org/dc/elements/1.1/">
<dc:creator>John Doe</dc:creator>
</root>
XML,
        ];
    }
}
