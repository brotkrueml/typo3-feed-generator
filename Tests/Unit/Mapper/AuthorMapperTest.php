<?php

declare(strict_types=1);

/*
 * This file is part of the "feed_generator" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\FeedGenerator\Tests\Unit\Mapper;

use Brotkrueml\FeedGenerator\Feed\AuthorInterface;
use Brotkrueml\FeedGenerator\Mapper\AuthorMapper;
use PHPUnit\Framework\TestCase;

final class AuthorMapperTest extends TestCase
{
    private AuthorMapper $subject;

    protected function setUp(): void
    {
        $this->subject = new AuthorMapper();
    }

    /**
     * @test
     */
    public function mapReturnsFeedIoAuthorCorrectly(): void
    {
        $author = new class() implements AuthorInterface {
            public function getName(): string
            {
                return 'some name';
            }

            public function getUri(): string
            {
                return 'some uri';
            }

            public function getEmail(): string
            {
                return 'some email';
            }
        };

        $actual = $this->subject->map($author);

        self::assertSame('some name', $actual->getName());
        self::assertSame('some uri', $actual->getUri());
        self::assertSame('some email', $actual->getEmail());
    }
}
