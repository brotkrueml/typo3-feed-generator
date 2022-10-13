<?php

declare(strict_types=1);

/*
 * This file is part of the "feed_generator" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\FeedGenerator\Tests\Unit\Package;

use Brotkrueml\FeedGenerator\Format\FeedFormat;
use Brotkrueml\FeedGenerator\Package\PackageNotInstalledException;
use PHPUnit\Framework\TestCase;

final class PackageNotInstalledExceptionTest extends TestCase
{
    /**
     * @test
     * @dataProvider provider
     */
    public function fromFormat(FeedFormat $format): void
    {
        $actual = PackageNotInstalledException::fromFormat($format);

        self::assertStringContainsString($format->package()->package, $actual->getMessage());
        self::assertStringContainsString($format->package()->constraint, $actual->getMessage());
        self::assertStringContainsString($format->format(), $actual->getMessage());
        self::assertSame(1665683416, $actual->getCode());
    }

    public function provider(): iterable
    {
        yield 'atom' => [
            FeedFormat::ATOM,
        ];

        yield 'json' => [
            FeedFormat::JSON,
        ];

        yield 'rss' => [
            FeedFormat::RSS,
        ];
    }
}
