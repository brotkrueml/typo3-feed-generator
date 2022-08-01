<?php

declare(strict_types=1);

/*
 * This file is part of the "feed_generator" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\FeedGenerator\Tests\Unit\Feed;

use Brotkrueml\FeedGenerator\Feed\Author;
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
    public function getEmailReturnsEmailCorrectly(): void
    {
        $subject = new Author('Some Name', 'some email');

        self::assertSame('some email', $subject->getEmail());
    }

    /**
     * @test
     */
    public function getUriReturnsUriCorrectly(): void
    {
        $subject = new Author('Some Name', uri: 'some uri');

        self::assertSame('some uri', $subject->getUri());
    }
}
