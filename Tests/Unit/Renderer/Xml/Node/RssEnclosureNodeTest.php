<?php

declare(strict_types=1);

/*
 * This file is part of the "feed_generator" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\FeedGenerator\Tests\Unit\Renderer\Xml\Node;

use Brotkrueml\FeedGenerator\Renderer\IntegerNotInRangeException;
use Brotkrueml\FeedGenerator\Renderer\MissingRequiredPropertyException;
use Brotkrueml\FeedGenerator\Renderer\Xml\Node\RssEnclosureNode;
use Brotkrueml\FeedGenerator\ValueObject\Attachment;
use PHPUnit\Framework\TestCase;

final class RssEnclosureNodeTest extends TestCase
{
    private \DOMDocument $document;
    private RssEnclosureNode $subject;

    protected function setUp(): void
    {
        $this->document = new \DOMDocument('1.0', 'utf-8');
        $this->document->formatOutput = true;

        $rootElement = $this->document->appendChild($this->document->createElement('root'));

        $this->subject = new RssEnclosureNode($this->document, $rootElement);
    }

    /**
     * @test
     */
    public function urlIsEmptyThenExceptionIsThrown(): void
    {
        $this->expectException(MissingRequiredPropertyException::class);
        $this->expectExceptionMessageMatches('#item/enclosure/url#');

        $this->subject->add(
            new Attachment(''),
        );
    }

    /**
     * @test
     */
    public function typeIsEmptyThenExceptionIsThrown(): void
    {
        $this->expectException(MissingRequiredPropertyException::class);
        $this->expectExceptionMessageMatches('#item/enclosure/type#');

        $this->subject->add(
            new Attachment('https://example.org/some-enclosure'),
        );
    }

    /**
     * @test
     */
    public function lengthIsZeroThenExceptionIsThrown(): void
    {
        $this->expectException(IntegerNotInRangeException::class);
        $this->expectExceptionMessageMatches('#item/enclosure/length#');

        $this->subject->add(
            new Attachment('https://example.org/some-enclosure', 'some/type', 0),
        );
    }

    /**
     * @test
     */
    public function allValuesAreGivenThenNodeIsAdded(): void
    {
        $this->subject->add(
            new Attachment('https://example.org/some-enclosure', 'some/type', 123456),
        );

        $expected = <<<XML
<?xml version="1.0" encoding="utf-8"?>
<root>
  <enclosure length="123456" type="some/type" url="https://example.org/some-enclosure"/>
</root>
XML;

        self::assertXmlStringEqualsXmlString($expected, $this->document->saveXML());
    }
}
