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

final class PackageNotInstalledException extends \RuntimeException
{
    public static function fromFormat(FeedFormat $format): self
    {
        return new self(
            \sprintf(
                'Composer package "%s" within the version constraint "%s" must be installed to generate a feed in format "%s".',
                $format->package()->package,
                $format->package()->constraint,
                $format->format()
            ),
            1665683416
        );
    }
}
