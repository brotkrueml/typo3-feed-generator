<?php

declare(strict_types=1);

/*
 * This file is part of the "feed_generator" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\FeedGenerator\Renderer\Json;

use Brotkrueml\FeedGenerator\Collection\Collection;
use Brotkrueml\FeedGenerator\Configuration\ExtensionRegistry;
use Brotkrueml\FeedGenerator\Contract\ExtensionContentInterface;
use Brotkrueml\FeedGenerator\Contract\JsonExtensionInterface;

/**
 * @internal
 */
class JsonExtensionProcessor
{
    /**
     * @var array<string, bool>
     */
    private array $isAboutRendered = [];

    public function __construct(
        private readonly ExtensionRegistry $extensionRegistry,
    ) {}

    /**
     * @param Collection<ExtensionContentInterface> $extensionContents
     * @return array<string, array<string, mixed>>
     */
    public function process(Collection $extensionContents): array
    {
        $result = [];
        foreach ($extensionContents as $content) {
            $extension = $this->extensionRegistry->getExtensionForJsonContent($content);
            if (! $extension instanceof JsonExtensionInterface) {
                continue;
            }

            $qualifiedName = (\str_starts_with($extension->getQualifiedName(), '_') ? '' : '_') . $extension->getQualifiedName();
            $content = $extension->getJsonRenderer()->render($content);
            if ($extension->getAbout() !== '' && ! ($this->isAboutRendered[$qualifiedName] ?? false)) {
                $content = [...[
                    'about' => $extension->getAbout(),
                ], ...$content];
                $this->isAboutRendered[$qualifiedName] = true;
            }

            $result = \array_merge(
                $result,
                [
                    $qualifiedName => $content,
                ],
            );
        }

        return $result;
    }
}
