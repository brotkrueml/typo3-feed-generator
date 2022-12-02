<?php

declare(strict_types=1);

/*
 * This file is part of the "feed_generator" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\FeedGenerator\Collection;

/**
 * @implements \IteratorAggregate<string, string>
 * @internal
 */
final class XmlNamespaceCollection implements \IteratorAggregate
{
    /**
     * Key: qualified name, value: namespace
     * @var array<string, string>
     */
    private array $namespaces = [];

    public function add(string $qualifiedName, string $namespace): void
    {
        $this->namespaces[$qualifiedName] = $namespace;
    }

    /**
     * @return \Traversable<string, string>
     * @noRector \Rector\TypeDeclaration\Rector\FunctionLike\ReturnTypeDeclarationRector
     */
    public function getIterator(): \Traversable
    {
        return new \ArrayIterator($this->namespaces);
    }
}
