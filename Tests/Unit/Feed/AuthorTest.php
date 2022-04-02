<?php

declare(strict_types=1);


namespace Brotkrueml\FeedGenerator\Tests\Unit\Feed;

use Brotkrueml\FeedGenerator\Feed\Author;
use Brotkrueml\FeedGenerator\Feed\Item;
use PHPUnit\Framework\TestCase;

final class AuthorTest extends TestCase
{
    /**
     * @test
     */
    public function propertiesAreInitialisedCorrectlyWhenNotGiven(): void
    {
        $subject = new Author('Some Name');

        self::assertSame('Some Name', $subject->getName());
        self::assertSame('', $subject->getUri());
        self::assertSame('', $subject->getEmail());
    }

    /**
     * @test
     */
    public function getUriReturnsUriCorrectly(): void
    {
        $subject = new Author('Some Name', 'some uri');

        self::assertSame('some uri', $subject->getUri());
    }

    /**
     * @test
     */
    public function getEmailReturnsEmailCorrectly(): void
    {
        $subject = new Author('Some Name', email: 'some email');

        self::assertSame('some email', $subject->getEmail());
    }
}
