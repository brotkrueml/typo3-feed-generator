<?php

declare(strict_types=1);

/*
 * This file is part of the "feed_generator" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\FeedGenerator\Tests\Unit\Renderer\Xml\Node;

use Brotkrueml\FeedGenerator\Renderer\MissingRequiredPropertyException;
use Brotkrueml\FeedGenerator\Renderer\Xml\Node\AtomLinkNode;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class AtomLinkNodeTest extends TestCase
{
    private \DOMDocument $document;
    private \DOMElement $rootElement;
    private AtomLinkNode $subject;

    protected function setUp(): void
    {
        $this->document = new \DOMDocument('1.0', 'utf-8');
        $this->document->formatOutput = true;

        $this->rootElement = $this->document->appendChild($this->document->createElement('root'));

        $this->subject = new AtomLinkNode($this->document, $this->rootElement);
    }

    #[Test]
    public function hrefIsEmptyThenExceptionIsThrown(): void
    {
        $this->expectException(MissingRequiredPropertyException::class);
        $this->expectExceptionMessageMatches('#link/href#');

        $this->subject->add('', 'alternative', 'text/html');
    }

    #[Test]
    public function relIsEmptyThenExceptionIsThrown(): void
    {
        $this->expectException(MissingRequiredPropertyException::class);
        $this->expectExceptionMessageMatches('#link/rel#');

        $this->subject->add('https://example.org/', '', 'text/html');
    }

    #[Test]
    public function typeIsEmptyThenExceptionIsThrown(): void
    {
        $this->expectException(MissingRequiredPropertyException::class);
        $this->expectExceptionMessageMatches('#link/type#');

        $this->subject->add('https://example.org/', 'alternative', '');
    }

    #[Test]
    public function allRequiredValuesAreGivenThenNodeIsAdded(): void
    {
        $this->subject->add('https://example.org/', 'alternative', 'text/html');

        $expected = <<<XML
<?xml version="1.0" encoding="utf-8"?>
<root>
  <link href="https://example.org/" rel="alternative" type="text/html"/>
</root>
XML;

        self::assertXmlStringEqualsXmlString($expected, $this->document->saveXML());
    }

    #[Test]
    public function namespaceIsGiven(): void
    {
        $this->rootElement->setAttribute('xmlns:atom', 'http://www.w3.org/2005/Atom');

        $this->subject->add('https://example.org/some-feed.rss', 'self', 'application/rss+xml', 'atom');

        $expected = <<<XML
<?xml version="1.0" encoding="utf-8"?>
<root xmlns:atom="http://www.w3.org/2005/Atom">
  <atom:link href="https://example.org/some-feed.rss" rel="self" type="application/rss+xml"/>
</root>
XML;

        self::assertXmlStringEqualsXmlString($expected, $this->document->saveXML());
    }
}
