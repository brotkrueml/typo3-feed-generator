<?php

declare(strict_types=1);

/*
 * This file is part of the "feed_generator" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use Brotkrueml\FeedGenerator\Middleware\FeedMiddleware;

return [
    'frontend' => [
        'brotkrueml/feed-generator/feed' => [
            'target' => FeedMiddleware::class,
            'before' => [
                'typo3/cms-frontend/page-resolver',
            ],
        ],
    ],
];
