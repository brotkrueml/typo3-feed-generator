<?php

declare(strict_types=1);

/*
 * This file is part of the "feed_generator" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\FeedGenerator\Tests\Unit\ConfigurationModule;

use Brotkrueml\FeedGenerator\Configuration\FeedConfiguration;
use Brotkrueml\FeedGenerator\Configuration\FeedRegistryInterface;
use Brotkrueml\FeedGenerator\ConfigurationModule\FeedProvider;
use Brotkrueml\FeedGenerator\Contract\FeedInterface;
use Brotkrueml\FeedGenerator\Format\FeedFormat;
use Brotkrueml\FeedGenerator\Tests\Fixtures\FeedConfiguration\EmptyFeed;
use Brotkrueml\FeedGenerator\Tests\Fixtures\FeedConfiguration\SomeFeed;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class FeedProviderTest extends TestCase
{
    #[Test]
    public function getIdentifierReturnsCorrectIdentifier(): void
    {
        $subject = $this->getInstanceOfSubjectUnderTest([]);

        self::assertSame('some.identifier', $subject->getIdentifier());
    }

    #[Test]
    public function getLabelReturnsCorrectLabel(): void
    {
        $subject = $this->getInstanceOfSubjectUnderTest([]);

        self::assertSame('Feed Generator: Feeds', $subject->getLabel());
    }

    #[Test]
    #[DataProvider('providerForGetConfigurations')]
    public function getConfigurationsReturnsArrayWithConfigurationsCorrectly(
        array $configurations,
        array $expected,
    ): void {
        $subject = $this->getInstanceOfSubjectUnderTest($configurations);

        self::assertSame($expected, $subject->getConfiguration());
    }

    public static function providerForGetConfigurations(): iterable
    {
        yield 'No configurations available' => [
            'configurations' => [],
            'expected' => ['No feeds found'],
        ];

        yield 'One configuration in one class and one format available' => [
            'configurations' => [
                new FeedConfiguration(new SomeFeed(), '/some/path.atom', FeedFormat::ATOM, [], null),
            ],
            'expected' => [
                SomeFeed::class => [
                    'Path' => '/some/path.atom',
                    'Format' => 'Atom',
                ],
            ],
        ];

        yield 'One configuration in one class and one format with one site available' => [
            'configurations' => [
                new FeedConfiguration(new SomeFeed(), '/some/path.atom', FeedFormat::ATOM, ['some_site'], null),
            ],
            'expected' => [
                SomeFeed::class => [
                    'Site identifier' => 'some_site',
                    'Path' => '/some/path.atom',
                    'Format' => 'Atom',
                ],
            ],
        ];

        yield 'One configuration in one class and one format with two sites available' => [
            'configurations' => [
                new FeedConfiguration(new SomeFeed(), '/some/path.atom', FeedFormat::ATOM, ['some_site', 'another_site'], null),
            ],
            'expected' => [
                SomeFeed::class => [
                    'Site identifiers' => 'some_site, another_site',
                    'Path' => '/some/path.atom',
                    'Format' => 'Atom',
                ],
            ],
        ];

        yield 'Three configurations in one class available' => [
            'configurations' => [
                new FeedConfiguration(new SomeFeed(), '/some/path.atom', FeedFormat::ATOM, [], null),
                new FeedConfiguration(new SomeFeed(), '/some/path.rss', FeedFormat::RSS, [], null),
            ],
            'expected' => [
                SomeFeed::class => [
                    [
                        'Path' => '/some/path.atom',
                        'Format' => 'Atom',
                    ],
                    [
                        'Path' => '/some/path.rss',
                        'Format' => 'RSS',
                    ],
                ],
            ],
        ];

        yield 'Four configurations in two classes available' => [
            'configurations' => [
                new FeedConfiguration(new SomeFeed(), '/some/path.atom', FeedFormat::ATOM, [], null),
                new FeedConfiguration(new SomeFeed(), '/some/path.rss', FeedFormat::RSS, [], null),
                new FeedConfiguration(new EmptyFeed(), '/empty/feed', FeedFormat::RSS, ['another_site'], null),
            ],
            'expected' => [
                SomeFeed::class => [
                    [
                        'Path' => '/some/path.atom',
                        'Format' => 'Atom',
                    ],
                    [
                        'Path' => '/some/path.rss',
                        'Format' => 'RSS',
                    ],
                ],
                EmptyFeed::class => [
                    'Site identifier' => 'another_site',
                    'Path' => '/empty/feed',
                    'Format' => 'RSS',
                ],
            ],
        ];
    }

    /**
     * @param FeedConfiguration[] $configurations
     */
    private function getInstanceOfSubjectUnderTest(array $configurations): FeedProvider
    {
        $registry = new class($configurations) implements FeedRegistryInterface {
            public function __construct(
                private readonly array $configurations,
            ) {}

            public function getConfigurationBySiteIdentifierAndPath(string $siteIdentifier, string $path): ?FeedConfiguration
            {
                return null;
            }

            /**
             * @return FeedInterface[]
             */
            public function getAllConfigurations(): array
            {
                return $this->configurations;
            }
        };

        $subject = new FeedProvider($registry);
        $subject([
            'identifier' => 'some.identifier',
        ]);

        return $subject;
    }
}
