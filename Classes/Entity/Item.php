<?php

declare(strict_types=1);

/*
 * This file is part of the "feed_generator" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\FeedGenerator\Entity;

use Brotkrueml\FeedGenerator\Collection\Collection;
use Brotkrueml\FeedGenerator\Contract\AttachmentInterface;
use Brotkrueml\FeedGenerator\Contract\AuthorInterface;
use Brotkrueml\FeedGenerator\Contract\CategoryInterface;
use Brotkrueml\FeedGenerator\Contract\ExtensionContentInterface;
use Brotkrueml\FeedGenerator\Contract\ItemInterface;
use Brotkrueml\FeedGenerator\Contract\TextInterface;

/**
 * @api
 */
final class Item implements ItemInterface
{
    private string $id = '';
    private string $title = '';
    private string|TextInterface $description = '';
    private string $content = '';
    private string $link = '';
    /**
     * @var Collection<AuthorInterface>
     */
    private readonly Collection $authors;
    private ?\DateTimeInterface $datePublished = null;
    private ?\DateTimeInterface $dateModified = null;
    /**
     * @var Collection<AttachmentInterface>
     */
    private readonly Collection $attachments;
    /**
     * @var Collection<CategoryInterface>
     */
    private readonly Collection $categories;
    /**
     * @var Collection<ExtensionContentInterface>
     */
    private readonly Collection $extensionContents;

    public function __construct()
    {
        $this->attachments = new Collection();
        $this->authors = new Collection();
        $this->categories = new Collection();
        $this->extensionContents = new Collection();
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): string|TextInterface
    {
        return $this->description;
    }

    public function setDescription(string|TextInterface $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getLink(): string
    {
        return $this->link;
    }

    public function setLink(string $link): self
    {
        $this->link = $link;

        return $this;
    }

    /**
     * @return Collection<AuthorInterface>
     */
    public function getAuthors(): Collection
    {
        return $this->authors;
    }

    public function addAuthors(AuthorInterface ...$authors): self
    {
        $this->authors->add(...$authors);

        return $this;
    }

    public function getDatePublished(): ?\DateTimeInterface
    {
        return $this->datePublished;
    }

    public function setDatePublished(?\DateTimeInterface $datePublished): self
    {
        $this->datePublished = $datePublished;

        return $this;
    }

    public function getDateModified(): ?\DateTimeInterface
    {
        return $this->dateModified;
    }

    public function setDateModified(?\DateTimeInterface $dateModified): self
    {
        $this->dateModified = $dateModified;

        return $this;
    }

    /**
     * @return Collection<AttachmentInterface>
     */
    public function getAttachments(): Collection
    {
        return $this->attachments;
    }

    public function addAttachments(AttachmentInterface ...$attachments): self
    {
        $this->attachments->add(...$attachments);

        return $this;
    }

    /**
     * @return Collection<CategoryInterface>
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategories(CategoryInterface ...$categories): self
    {
        $this->categories->add(...$categories);

        return $this;
    }

    public function addExtensionContents(ExtensionContentInterface ...$contents): self
    {
        $this->extensionContents->add(...$contents);

        return $this;
    }

    /**
     * @return Collection<ExtensionContentInterface>
     */
    public function getExtensionContents(): Collection
    {
        return $this->extensionContents;
    }
}
