<?php

declare(strict_types=1);

/*
 * This file is part of the "feed_generator" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\FeedGenerator\Mapper\LaminasFeed;

use Brotkrueml\FeedGenerator\Contract\CategoryInterface;

/**
 * @internal
 */
final class CategoryMapper
{
    /**
     * @return array{term: non-empty-string, scheme?: string, label?: string}
     */
    public function map(CategoryInterface $category): array
    {
        $categoryArray = [
            'term' => $category->getTerm(),
        ];

        if ($category->getScheme() !== '') {
            $categoryArray['scheme'] = $category->getScheme();
        }
        if ($category->getLabel() !== '') {
            $categoryArray['label'] = $category->getLabel();
        }

        return $categoryArray;
    }
}
