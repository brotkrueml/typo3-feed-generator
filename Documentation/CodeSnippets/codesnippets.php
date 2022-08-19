<?php

declare(strict_types=1);

/*
 * This file is part of the "feed_generator" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

$classes = [
    \Brotkrueml\FeedGenerator\Entity\AuthorInterface::class => 'Interfaces/EntityAuthorInterface.rst',
    \Brotkrueml\FeedGenerator\Entity\CategoryInterface::class => 'Interfaces/EntityCategoryInterface.rst',
    \Brotkrueml\FeedGenerator\Entity\ImageInterface::class => 'Interfaces/EntityImageInterface.rst',
    \Brotkrueml\FeedGenerator\Feed\CategoryAwareInterface::class => 'Interfaces/FeedCategoryAwareInterface.rst',
    \Brotkrueml\FeedGenerator\Feed\FeedFormatAwareInterface::class => 'Interfaces/FeedFeedFormatAwareInterface.rst',
    \Brotkrueml\FeedGenerator\Feed\FeedInterface::class => 'Interfaces/FeedFeedInterface.rst',
    \Brotkrueml\FeedGenerator\Feed\ItemInterface::class => 'Interfaces/FeedItemInterface.rst',
    \Brotkrueml\FeedGenerator\Feed\RequestAwareInterface::class => 'Interfaces/FeedRequestAwareInterface.rst',

    \Brotkrueml\FeedGenerator\Entity\Author::class => 'Classes/EntityAuthor.rst',
    \Brotkrueml\FeedGenerator\Entity\Category::class => 'Classes/EntityCategory.rst',
    \Brotkrueml\FeedGenerator\Entity\Image::class => 'Classes/EntityImage.rst',
    \Brotkrueml\FeedGenerator\Feed\Item::class => 'Classes/FeedItem.rst',

    \Brotkrueml\FeedGenerator\Feed\FeedFormat::class => 'Enums/FeedFeedFormat.rst',
];

$template = <<<TEMPLATE

================================================================================
%s
================================================================================

%s

TEMPLATE;

$result = [];
foreach ($classes as $class => $filePath) {
    $result[] = [
        'action' => 'createPhpClassDocs',
        'class' => $class,
        'targetFileName' => '../API/' . $filePath,
        'template' => $template,
    ];
}

return $result;
