<?php

declare(strict_types=1);

/*
 * This file is part of the "feed_generator" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\FeedGenerator\Tests\Unit\Collection;

use Brotkrueml\FeedGenerator\Collection\XmlNamespaceCollection;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class XmlNamespaceCollectionTest extends TestCase
{
    private XmlNamespaceCollection $subject;

    protected function setUp(): void
    {
        $this->subject = new XmlNamespaceCollection();
    }

    #[Test]
    public function collectionIsEmptyAfterInitialisation(): void
    {
        $actual = 0;
        foreach ($this->subject as $namespace) {
            $actual++;
        }

        self::assertSame(0, $actual);
    }

    #[Test]
    public function addCalledMultipleTimesWithDifferentNamespaces(): void
    {
        $this->subject->add('one', 'http://example.org/one');
        $this->subject->add('two', 'http://example.org/two');
        $this->subject->add('three', 'http://example.org/three');

        $actual = [];
        foreach ($this->subject as $qualifiedName => $namespace) {
            $actual[$qualifiedName] = $namespace;
        }

        self::assertCount(3, $actual);
        self::assertArrayHasKey('one', $actual);
        self::assertSame('http://example.org/one', $actual['one']);
        self::assertArrayHasKey('two', $actual);
        self::assertSame('http://example.org/two', $actual['two']);
        self::assertArrayHasKey('three', $actual);
        self::assertSame('http://example.org/three', $actual['three']);
    }

    #[Test]
    public function addCalledTwiceWithSameQualifiedNameOverridesFirstOne(): void
    {
        $this->subject->add('foo', 'http://example.org/foo');
        $this->subject->add('foo', 'http://example.org/bar');

        $actual = [];
        foreach ($this->subject as $qualifiedName => $namespace) {
            $actual[$qualifiedName] = $namespace;
        }

        self::assertCount(1, $actual);
        self::assertArrayHasKey('foo', $actual);
        self::assertSame('http://example.org/bar', $actual['foo']);
    }
}
