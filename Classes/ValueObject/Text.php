<?php

declare(strict_types=1);

/*
 * This file is part of the "feed_generator" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\FeedGenerator\ValueObject;

use Brotkrueml\FeedGenerator\Contract\TextInterface;
use Brotkrueml\FeedGenerator\Format\TextFormat;

/**
 * @api
 */
final readonly class Text implements TextInterface
{
    public function __construct(
        private string $text,
        private TextFormat $format = TextFormat::TEXT,
    ) {}

    public function getText(): string
    {
        return $this->text;
    }

    public function getFormat(): TextFormat
    {
        return $this->format;
    }
}
