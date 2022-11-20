<?php

declare(strict_types=1);

/*
 * This file is part of the "feed_generator" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\FeedGenerator\Tests\Unit\Configuration;

use Brotkrueml\FeedGenerator\Configuration\ExtensionForElementNotFoundException;
use Brotkrueml\FeedGenerator\Format\FeedFormat;
use Brotkrueml\FeedGenerator\Tests\Fixtures\ValueObject\ExtensionElementFixture;
use PHPUnit\Framework\TestCase;

final class ExtensionForElementNotFoundExceptionTest extends TestCase
{
    /**
     * @test
     */
    public function forElement(): void
    {
        $actual = ExtensionForElementNotFoundException::forFormatAndElement(FeedFormat::RSS, new ExtensionElementFixture());

        self::assertSame(
            'Extension for format "rss" and element "Brotkrueml\FeedGenerator\Tests\Fixtures\ValueObject\ExtensionElementFixture" not found.',
            $actual->getMessage()
        );
        self::assertSame(1668878017, $actual->getCode());
    }
}
