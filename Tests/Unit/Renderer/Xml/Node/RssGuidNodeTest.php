<?php

declare(strict_types=1);

/*
 * This file is part of the "feed_generator" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\FeedGenerator\Tests\Unit\Renderer\Xml\Node;

use Brotkrueml\FeedGenerator\Renderer\Xml\Node\RssGuidNode;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class RssGuidNodeTest extends TestCase
{
    private \DOMDocument $document;
    private RssGuidNode $subject;

    protected function setUp(): void
    {
        $this->document = new \DOMDocument('1.0', 'utf-8');
        $this->document->formatOutput = true;

        $rootElement = $this->document->appendChild($this->document->createElement('root'));

        $this->subject = new RssGuidNode($this->document, $rootElement);
    }

    #[Test]
    #[DataProvider('provider')]
    public function guidIsEmptyThenNoNodeIsAdded(string $guid, string $expected): void
    {
        $this->subject->add($guid);

        self::assertXmlStringEqualsXmlString($expected, $this->document->saveXML());
    }

    public static function provider(): iterable
    {
        yield 'guid is empty' => [
            'guid' => '',
            'expected' => <<<XML
<?xml version="1.0" encoding="utf-8"?>
<root/>
XML,
        ];

        yield 'guid is a random string' => [
            'guid' => 'some-random-string',
            'expected' => <<<XML
<?xml version="1.0" encoding="utf-8"?>
<root>
  <guid isPermaLink="false">some-random-string</guid>
</root>
XML,
        ];

        yield 'guid is a random string starting with http' => [
            'guid' => 'http-random-string',
            'expected' => <<<XML
<?xml version="1.0" encoding="utf-8"?>
<root>
  <guid isPermaLink="false">http-random-string</guid>
</root>
XML,
        ];

        yield 'guid is a URL' => [
            'guid' => 'https://example.org/some-link',
            'expected' => <<<XML
<?xml version="1.0" encoding="utf-8"?>
<root>
  <guid isPermaLink="true">https://example.org/some-link</guid>
</root>
XML,
        ];
    }
}
