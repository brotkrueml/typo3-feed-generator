<?php

declare(strict_types=1);

/*
 * This file is part of the "feed_generator" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\FeedGenerator\Tests\Unit\Collection;

use Brotkrueml\FeedGenerator\Collection\Collection;
use Brotkrueml\FeedGenerator\Collection\IndexNotFoundException;
use Brotkrueml\FeedGenerator\Contract\ExtensionContentInterface;
use Brotkrueml\FeedGenerator\Tests\Fixtures\Entity\ItemFixture;
use Brotkrueml\FeedGenerator\Tests\Fixtures\ValueObject\AttachmentFixture;
use Brotkrueml\FeedGenerator\Tests\Fixtures\ValueObject\AuthorFixture;
use Brotkrueml\FeedGenerator\Tests\Fixtures\ValueObject\CategoryFixture;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class CollectionTest extends TestCase
{
    private Collection $subject;

    protected function setUp(): void
    {
        $this->subject = new Collection();
    }

    #[Test]
    public function collectionIsInitiallyEmpty(): void
    {
        self::assertTrue($this->subject->isEmpty());
    }

    #[Test]
    public function getThrowsExceptionWhenIndexIsNotAvailable(): void
    {
        $this->expectException(IndexNotFoundException::class);

        $this->subject->get(0);
    }

    #[Test]
    public function addAcceptsAnImplementationOfAttachmentInterface(): void
    {
        self::expectNotToPerformAssertions();

        $this->subject->add(new AttachmentFixture());
    }

    #[Test]
    public function addAcceptsAnImplementationOfAuthorInterface(): void
    {
        self::expectNotToPerformAssertions();

        $this->subject->add(new AuthorFixture());
    }

    #[Test]
    public function addAcceptsAnImplementationOfExtensionContentInterface(): void
    {
        self::expectNotToPerformAssertions();

        $extensionContent = new class implements ExtensionContentInterface {};

        $this->subject->add($extensionContent);
    }

    #[Test]
    public function addAcceptsAnImplementationOfCategoryInterface(): void
    {
        self::expectNotToPerformAssertions();

        $this->subject->add(new CategoryFixture());
    }

    #[Test]
    public function addAcceptsAnImplementationOfItemInterface(): void
    {
        self::expectNotToPerformAssertions();

        $this->subject->add(new ItemFixture());
    }

    #[Test]
    public function addReturnsCollectionItself(): void
    {
        self::assertSame($this->subject, $this->subject->add());
    }

    #[Test]
    public function addAndGetWithOneItem(): void
    {
        $author = new AuthorFixture();

        $this->subject->add($author);

        self::assertSame($author, $this->subject->get(0));
    }

    #[Test]
    public function addAndGetWithTwoItems(): void
    {
        $author1 = new AuthorFixture();
        $author2 = new AuthorFixture();

        $this->subject->add($author1, $author2);

        self::assertSame($author1, $this->subject->get(0));
        self::assertSame($author2, $this->subject->get(1));
    }

    #[Test]
    public function isEmptyReturnsFalseWhenAnItemIsAvailable(): void
    {
        $author = new AuthorFixture();

        $this->subject->add($author);

        self::assertFalse($this->subject->isEmpty());
    }

    #[Test]
    public function getIterator(): void
    {
        $author1 = new AuthorFixture();
        $author2 = new AuthorFixture();

        $this->subject->add($author1, $author2);

        $actual = $this->subject->getIterator();

        self::assertCount(2, $actual);
        self::assertSame($author1, $actual[0]);
        self::assertSame($author2, $actual[1]);
    }
}
