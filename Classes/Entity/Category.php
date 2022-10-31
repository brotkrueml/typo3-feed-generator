<?php

declare(strict_types=1);

/*
 * This file is part of the "feed_generator" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\FeedGenerator\Entity;

use Brotkrueml\FeedGenerator\Contract\CategoryInterface;

/**
 * @api
 */
final class Category implements CategoryInterface
{
    /**
     * @param non-empty-string $term
     */
    public function __construct(
        private readonly string $term,
        private readonly string $scheme = '',
        private readonly string $label = '',
    ) {
    }

    public function getTerm(): string
    {
        return $this->term;
    }

    public function getScheme(): string
    {
        return $this->scheme;
    }

    public function getLabel(): string
    {
        return $this->label;
    }
}
