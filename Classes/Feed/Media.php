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
final class Media implements MediaInterface
{
    /**
     * @param int<0, max> $length
     */
    public function __construct(
        private readonly string $type,
        private readonly string $url,
        private readonly int $length = 0,
        private readonly string $title = '',
    ) {
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @return int<0, max>
     */
    public function getLength(): int
    {
        return $this->length;
    }

    public function getTitle(): string
    {
        return $this->title;
    }
}
