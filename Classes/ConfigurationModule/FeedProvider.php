<?php

declare(strict_types=1);

/*
 * This file is part of the "feed_generator" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\FeedGenerator\ConfigurationModule;

use Brotkrueml\FeedGenerator\Configuration\FeedRegistryInterface;
use TYPO3\CMS\Lowlevel\ConfigurationModuleProvider\ProviderInterface;

/**
 * @internal
 */
final class FeedProvider implements ProviderInterface
{
    private string $identifier;

    public function __construct(
        private readonly FeedRegistryInterface $feedRegistry,
    ) {
    }

    /**
     * @param array{identifier: string} $attributes
     */
    public function __invoke(array $attributes): ProviderInterface
    {
        $this->identifier = $attributes['identifier'];

        return $this;
    }

    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    public function getLabel(): string
    {
        return 'Feed Generator: Feeds';
    }

    /**
     * @noRector AddArrayReturnDocTypeRector
     * @return string[]|array{class-string, array<mixed>}
     */
    public function getConfiguration(): array
    {
        $configurations = $this->feedRegistry->getAllConfigurations();
        if ($configurations === []) {
            return ['No feeds found'];
        }

        /** @var array<class-string, list<array{'Site identifier'?: string, 'Site identifiers'?: string, Path: string, Format: string}>> $feeds */
        $feeds = [];
        foreach ($configurations as $configuration) {
            $feed = [];
            if ($configuration->siteIdentifiers !== []) {
                $feed['Site identifier' . (\count($configuration->siteIdentifiers) > 1 ? 's' : '')] =
                    \implode(', ', $configuration->siteIdentifiers);
            }
            $feed += [
                'Path' => $configuration->path,
                'Format' => $configuration->format->caseSensitive(),
            ];

            $feeds[$configuration->instance::class][] = $feed;
        }

        foreach ($feeds as $class => $feedsPerClass) {
            /** @noRector CountOnNullRector */
            if (\count($feedsPerClass) === 1) {
                $feeds[$class] = $feedsPerClass[0];
            }
        }

        return $feeds;
    }
}
