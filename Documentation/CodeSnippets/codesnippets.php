<?php

declare(strict_types=1);

/*
 * This file is part of the "feed_generator" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

$classes = [
    \Brotkrueml\FeedGenerator\Contract\AuthorInterface::class => 'Interfaces/AuthorInterface.rst',
    \Brotkrueml\FeedGenerator\Contract\CategoryInterface::class => 'Interfaces/CategoryInterface.rst',
    \Brotkrueml\FeedGenerator\Contract\ImageInterface::class => 'Interfaces/ImageInterface.rst',
    \Brotkrueml\FeedGenerator\Contract\CategoryAwareInterface::class => 'Interfaces/CategoryAwareInterface.rst',
    \Brotkrueml\FeedGenerator\Contract\FeedFormatAwareInterface::class => 'Interfaces/FeedFormatAwareInterface.rst',
    \Brotkrueml\FeedGenerator\Contract\FeedInterface::class => 'Interfaces/FeedInterface.rst',
    \Brotkrueml\FeedGenerator\Contract\ItemInterface::class => 'Interfaces/ItemInterface.rst',
    \Brotkrueml\FeedGenerator\Contract\RequestAwareInterface::class => 'Interfaces/RequestAwareInterface.rst',
    \Brotkrueml\FeedGenerator\Contract\StyleSheetAwareInterface::class => 'Interfaces/StyleSheetAwareInterface.rst',

    \Brotkrueml\FeedGenerator\Entity\Author::class => 'Classes/Author.rst',
    \Brotkrueml\FeedGenerator\Entity\Category::class => 'Classes/Category.rst',
    \Brotkrueml\FeedGenerator\Entity\Image::class => 'Classes/Image.rst',
    \Brotkrueml\FeedGenerator\Entity\Item::class => 'Classes/Item.rst',

    \Brotkrueml\FeedGenerator\Format\FeedFormat::class => 'Enums/FeedFormat.rst',
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