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
use Brotkrueml\FeedGenerator\Renderer\Xml\Node\AtomCategoryNode;
use Brotkrueml\FeedGenerator\ValueObject\Category;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Brotkrueml\FeedGenerator\Renderer\Xml\Node\AtomCategoryNode
 */
final class AtomCategoryNodeTest extends TestCase
{
    private \DOMDocument $document;
    private AtomCategoryNode $subject;

    protected function setUp(): void
    {
        $this->document = new \DOMDocument('1.0', 'utf-8');
        $this->document->formatOutput = true;

        $rootElement = $this->document->appendChild($this->document->createElement('root'));

        $this->subject = new AtomCategoryNode($this->document, $rootElement);
    }

    /**
     * @test
     */
    public function termIsEmptyThenAnExceptionIsThrown(): void
    {
        $this->expectException(MissingRequiredPropertyException::class);
        $this->expectErrorMessageMatches('#category#');

        $this->subject->add(new Category(''));
    }

    /**
     * @test
     */
    public function onlyTermIsGiven(): void
    {
        $this->subject->add(new Category('some term'));

        $expected = <<<XML
<?xml version="1.0" encoding="utf-8"?>
<root>
  <category term="some term"/>
</root>
XML;

        self::assertXmlStringEqualsXmlString($expected, $this->document->saveXML());
    }

    /**
     * @test
     */
    public function termAndSchemaAreGiven(): void
    {
        $this->subject->add(new Category('some term', 'https://example.org/some-term'));

        $expected = <<<XML
<?xml version="1.0" encoding="utf-8"?>
<root>
  <category term="some term" scheme="https://example.org/some-term"/>
</root>
XML;

        self::assertXmlStringEqualsXmlString($expected, $this->document->saveXML());
    }

    /**
     * @test
     */
    public function termAndLabelAreGiven(): void
    {
        $this->subject->add(new Category('some term', label: 'some label'));

        $expected = <<<XML
<?xml version="1.0" encoding="utf-8"?>
<root>
  <category term="some term" label="some label"/>
</root>
XML;

        self::assertXmlStringEqualsXmlString($expected, $this->document->saveXML());
    }

    /**
     * @test
     */
    public function termSchemeAndLabelAreGiven(): void
    {
        $this->subject->add(
            new Category('some term', 'https://example.org/some-term', 'some label'),
        );

        $expected = <<<XML
<?xml version="1.0" encoding="utf-8"?>
<root>
  <category term="some term" scheme="https://example.org/some-term" label="some label"/>
</root>
XML;

        self::assertXmlStringEqualsXmlString($expected, $this->document->saveXML());
    }
}
