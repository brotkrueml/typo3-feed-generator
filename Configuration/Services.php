<?php

declare(strict_types=1);

/*
 * This file is part of the "feed_generator" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\FeedGenerator;

use Brotkrueml\FeedGenerator\Contract\ExtensionInterface;
use Brotkrueml\FeedGenerator\Contract\FeedInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $configurator, ContainerBuilder $builder) {
    $builder->registerForAutoconfiguration(ExtensionInterface::class)->addTag('tx_feed_generator.extension');
    $builder->registerForAutoconfiguration(FeedInterface::class)->addTag('tx_feed_generator.feed');
};
