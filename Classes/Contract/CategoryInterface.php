<?php

declare(strict_types=1);

/*
 * This file is part of the "feed_generator" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\FeedGenerator\Contract;

/**
 * Interface for a category. Used in Atom and RSS feeds.
 */
interface CategoryInterface extends CollectableInterface
{
    /**
     * Get the term. The term identifies the category.
     */
    public function getTerm(): string;

    /**
     * Get the scheme. The scheme identifies the categorisation scheme via a URI.
     */
    public function getScheme(): string;

    /**
     * Get the label. The label provides a human-readable label for display. Used in Atom only.
     */
    public function getLabel(): string;
}
