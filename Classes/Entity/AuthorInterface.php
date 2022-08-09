<?php

declare(strict_types=1);

/*
 * This file is part of the "feed_generator" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\FeedGenerator\Entity;

interface AuthorInterface
{
    /**
     * Get the name.
     * @return non-empty-string
     */
    public function getName(): string;

    /**
     * Get the email address.
     */
    public function getEmail(): string;

    /**
     * Get the URI. Used in Atom only.
     */
    public function getUri(): string;
}
