<?php

declare(strict_types=1);

/*
 * This file is part of the "feed_generator" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\FeedGenerator\ValueObject;

use Brotkrueml\FeedGenerator\Contract\ImageInterface;

/**
 * @api
 */
final class Image implements ImageInterface
{
    /**
     * @param int<0, 144> $width
     * @param int<0, 400> $height
     */
    public function __construct(
        private readonly string $url,
        private readonly string $title = '',
        private readonly string $link = '',
        private readonly int $width = 0,
        private readonly int $height = 0,
        private readonly string $description = '',
    ) {
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getLink(): string
    {
        return $this->link;
    }

    public function getWidth(): int
    {
        return $this->width;
    }

    public function getHeight(): int
    {
        return $this->height;
    }

    public function getDescription(): string
    {
        return $this->description;
    }
}
