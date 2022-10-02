<?php

declare(strict_types=1);

/*
 * This file is part of the "feed_generator" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\FeedGenerator\Configuration;

use Brotkrueml\FeedGenerator\Attributes\Cache;
use Brotkrueml\FeedGenerator\Attributes\Feed;
use Brotkrueml\FeedGenerator\Contract\FeedInterface;

/**
 * @internal
 */
final class FeedRegistry implements FeedRegistryInterface
{
    /**
     * @var FeedConfiguration[]
     */
    private readonly array $configurations;

    /**
     * @param iterable<FeedInterface> $configuredFeeds
     */
    public function __construct(iterable $configuredFeeds)
    {
        $configurations = [];
        foreach ($configuredFeeds as $configuredFeed) {
            $feedAttributes = \array_map(
                static fn (\ReflectionAttribute $attrib): Feed => $attrib->newInstance(),
                (new \ReflectionClass($configuredFeed))->getAttributes(Feed::class)
            ) ?: throw new \DomainException(
                \sprintf(
                    'Mandatory attribute "%s" is missing for class "%s" which implements "%s"',
                    Feed::class,
                    $configuredFeed::class,
                    FeedInterface::class,
                ),
                1647595664
            );

            $cacheAttributes = (new \ReflectionClass($configuredFeed))->getAttributes(Cache::class);

            foreach ($feedAttributes as $attribute) {
                $configurations[] = new FeedConfiguration(
                    $configuredFeed,
                    $attribute->path,
                    $attribute->format,
                    $attribute->siteIdentifiers,
                    $cacheAttributes !== [] ? $cacheAttributes[0]->newInstance()->seconds : null,
                );
            }
        }

        $this->configurations = $configurations;
    }

    public function getConfigurationBySiteIdentifierAndPath(string $siteIdentifier, string $path): ?FeedConfiguration
    {
        $filteredConfigurations = \array_filter(
            $this->configurations,
            static fn (FeedConfiguration $configuration): bool => (
                $configuration->siteIdentifiers === []
                    || \in_array($siteIdentifier, $configuration->siteIdentifiers, true)
            )
                && $configuration->path === $path
        );

        if ($filteredConfigurations === []) {
            return null;
        }

        return \array_shift($filteredConfigurations);
    }

    /**
     * @return FeedConfiguration[]
     */
    public function getAllConfigurations(): array
    {
        return $this->configurations;
    }
}
