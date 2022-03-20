<?php

declare(strict_types=1);

/*
 * This file is part of the "feed_generator" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\FeedGenerator\Configuration;

use Brotkrueml\FeedGenerator\Attributes\Path;
use Brotkrueml\FeedGenerator\Feed\FeedInterface;

/**
 * @internal
 */
final class FeedRegistry
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
            $pathAttributes = \array_map(
                static fn (\ReflectionAttribute $attrib) => $attrib->newInstance(),
                (new \ReflectionClass($configuredFeed))->getAttributes(Path::class)
            ) ?: throw new \DomainException(
                \sprintf(
                    'Mandatory attribute "%s" is missing for class "%s" which implements "%s"',
                    Path::class,
                    $configuredFeed::class,
                    FeedInterface::class,
                ),
                1647595664
            );

            foreach ($pathAttributes as $attribute) {
                $configurations[] = new FeedConfiguration(
                    $configuredFeed,
                    $attribute->path,
                    $attribute->siteIdentifiers,
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
