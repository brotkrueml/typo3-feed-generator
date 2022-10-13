<?php

declare(strict_types=1);

use Brotkrueml\FeedGenerator\Report\FeedGeneratorStatus;
use TYPO3\CMS\Core\Information\Typo3Version;

defined('TYPO3') or die();

if ((new Typo3Version())->getMajorVersion() < 12) {
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['reports']['tx_reports']['status']['providers']['Feed Generator'][] =
        FeedGeneratorStatus::class;
}
