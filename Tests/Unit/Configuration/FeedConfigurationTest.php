<?php

declare(strict_types=1);

/*
 * This file is part of the "feed_generator" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\FeedGenerator\Tests\Unit\Configuration;

use Brotkrueml\FeedGenerator\Configuration\FeedConfiguration;
use Brotkrueml\FeedGenerator\Format\FeedFormat;
use Brotkrueml\FeedGenerator\Tests\Fixtures\FeedConfiguration\SomeFeed;
use PHPUnit\Framework\Attributes\DoesNotPerformAssertions;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class FeedConfigurationTest extends TestCase
{
    #[Test]
    #[DoesNotPerformAssertions]
    public function instantiatingWithCacheInSecondsWithNullIsValid(): void
    {
        new FeedConfiguration(
            new SomeFeed(),
            '/some/path',
            FeedFormat::ATOM,
            [],
            null,
        );
    }

    #[Test]
    #[DoesNotPerformAssertions]
    public function instantiatingWithCacheInSecondsWith0IsValid(): void
    {
        new FeedConfiguration(
            new SomeFeed(),
            '/some/path',
            FeedFormat::ATOM,
            [],
            0,
        );
    }

    #[Test]
    public function instantiatingWithCacheInSecondsWithANegativeNumberThrowsException(): void
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionCode(1655707760);
        $this->expectExceptionMessage('The configured cache seconds (-1) is a negative int');

        new FeedConfiguration(
            new SomeFeed(),
            '/some/path',
            FeedFormat::ATOM,
            [],
            -1,
        );
    }
}
