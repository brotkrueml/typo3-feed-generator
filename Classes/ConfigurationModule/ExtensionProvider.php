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
use Brotkrueml\FeedGenerator\Contract\JsonExtensionInterface;
use Brotkrueml\FeedGenerator\Contract\XmlExtensionInterface;
use Brotkrueml\FeedGenerator\Format\FeedFormat;
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
        if (\count($extensions) === 0) {
            return ['No extensions available'];
        }

        $result = [];
        foreach ($extensions as $extension) {
            $item = [
                'Qualified name' => $extension->getQualifiedName(),
            ];

            $formats = [];
            if ($extension instanceof XmlExtensionInterface) {
                $formats = [FeedFormat::ATOM, FeedFormat::RSS];
                $item['Namespace'] = $extension->getNamespace();
            }
            if ($extension instanceof JsonExtensionInterface) {
                $formats[] = FeedFormat::JSON;
                $item['About'] = $extension->getAbout();
            }

            $formatKey = 'Feed format' . (count($formats) > 1 ? 's' : '');
            $item[$formatKey] = \implode(
                ', ',
                \array_map(static fn (FeedFormat $format): string => $format->caseSensitive(), $formats)
            );

            $result[] = $item;
        }

        \usort(
            $result,
            static fn (array $a, array $b): int => $a['Qualified name'] <=> $b['Qualified name']
        );

        return $result;
    }
}
