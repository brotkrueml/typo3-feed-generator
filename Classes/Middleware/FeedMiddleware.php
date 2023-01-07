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
use Brotkrueml\FeedGenerator\Configuration\FeedRegistryInterface;
use Brotkrueml\FeedGenerator\Contract\FeedFormatAwareInterface;
use Brotkrueml\FeedGenerator\Contract\RequestAwareInterface;
use Brotkrueml\FeedGenerator\Format\FeedFormat;
use Brotkrueml\FeedGenerator\Renderer\AtomRenderer;
use Brotkrueml\FeedGenerator\Renderer\JsonRenderer;
use Brotkrueml\FeedGenerator\Renderer\RendererInterface;
use Brotkrueml\FeedGenerator\Renderer\RssRenderer;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Symfony\Contracts\Service\ServiceSubscriberInterface;
use TYPO3\CMS\Core\Http\NormalizedParams;
use TYPO3\CMS\Core\Site\Entity\Site;

/**
 * @internal
 */
final class FeedMiddleware implements MiddlewareInterface, ServiceSubscriberInterface
{
    public function __construct(
        private readonly ContainerInterface $renderers,
        private readonly FeedRegistryInterface $feedRegistry,
        private readonly ResponseFactoryInterface $responseFactory,
    ) {
    }

    /**
     * @return list<class-string<RendererInterface>>
     */
    public static function getSubscribedServices(): array
    {
        return [
            AtomRenderer::class,
            JsonRenderer::class,
            RssRenderer::class,
        ];
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

        /** @var NormalizedParams $normalizedParams */
        $normalizedParams = $request->getAttribute('normalizedParams');
        $feedLink = $normalizedParams->getRequestHost() . $configuration->path;
        /** @var RendererInterface $renderer */
        $renderer = $this->renderers->get($configuration->format->renderer());
        $result = $renderer->render($feed, $feedLink);

        $hasStyleSheet = ($configuration->format !== FeedFormat::JSON) && ($feed->getStyleSheet() !== '');
        $response = $this->responseFactory->createResponse()
            ->withHeader('Content-Type', $configuration->format->contentType($hasStyleSheet) . '; charset=utf-8');

        if ($feed->getDateModified() instanceof \DateTimeInterface) {
            $response = $response->withHeader('Last-Modified', $feed->getDateModified()->format(\DateTimeInterface::RFC7231));
        }

        if ($configuration->cacheInSeconds !== null) {
            $response = $response
                ->withHeader('Cache-Control', 'max-age=' . $configuration->cacheInSeconds)
                ->withHeader(
                    'Expires',
                    (new \DateTimeImmutable($configuration->cacheInSeconds . ' seconds'))->format(\DateTimeInterface::RFC7231),
                );
        }

        $response->getBody()->write($result);

        return $response;
    }
}
