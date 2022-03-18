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
final class Item implements ItemInterface
{
    public function __construct(
        private readonly string $title = '',
        private readonly string $publicId = '',
        private readonly ?\DateTimeInterface $lastModified = null,
        private readonly string $link = '',
        private readonly string $summary = '',
        private readonly string $content = '',
    ) {
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getPublicId(): string
    {
        return $this->publicId;
    }

    public function getLastModified(): ?\DateTimeInterface
    {
        return $this->lastModified;
    }

    public function getLink(): string
    {
        return $this->link;
    }

    public function getSummary(): string
    {
        return $this->summary;
    }

    public function getContent(): string
    {
        return $this->content;
    }
}
