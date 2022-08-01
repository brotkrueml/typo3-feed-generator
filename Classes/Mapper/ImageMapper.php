<?php

declare(strict_types=1);

/*
 * This file is part of the "feed_generator" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\FeedGenerator\Mapper;

use Brotkrueml\FeedGenerator\Feed\ImageInterface;

/**
 * @internal
 */
final class ImageMapper
{
    /**
     * @return array{uri: non-empty-string, title?: non-empty-string, link?: non-empty-string, width?: numeric-string, height?: numeric-string, description?: non-empty-string}
     */
    public function map(ImageInterface $image): array
    {
        // @phpstan-ignore-next-line Array with keys is not allowed. Use value object to pass data instead
        $imageArray = [
            'uri' => $image->getUri(),
        ];

        if ($image->getTitle() !== '') {
            $imageArray['title'] = $image->getTitle();
        }
        if ($image->getLink() !== '') {
            $imageArray['link'] = $image->getLink();
        }
        if ($image->getWidth() > 0) {
            $imageArray['width'] = (string)$image->getWidth();
        }
        if ($image->getHeight() > 0) {
            $imageArray['height'] = (string)$image->getHeight();
        }
        if ($image->getDescription() !== '') {
            $imageArray['description'] = $image->getDescription();
        }

        return $imageArray;
    }
}
