<?php

declare(strict_types=1);

/*
 * This file is part of the "feed_generator" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\FeedGenerator\ConfigurationModule;

use Brotkrueml\FeedGenerator\Configuration\ExtensionRegistryInterface;
use TYPO3\CMS\Lowlevel\ConfigurationModuleProvider\ProviderInterface;

/**
 * @internal
 */
final class ExtensionProvider implements ProviderInterface
{
    private string $identifier;

    public function __construct(
        private readonly ExtensionRegistryInterface $extensionRegistry,
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
        return 'Feed Generator: Extensions';
    }

    /**
     * @return string[]|list<array<string, string>>
     */
    public function getConfiguration(): array
    {
        $extensions = $this->extensionRegistry->getAllExtensions();
        if ($extensions === []) {
            return ['No extensions available'];
        }

        $result = [];
        foreach ($extensions as $extension) {
            $result[] = [
                'Qualified name' => $extension->getQualifiedName(),
                'Namespace' => $extension->getNamespace(),
            ];
        }

        \usort(
            $result,
            static fn (array $a, array $b): int => $a['Qualified name'] <=> $b['Qualified name']
        );

        return $result;
    }
}
