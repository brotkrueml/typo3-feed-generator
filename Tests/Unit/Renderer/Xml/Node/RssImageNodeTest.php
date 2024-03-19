<?php

declare(strict_types=1);

/*
 * This file is part of the "feed_generator" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\FeedGenerator\Tests\Unit\Renderer\Xml\Node;

use Brotkrueml\FeedGenerator\Contract\ImageInterface;
use Brotkrueml\FeedGenerator\Renderer\IntegerNotInRangeException;
use Brotkrueml\FeedGenerator\Renderer\MissingRequiredPropertyException;
use Brotkrueml\FeedGenerator\Renderer\Xml\Node\RssImageNode;
use Brotkrueml\FeedGenerator\ValueObject\Image;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Brotkrueml\FeedGenerator\Renderer\Xml\Node\RssImageNode
 */
final class RssImageNodeTest extends TestCase
{
    private \DOMDocument $document;
    private RssImageNode $subject;

    protected function setUp(): void
    {
        $this->document = new \DOMDocument('1.0', 'utf-8');
        $this->document->formatOutput = true;

        $rootElement = $this->document->appendChild($this->document->createElement('root'));

        $this->subject = new RssImageNode($this->document, $rootElement);
    }

    /**
     * @test
     */
    public function urlIsEmptyThenAnExceptionIsThrown(): void
    {
        $this->expectException(MissingRequiredPropertyException::class);
        $this->expectExceptionMessageMatches('#image/url#');

        $this->subject->add(new Image(''));
    }

    /**
     * @test
     */
    public function titleIsEmptyThenAnExceptionIsThrown(): void
    {
        $this->expectException(MissingRequiredPropertyException::class);
        $this->expectExceptionMessageMatches('#image/title#');

        $this->subject->add(
            new Image('https://example.org/some-image.png'),
        );
    }

    /**
     * @test
     */
    public function linkIsEmptyThenAnExceptionIsThrown(): void
    {
        $this->expectException(MissingRequiredPropertyException::class);
        $this->expectExceptionMessageMatches('#image/link#');

        $this->subject->add(
            new Image('https://example.org/some-image.png', 'Some title'),
        );
    }

    /**
     * @test
     */
    public function widthIsToLargeThenAnExceptionIsThrown(): void
    {
        $this->expectException(IntegerNotInRangeException::class);
        $this->expectExceptionMessageMatches('#image/width#');

        $this->subject->add(
            new Image(
                'https://example.org/some-image.png',
                'Some title',
                'image/png',
                145,
            ),
        );
    }

    /**
     * @test
     */
    public function heightIsToLargeThenAnExceptionIsThrown(): void
    {
        $this->expectException(IntegerNotInRangeException::class);
        $this->expectExceptionMessageMatches('#image/height#');

        $this->subject->add(
            new Image(
                'https://example.org/some-image.png',
                'Some title',
                'image/png',
                height: 401,
            ),
        );
    }

    /**
     * @test
     * @dataProvider provider
     */
    public function nodeIsAddedCorrectly(?ImageInterface $image, string $expected): void
    {
        $this->subject->add($image);

        self::assertXmlStringEqualsXmlString($expected, $this->document->saveXml());
    }

    public function provider(): iterable
    {
        yield 'Image is null' => [
            'image' => null,
            'expected' => <<<XML
<?xml version="1.0" encoding="utf-8"?>
<root/>
XML,
        ];

        yield 'Only minimum properties are given' => [
            'image' => new Image(
                'https://example.org/some-image.png',
                'Some image',
                'image/png',
            ),
            'expected' => <<<XML
<?xml version="1.0" encoding="utf-8"?>
<root>
  <image>
    <url>https://example.org/some-image.png</url>
    <title>Some image</title>
    <link>image/png</link>
  </image>
</root>
XML,
        ];

        yield 'Width is maximum allowed value' => [
            'image' => new Image(
                'https://example.org/some-image.png',
                'Some image',
                'image/png',
                144,
            ),
            'expected' => <<<XML
<?xml version="1.0" encoding="utf-8"?>
<root>
  <image>
    <url>https://example.org/some-image.png</url>
    <title>Some image</title>
    <link>image/png</link>
    <width>144</width>
  </image>
</root>
XML,
        ];

        yield 'Height is maximum allowed value' => [
            'image' => new Image(
                'https://example.org/some-image.png',
                'Some image',
                'image/png',
                height: 400,
            ),
            'expected' => <<<XML
<?xml version="1.0" encoding="utf-8"?>
<root>
  <image>
    <url>https://example.org/some-image.png</url>
    <title>Some image</title>
    <link>image/png</link>
    <height>400</height>
  </image>
</root>
XML,
        ];

        yield 'Description is given' => [
            'image' => new Image(
                'https://example.org/some-image.png',
                'Some image',
                'image/png',
                description: 'Some description',
            ),
            'expected' => <<<XML
<?xml version="1.0" encoding="utf-8"?>
<root>
  <image>
    <url>https://example.org/some-image.png</url>
    <title>Some image</title>
    <link>image/png</link>
    <description>Some description</description>
  </image>
</root>
XML,
        ];

        yield 'All properties is given' => [
            'image' => new Image(
                'https://example.org/some-image.png',
                'Some image',
                'image/png',
                123,
                234,
                'Some description',
            ),
            'expected' => <<<XML
<?xml version="1.0" encoding="utf-8"?>
<root>
  <image>
    <url>https://example.org/some-image.png</url>
    <title>Some image</title>
    <link>image/png</link>
    <width>123</width>
    <height>234</height>
    <description>Some description</description>
  </image>
</root>
XML,
        ];
    }
}
