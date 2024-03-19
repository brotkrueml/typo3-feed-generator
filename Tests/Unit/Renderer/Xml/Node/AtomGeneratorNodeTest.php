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
use Brotkrueml\FeedGenerator\Renderer\Xml\Node\AtomGeneratorNode;
use PHPUnit\Framework\TestCase;

final class AtomGeneratorNodeTest extends TestCase
{
    private \DOMDocument $document;
    private AtomGeneratorNode $subject;

    protected function setUp(): void
    {
        $this->document = new \DOMDocument('1.0', 'utf-8');
        $this->document->formatOutput = true;

        $rootElement = $this->document->appendChild($this->document->createElement('root'));

        $this->subject = new AtomGeneratorNode($this->document, $rootElement);
    }

    /**
     * @test
     */
    public function nameIsEmptyThenAnExceptionIsThrown(): void
    {
        $this->expectException(MissingRequiredPropertyException::class);
        $this->expectExceptionMessageMatches('#generator#');

        $this->subject->add('', 'https://example.org/');
    }

    /**
     * @test
     */
    public function onlyNameIsGiven(): void
    {
        $this->subject->add('foo');

        $expected = <<<XML
<?xml version="1.0" encoding="utf-8"?>
<root>
  <generator>foo</generator>
</root>
XML;

        self::assertXmlStringEqualsXmlString($expected, $this->document->saveXML());
    }

    /**
     * @test
     */
    public function nameAndUriAreGiven(): void
    {
        $this->subject->add('foo', 'https://example.org/foo');

        $expected = <<<XML
<?xml version="1.0" encoding="utf-8"?>
<root>
  <generator uri="https://example.org/foo">foo</generator>
</root>
XML;

        self::assertXmlStringEqualsXmlString($expected, $this->document->saveXML());
    }

    /**
     * @test
     */
    public function nameAndVersionAreGiven(): void
    {
        $this->subject->add('foo', version: '1.2');

        $expected = <<<XML
<?xml version="1.0" encoding="utf-8"?>
<root>
  <generator version="1.2">foo</generator>
</root>
XML;

        self::assertXmlStringEqualsXmlString($expected, $this->document->saveXML());
    }

    /**
     * @test
     */
    public function nameUriAndVersionAreGiven(): void
    {
        $this->subject->add('foo', 'https://example.org/foo', '1.2');

        $expected = <<<XML
<?xml version="1.0" encoding="utf-8"?>
<root>
  <generator uri="https://example.org/foo" version="1.2">foo</generator>
</root>
XML;

        self::assertXmlStringEqualsXmlString($expected, $this->document->saveXML());
    }
}
