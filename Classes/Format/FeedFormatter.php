<?php

declare(strict_types=1);

/*
 * This file is part of the "feed_generator" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\FeedGenerator\Format;

use Brotkrueml\FeedGenerator\Contract\FeedInterface;
use Brotkrueml\FeedGenerator\Mapper\JsonFeed\FeedMapper as JsonFeedMapper;
use Brotkrueml\FeedGenerator\Mapper\LaminasFeed\FeedMapper as LaminasFeedMapper;
use Brotkrueml\FeedGenerator\Package\PackageChecker;
use Brotkrueml\FeedGenerator\Package\PackageNotInstalledException;
use JDecool\JsonFeed\Writer\RendererFactory;

/**
 * @internal
 */
final class FeedFormatter
{
    public function __construct(
        private readonly PackageChecker $packageChecker,
        private readonly JsonFeedMapper $jsonFeedMapper,
        private readonly LaminasFeedMapper $laminasFeedMapper,
    ) {
    }

    public function format(string $feedLink, FeedInterface $feed, FeedFormat $format): string
    {
        if (! $this->packageChecker->isPackageInstalledForFormat($format)) {
            throw PackageNotInstalledException::fromFormat($format);
        }

        if ($format === FeedFormat::JSON) {
            return (new RendererFactory())
                ->createRenderer()
                ->render($this->jsonFeedMapper->map($feedLink, $feed));
        }

        return $this->laminasFeedMapper
            ->map($feedLink, $feed, $format)
            ->export($format->format());
    }
}
