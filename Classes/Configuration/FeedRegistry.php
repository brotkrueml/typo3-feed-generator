<?php

declare(strict_types=1);

/*
 * This file is part of the "feed_generator" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\FeedGenerator\Configuration;

use Brotkrueml\FeedGenerator\Feed\FeedInterface;

/**
 * @internal
 */
final class FeedRegistry
{
    private readonly array $configurations;

    public function __construct(iterable $configuredFeeds)
    {
        $configurations = [];
        foreach ($configuredFeeds as $configuredFeed) {
            $reflectionClass = new \ReflectionClass($configuredFeed);
            $attributes = $reflectionClass->getAttributes(\Brotkrueml\FeedGenerator\Attributes\Feed::class);
            foreach ($attributes as $attribute) {
                $feed = $attribute->newInstance();
                $configurations[] = new FeedConfiguration(
                    $configuredFeed,
                    $feed->path,
                    $feed->siteIdentifiers,
                );
            }
        }

        $this->configurations = $configurations;
    }

    public function getFeedBySiteIdentifierAndPath(string $siteIdentifier, string $path): ?FeedInterface
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

        $configuration = \array_shift($filteredConfigurations);

        return $configuration->instance;
    }
}
