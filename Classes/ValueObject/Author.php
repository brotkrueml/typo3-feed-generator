<?php

declare(strict_types=1);

/*
 * This file is part of the "feed_generator" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\FeedGenerator\ValueObject;

use Brotkrueml\FeedGenerator\Contract\AuthorInterface;

/**
 * @api
 */
final readonly class Author implements AuthorInterface
{
    public function __construct(
        private string $name,
        private string $email = '',
        private string $uri = '',
    ) {}

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getUri(): string
    {
        return $this->uri;
    }
}
