<?php

declare(strict_types=1);

/*
 * This file is part of the "feed_generator" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\FeedGenerator\Mapper\LaminasFeed;

use Brotkrueml\FeedGenerator\Contract\EnclosureInterface;

/**
 * @internal
 */
final class EnclosureMapper
{
    /**
     * @return array{uri: non-empty-string, type?: string, length?: string}
     */
    public function map(EnclosureInterface $enclosure): array
    {
        $enclosureArray = [
            'uri' => $enclosure->getUri(),
        ];

        if ($enclosure->getType() !== '') {
            $enclosureArray['type'] = $enclosure->getType();
        }
        if ($enclosure->getLength() > 0) {
            $enclosureArray['length'] = (string)$enclosure->getLength();
        }

        return $enclosureArray;
    }
}
