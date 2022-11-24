<?php

declare(strict_types=1);

/*
 * This file is part of the "feed_generator" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\FeedGenerator\Tests\Unit\Renderer\Xml\Node;

use Brotkrueml\FeedGenerator\Format\TextFormat;
use Brotkrueml\FeedGenerator\Renderer\Xml\Node\TextFormatNode;
use Brotkrueml\FeedGenerator\ValueObject\Text;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Brotkrueml\FeedGenerator\Renderer\Xml\Node\TextFormatNode
 */
final class TextFormatNodeTest extends TestCase
{
    private \DOMDocument $document;
    private TextFormatNode $subject;

    protected function setUp(): void
    {
        $this->document = new \DOMDocument('1.0', 'utf-8');
        $this->document->formatOutput = true;

        $rootElement = $this->document->appendChild($this->document->createElement('root'));

        $this->subject = new TextFormatNode($this->document, $rootElement);
    }

    /**
     * @test
     */
    public function valueIsEmptyThenNoNodeIsAppended(): void
    {
        $this->subject->add('foo', new Text('', TextFormat::HTML));

        $expected = <<<XML
<?xml version="1.0" encoding="utf-8"?>
<root/>
XML;

        self::assertXmlStringEqualsXmlString($expected, $this->document->saveXML());
    }

    /**
     * @test
     */
    public function valueIsNotEmptyThenNodeIsAppended(): void
    {
        $this->subject->add('foo', new Text('bar', TextFormat::HTML));

        $expected = <<<XML
<?xml version="1.0" encoding="utf-8"?>
<root>
  <foo type="html">bar</foo>
</root>
XML;

        self::assertXmlStringEqualsXmlString($expected, $this->document->saveXML());
    }

    /**
     * @test
     */
    public function multipleCallsAddMultipleNodes(): void
    {
        $this->subject->add('foo', new Text('bar', TextFormat::HTML));
        $this->subject->add('qux', new Text('quu', TextFormat::TEXT));
        $this->subject->add('qux', new Text('baz', TextFormat::HTML));
        $expected = <<<XML
<?xml version="1.0" encoding="utf-8"?>
<root>
  <foo type="html">bar</foo>
  <qux type="text">quu</qux>
  <qux type="html">baz</qux>
</root>
XML;

        self::assertXmlStringEqualsXmlString($expected, $this->document->saveXML());
    }
}
