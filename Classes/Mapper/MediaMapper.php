<?php

declare(strict_types=1);

/*
 * This file is part of the "feed_generator" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\FeedGenerator\Mapper;

use Brotkrueml\FeedGenerator\Feed\MediaInterface;
use FeedIo\Feed\Item\Media;

/**
 * @internal
 */
final class MediaMapper
{
    public function map(MediaInterface $media): \FeedIo\Feed\Item\MediaInterface
    {
        return (new Media())
            ->setType($media->getType())
            ->setUrl($media->getUrl())
            ->setLength((string)$media->getLength())
            ->setTitle($media->getTitle());
    }
}
