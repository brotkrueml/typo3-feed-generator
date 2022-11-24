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
use PHPUnit\Framework\TestCase;

final class AtomLinkNodeTest extends TestCase
{
    private \DOMDocument $document;
    private AtomLinkNode $subject;

    protected function setUp(): void
    {
        $this->document = new \DOMDocument('1.0', 'utf-8');
        $this->document->formatOutput = true;

        $rootElement = $this->document->appendChild($this->document->createElement('root'));

        $this->subject = new AtomLinkNode($this->document, $rootElement);
    }

    /**
     * @test
     */
    public function hrefIsEmptyThenExceptionIsThrown(): void
    {
        $this->expectException(MissingRequiredPropertyException::class);
        $this->expectErrorMessageMatches('#link/href#');

        $this->subject->add('', 'alternative', 'text/html');
    }

    /**
     * @test
     */
    public function relIsEmptyThenExceptionIsThrown(): void
    {
        $this->expectException(MissingRequiredPropertyException::class);
        $this->expectErrorMessageMatches('#link/rel#');

        $this->subject->add('https://example.org/', '', 'text/html');
    }

    /**
     * @test
     */
    public function typeIsEmptyThenExceptionIsThrown(): void
    {
        $this->expectException(MissingRequiredPropertyException::class);
        $this->expectErrorMessageMatches('#link/type#');

        $this->subject->add('https://example.org/', 'alternative', '');
    }

    /**
     * @test
     */
    public function allValuesAreGivenThenNodeIsAdded(): void
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
}
