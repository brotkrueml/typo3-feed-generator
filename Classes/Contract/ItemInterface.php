<?php

declare(strict_types=1);

/*
 * This file is part of the "feed_generator" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\FeedGenerator\Contract;

use Brotkrueml\FeedGenerator\Collection\Collection;

/**
 * @api
 */
interface ItemInterface extends CollectableInterface
{
    /**
     * Get a unique identifier associated with this item. These are optional so long as a link is added; i.e. if no
     * identifier is provided, the link is used.
     */
    public function getId(): string;

    /**
     * Get the title of the item.
     */
    public function getTitle(): string;

    /**
     * Get the description of the item.
     */
    public function getDescription(): string|TextInterface;

    /**
     * Get the content of the item. In JSON this is the HTML content.
     */
    public function getContent(): string;

    /**
     * Get a URI to the HTML website containing the same or similar information as this item (i.e. if the feed is from
     * a blog, it should provide the blog article's URI where the HTML version of the entry can be read).
     */
    public function getLink(): string;

    /**
     * Get the data for authors. For an RSS feed only one author can be assigned.
     * @return Collection<AuthorInterface>
     */
    public function getAuthors(): Collection;

    /**
     * Get the date on which this item was published. If null, the date used will be the current date and
     * time.
     */
    public function getDatePublished(): ?\DateTimeInterface;

    /**
     * Get the date on which this item was last modified. If null, the date used will be the current date and time.
     * Used in Atom and JSON.
     */
    public function getDateModified(): ?\DateTimeInterface;

    /**
     * Get the attachments (enclosure). In accordance with the RSS Best Practices Profile of the RSS Advisory Board,
     * no support is offered for multiple enclosures since such support forms no part of the RSS specification.
     * JSON feeds support multiple attachments
     * @return Collection<AttachmentInterface>
     */
    public function getAttachments(): Collection;

    /**
     * Get extension elements
     * @return Collection<ExtensionElementInterface>
     */
    public function getExtensionElements(): Collection;
}
