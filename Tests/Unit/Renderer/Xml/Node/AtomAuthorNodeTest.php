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
use Brotkrueml\FeedGenerator\Renderer\Xml\Node\AtomAuthorNode;
use Brotkrueml\FeedGenerator\ValueObject\Author;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Brotkrueml\FeedGenerator\Renderer\Xml\Node\AtomAuthorNode
 */
final class AtomAuthorNodeTest extends TestCase
{
    private \DOMDocument $document;
    private AtomAuthorNode $subject;

    protected function setUp(): void
    {
        $this->document = new \DOMDocument('1.0', 'utf-8');
        $this->document->formatOutput = true;

        $rootElement = $this->document->appendChild($this->document->createElement('root'));

        $this->subject = new AtomAuthorNode($this->document, $rootElement);
    }

    /**
     * @test
     */
    public function nameIsEmptyThenAnExceptionIsThrown(): void
    {
        $this->expectException(MissingRequiredPropertyException::class);
        $this->expectErrorMessageMatches('#author/name#');

        $this->subject->add(new Author(''));
    }

    /**
     * @test
     */
    public function onlyNameIsGiven(): void
    {
        $this->subject->add(new Author('John Doe'));

        $expected = <<<XML
<?xml version="1.0" encoding="utf-8"?>
<root>
  <author>
    <name>John Doe</name>
</author>
</root>
XML;

        self::assertXmlStringEqualsXmlString($expected, $this->document->saveXML());
    }

    /**
     * @test
     */
    public function nameAndEmailAreGiven(): void
    {
        $this->subject->add(new Author('John Doe', 'john.doe@example.org'));

        $expected = <<<XML
<?xml version="1.0" encoding="utf-8"?>
<root>
  <author>
    <name>John Doe</name>
    <email>john.doe@example.org</email>
</author>
</root>
XML;

        self::assertXmlStringEqualsXmlString($expected, $this->document->saveXML());
    }

    /**
     * @test
     */
    public function nameAndUriAreGiven(): void
    {
        $this->subject->add(new Author('John Doe', uri: 'https://example.org/john-doe'));

        $expected = <<<XML
<?xml version="1.0" encoding="utf-8"?>
<root>
  <author>
    <name>John Doe</name>
    <uri>https://example.org/john-doe</uri>
</author>
</root>
XML;

        self::assertXmlStringEqualsXmlString($expected, $this->document->saveXML());
    }

    /**
     * @test
     */
    public function nameEmailAndUriAreGiven(): void
    {
        $this->subject->add(
            new Author('John Doe', 'john.doe@example.org', 'https://example.org/john-doe'),
        );

        $expected = <<<XML
<?xml version="1.0" encoding="utf-8"?>
<root>
  <author>
    <name>John Doe</name>
    <email>john.doe@example.org</email>
    <uri>https://example.org/john-doe</uri>
</author>
</root>
XML;

        self::assertXmlStringEqualsXmlString($expected, $this->document->saveXML());
    }
}
