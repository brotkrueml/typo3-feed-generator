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
use PHPUnit\Framework\TestCase;

/**
 * @covers \Brotkrueml\FeedGenerator\Renderer\Xml\Node\RssAuthorNode
 */
final class RssAuthorNodeTest extends TestCase
{
    private \DOMDocument $document;
    private RssAuthorNode $subject;

    protected function setUp(): void
    {
        $this->document = new \DOMDocument('1.0', 'utf-8');
        $this->document->formatOutput = true;

        $rootElement = $this->document->appendChild($this->document->createElement('root'));

        $this->subject = new RssAuthorNode($this->document, $rootElement, new XmlNamespaceCollection());
    }

    /**
     * @test
     */
    public function nameIsEmptyThenAnExceptionIsThrown(): void
    {
        self::expectException(MissingRequiredPropertyException::class);
        self::expectExceptionMessageMatches('#author#');

        $this->subject->add(new Author(''));
    }

    /**
     * @test
     * @dataProvider provider
     */
    public function authorNodeIsAddedCorrectly(AuthorInterface $author, string $expected): void
    {
        $this->subject->add($author);

        self::assertXmlStringEqualsXmlString($expected, $this->document->saveXML());
    }

    public function provider(): iterable
    {
        yield 'Only name is given' => [
            'author' => new Author('John Doe'),
            'expected' => <<<XML
<?xml version="1.0" encoding="utf-8"?>
<root>
<dc:creator>John Doe</dc:creator>
</root>
XML,
        ];

        yield 'Name and email are given' => [
            'author' => new Author('John Doe', 'john.doe@example.org'),
            'expected' => <<<XML
<?xml version="1.0" encoding="utf-8"?>
<root>
<dc:creator>John Doe</dc:creator>
</root>
XML,
        ];
    }
}
