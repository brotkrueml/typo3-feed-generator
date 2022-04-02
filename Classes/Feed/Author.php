<?php

declare(strict_types=1);

/*
 * This file is part of the "feed_generator" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\FeedGenerator\Feed;

/**
 * @api
 */
final class Author implements AuthorInterface
{
    public function __construct(
        private readonly string $name,
        private readonly string $uri = '',
        private readonly string $email = '',
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getUri(): string
    {
        return $this->uri;
    }

    public function getEmail(): string
    {
        return $this->email;
    }
}
