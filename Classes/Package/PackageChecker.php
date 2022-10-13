<?php

declare(strict_types=1);

/*
 * This file is part of the "feed_generator" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\FeedGenerator\Package;

use Brotkrueml\FeedGenerator\Format\FeedFormat;
use Composer\InstalledVersions;
use Composer\Semver\VersionParser;

/**
 * @internal
 */
final class PackageChecker implements PackageCheckerInterface
{
    public function isPackageInstalledForFormat(FeedFormat $format): bool
    {
        try {
            return InstalledVersions::satisfies(
                new VersionParser(),
                $format->package()->package,
                $format->package()->constraint
            );
        } catch (\Exception) {
            return false;
        }
    }
}
