<?php

declare(strict_types=1);

/*
 * This file is part of the "feed_generator" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\FeedGenerator\Tests\Unit\ValueObject;

use Brotkrueml\FeedGenerator\ValueObject\Category;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class CategoryTest extends TestCase
{
    #[Test]
    public function propertiesAreInitialisedCorrectlyWhenNotGiven(): void
    {
        $subject = new Category('some-term');

        self::assertSame('some-term', $subject->getTerm());
        self::assertSame('', $subject->getScheme());
        self::assertSame('', $subject->getLabel());
    }

    #[Test]
    public function getSchemeReturnsSchemeCorrectly(): void
    {
        $subject = new Category('some-term', 'https://example.org/some-scheme');

        self::assertSame('https://example.org/some-scheme', $subject->getScheme());
    }

    #[Test]
    public function getLabelReturnsLabelCorrectly(): void
    {
        $subject = new Category('some-term', label: 'Some Label');

        self::assertSame('Some Label', $subject->getLabel());
    }
}
