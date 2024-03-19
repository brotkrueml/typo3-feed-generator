<?php

declare(strict_types=1);

/*
 * This file is part of the "feed_generator" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\FeedGenerator\Tests\Unit\Renderer\Xml\Node;

use Brotkrueml\FeedGenerator\Renderer\Xml\Node\TextNode;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class TextNodeTest extends TestCase
{
    private \DOMDocument $document;
    private TextNode $subject;

    protected function setUp(): void
    {
        $this->document = new \DOMDocument('1.0', 'utf-8');
        $this->document->formatOutput = true;

        $rootElement = $this->document->appendChild($this->document->createElement('root'));

        $this->subject = new TextNode($this->document, $rootElement);
    }

    #[Test]
    public function valueIsEmptyThenNoNodeIsAppended(): void
    {
        $this->subject->add('foo', '');

        $expected = <<<XML
<?xml version="1.0" encoding="utf-8"?>
<root/>
XML;

        self::assertXmlStringEqualsXmlString($expected, $this->document->saveXML());
    }

    #[Test]
    public function valueIsNotEmptyThenNodeIsAppended(): void
    {
        $this->subject->add('foo', 'bar');
        $expected = <<<XML
<?xml version="1.0" encoding="utf-8"?>
<root>
  <foo>bar</foo>
</root>
XML;

        self::assertXmlStringEqualsXmlString($expected, $this->document->saveXML());
    }

    #[Test]
    public function multipleCallsAddMultipleNodes(): void
    {
        $this->subject->add('foo', 'bar');
        $this->subject->add('qux', 'quu');
        $this->subject->add('qux', 'baz');
        $expected = <<<XML
<?xml version="1.0" encoding="utf-8"?>
<root>
  <foo>bar</foo>
  <qux>quu</qux>
  <qux>baz</qux>
</root>
XML;

        self::assertXmlStringEqualsXmlString($expected, $this->document->saveXML());
    }
}
