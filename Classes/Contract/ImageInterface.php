<?php

declare(strict_types=1);

/*
 * This file is part of the "feed_generator" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\FeedGenerator\Contract;

/**
 * Interface for an image. Used in Atom and RSS.
 */
interface ImageInterface
{
    /**
     * Get the URI of the image. Required.
     */
    public function getUri(): string;

    /**
     * Get the title. Used and required for RSS.
     */
    public function getTitle(): string;

    /**
     * Get the link. Used and required for RSS.
     */
    public function getLink(): string;

    /**
     * Get the width of the image. Use for RSS.
     * @return int<0, 144>
     */
    public function getWidth(): int;

    /**
     * Get the height of the image. Use for RSS.
     * @return int<0, 400>
     */
    public function getHeight(): int;

    /**
     * Get the description of the image. Use for RSS.
     */
    public function getDescription(): string;
}
