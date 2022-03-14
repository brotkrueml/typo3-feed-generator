<?php

declare(strict_types=1);

/*
 * This file is part of the "feed_generator" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\FeedGenerator\Middleware;

use Brotkrueml\FeedGenerator\Configuration\FeedRegistry;
use Brotkrueml\FeedGenerator\Feed\FeedBuilder;
use Brotkrueml\FeedGenerator\Feed\FeedInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * @internal
 */
final class FeedMiddleware implements MiddlewareInterface
{
    public function __construct(
        private readonly FeedBuilder $feedBuilder,
        private readonly FeedRegistry $feedRegistry,
        private readonly ResponseFactoryInterface $responseFactory,
    ) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $siteIdentifier = $request->getAttribute('site')->getIdentifier();
        $path = $request->getRequestTarget();
        $feed = $this->feedRegistry->getFeedBySiteIdentifierAndPath($siteIdentifier, $path);
        if ($feed instanceof FeedInterface) {
            $feedString = $this->feedBuilder->build($feed, $request->getAttribute('normalizedParams')->getRequestUrl());

            $response = $this->responseFactory->createResponse()
                ->withHeader('Content-Type', $feed->getFormat()->contentType() . '; charset=utf-8');
            $response->getBody()->write($feedString);

            return $response;
        }

        return $handler->handle($request);
    }
}
