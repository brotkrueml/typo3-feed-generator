<?php

declare(strict_types=1);

/*
 * This file is part of the "feed_generator" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\FeedGenerator\Format;

use Brotkrueml\FeedGenerator\Renderer\AtomRenderer;
use Brotkrueml\FeedGenerator\Renderer\JsonRenderer;
use Brotkrueml\FeedGenerator\Renderer\RendererInterface;
use Brotkrueml\FeedGenerator\Renderer\RssRenderer;

/**
 * @api
 */
enum FeedFormat
{
    case ATOM;
    case JSON;
    case RSS;

    /**
     * @internal
     */
    public function caseSensitive(): string
    {
        return match ($this) {
            FeedFormat::ATOM => 'Atom',
            FeedFormat::JSON => 'JSON',
            FeedFormat::RSS => 'RSS',
        };
    }

    /**
     * @internal
     */
    public function contentType(bool $isStyleSheetAttached): string
    {
        if ($isStyleSheetAttached) {
            return 'application/xml';
        }

        return match ($this) {
            FeedFormat::ATOM => 'application/atom+xml',
            FeedFormat::JSON => 'application/feed+json',
            FeedFormat::RSS => 'application/rss+xml',
        };
    }

    /**
     * @internal
     * @return class-string<RendererInterface>
     */
    public function renderer(): string
    {
        return match ($this) {
            FeedFormat::ATOM => AtomRenderer::class,
            FeedFormat::JSON => JsonRenderer::class,
            FeedFormat::RSS => RssRenderer::class,
        };
    }
}
