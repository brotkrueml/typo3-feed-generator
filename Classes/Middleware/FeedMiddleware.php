<?php

declare(strict_types=1);

/*
 * This file is part of the "feed_generator" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\FeedGenerator\Middleware;

use Brotkrueml\FeedGenerator\Configuration\FeedConfiguration;
use Brotkrueml\FeedGenerator\Configuration\FeedRegistry;
use Brotkrueml\FeedGenerator\Feed\FeedFormatAwareInterface;
use Brotkrueml\FeedGenerator\Feed\RequestAwareInterface;
use Brotkrueml\FeedGenerator\Feed\StyleSheetAwareInterface;
use Brotkrueml\FeedGenerator\Formatter\FeedFormatter;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use TYPO3\CMS\Core\Site\Entity\Site;

/**
 * @internal
 */
final class FeedMiddleware implements MiddlewareInterface
{
    public function __construct(
        private readonly FeedFormatter $feedFormatter,
        private readonly FeedRegistry $feedRegistry,
        private readonly ResponseFactoryInterface $responseFactory,
    ) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        /** @var Site $site */
        $site = $request->getAttribute('site');
        $siteIdentifier = $site->getIdentifier();
        $path = $request->getRequestTarget();
        $configuration = $this->feedRegistry->getConfigurationBySiteIdentifierAndPath($siteIdentifier, $path);

        if (! $configuration instanceof FeedConfiguration) {
            return $handler->handle($request);
        }

        $feed = $configuration->instance;
        if ($feed instanceof RequestAwareInterface) {
            $feed->setRequest($request);
        }
        if ($feed instanceof FeedFormatAwareInterface) {
            $feed->setFormat($configuration->format);
        }
        $result = $this->feedFormatter->format($feed, $configuration->format);

        $hasStyleSheet = $feed instanceof StyleSheetAwareInterface && ($feed->getStyleSheet() !== '');
        $response = $this->responseFactory->createResponse()
            ->withHeader('Content-Type', $configuration->format->contentType($hasStyleSheet) . '; charset=utf-8');
        $response->getBody()->write($result);

        return $response;
    }
}
