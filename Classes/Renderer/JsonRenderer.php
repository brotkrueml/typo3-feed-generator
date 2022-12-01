<?php

declare(strict_types=1);

/*
 * This file is part of the "feed_generator" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\FeedGenerator\Renderer;

use Brotkrueml\FeedGenerator\Collection\Collection;
use Brotkrueml\FeedGenerator\Contract\AttachmentInterface;
use Brotkrueml\FeedGenerator\Contract\AuthorInterface;
use Brotkrueml\FeedGenerator\Contract\CategoryInterface;
use Brotkrueml\FeedGenerator\Contract\FeedInterface;
use Brotkrueml\FeedGenerator\Contract\ImageInterface;
use Brotkrueml\FeedGenerator\Renderer\Json\JsonExtensionProcessor;

/**
 * @internal
 */
final class JsonRenderer implements RendererInterface
{
    public function __construct(
        private readonly JsonExtensionProcessor $extensionProcessor,
    ) {
    }

    public function render(FeedInterface $feed, string $feedLink): string
    {
        if ($feed->getTitle() === '') {
            throw MissingRequiredPropertyException::forProperty('title');
        }
        if ($feed->getItems()->isEmpty()) {
            throw MissingRequiredPropertyException::forProperty('items');
        }

        $resultArray = [
            'version' => 'https://jsonfeed.org/version/1.1',
            'title' => $feed->getTitle(),
        ];
        if ($feed->getLink() !== '') {
            $resultArray['home_page_url'] = $feed->getLink();
        }
        $resultArray['feed_url'] = $feedLink;
        if ($feed->getDescription() !== '') {
            $resultArray['description'] = $feed->getDescription();
        }
        if ($feed->getImage() instanceof ImageInterface) {
            $resultArray['icon'] = $feed->getImage()->getUrl();
        }
        $authorsArray = $this->buildAuthorsArray($feed->getAuthors());
        if ($authorsArray !== []) {
            $resultArray['authors'] = $authorsArray;
        }
        if ($feed->getLanguage() !== '') {
            $resultArray['language'] = $feed->getLanguage();
        }

        $resultArray = [...$resultArray, ...$this->extensionProcessor->process($feed->getExtensionContents())];

        $resultArray['items'] = [];
        foreach ($feed->getItems() as $item) {
            if ($item->getId() === '') {
                throw MissingRequiredPropertyException::forProperty('item/id');
            }

            $itemArray = [
                'id' => $item->getId(),
            ];

            if ($item->getLink() !== '') {
                $itemArray['url'] = $item->getLink();
            }
            if ($item->getTitle() !== '') {
                $itemArray['title'] = $item->getTitle();
            }
            if ($item->getDescription() !== '') {
                $itemArray['summary'] = $item->getDescription();
            }
            if ($item->getDatePublished() instanceof \DateTimeInterface) {
                $itemArray['date_published'] = $item->getDatePublished()->format('c');
            }
            if ($item->getDateModified() instanceof \DateTimeInterface) {
                $itemArray['date_modified'] = $item->getDateModified()->format('c');
            }
            $authorsArray = $this->buildAuthorsArray($item->getAuthors());
            if ($authorsArray !== []) {
                $itemArray['authors'] = $authorsArray;
            }
            if (! $item->getCategories()->isEmpty()) {
                $itemArray['tags'] = $this->buildTagsArray($item->getCategories());
            }
            if (! $item->getAttachments()->isEmpty()) {
                $itemArray['attachments'] = $this->buildAttachmentsArray($item->getAttachments());
            }

            $itemArray = [...$itemArray, ...$this->extensionProcessor->process($item->getExtensionContents())];

            $resultArray['items'][] = $itemArray;
        }

        return \json_encode($resultArray, \JSON_PRETTY_PRINT | \JSON_UNESCAPED_SLASHES | \JSON_THROW_ON_ERROR);
    }

    /**
     * @param Collection<AuthorInterface> $authors
     * @return list<array{name?: string, url?: string}>
     */
    private function buildAuthorsArray(Collection $authors): array
    {
        $authorsArray = [];
        foreach ($authors as $author) {
            $authorArray = [];
            if ($author->getName() !== '') {
                $authorArray['name'] = $author->getName();
            }
            if ($author->getUri() !== '') {
                $authorArray['uri'] = $author->getUri();
            }
            $authorsArray[] = $authorArray;
        }

        return $authorsArray;
    }

    /**
     * @param Collection<CategoryInterface> $tags
     * @return string[]
     */
    private function buildTagsArray(Collection $tags): array
    {
        $tagsArray = [];
        foreach ($tags as $tag) {
            $tagsArray[] = $tag->getTerm();
        }

        return $tagsArray;
    }

    /**
     * @param Collection<AttachmentInterface> $attachments
     * @return list<array{url: string, mime_type?: string, size_in_bytes?: int}>
     */
    private function buildAttachmentsArray(Collection $attachments): array
    {
        $attachmentsArray = [];
        foreach ($attachments as $attachment) {
            if ($attachment->getUrl() === '') {
                throw MissingRequiredPropertyException::forProperty('item/attachment/url');
            }
            if ($attachment->getType() === '') {
                throw MissingRequiredPropertyException::forProperty('item/attachment/type');
            }

            $attachmentArray = [
                'url' => $attachment->getUrl(),
            ];
            /** @noRector \Rector\DeadCode\Rector\If_\RemoveAlwaysTrueIfConditionRector */
            if ($attachment->getType() !== '') {
                $attachmentArray['type'] = $attachment->getType();
            }
            if ($attachment->getLength() !== 0) {
                $attachmentArray['size_in_bytes'] = $attachment->getLength();
            }

            $attachmentsArray[] = $attachmentArray;
        }

        return $attachmentsArray;
    }
}
