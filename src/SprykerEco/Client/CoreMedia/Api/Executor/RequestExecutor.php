<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Client\CoreMedia\Api\Executor;

use Generated\Shared\Transfer\CoreMediaApiResponseTransfer;
use Psr\Http\Message\RequestInterface;
use RuntimeException;
use SprykerEco\Client\CoreMedia\Api\Configuration\UrlConfigurationInterface;
use SprykerEco\Client\CoreMedia\Dependency\Guzzle\CoreMediaToGuzzleInterface;

class RequestExecutor implements RequestExecutorInterface
{
    /**
     * @var \SprykerEco\Client\CoreMedia\Dependency\Guzzle\CoreMediaToGuzzleInterface
     */
    protected $client;

    /**
     * @param \SprykerEco\Client\CoreMedia\Dependency\Guzzle\CoreMediaToGuzzleInterface $client
     */
    public function __construct(CoreMediaToGuzzleInterface $client)
    {
        $this->client = $client;
    }

    /**
     * @param \Psr\Http\Message\RequestInterface $request
     * @param \SprykerEco\Client\CoreMedia\Api\Configuration\UrlConfigurationInterface $urlConfiguration
     *
     * @return \Generated\Shared\Transfer\CoreMediaApiResponseTransfer
     */
    public function execute(
        RequestInterface $request,
        UrlConfigurationInterface $urlConfiguration
    ): CoreMediaApiResponseTransfer {
        try {
            $response = $this->client->send($request);
        } catch (RuntimeException $runtimeException) {
            return $this->createErrorResponseTransfer($runtimeException->getMessage());
        }

        $responseBody = $response->getBody()->getContents();

        return $this->createSuccessResponseTransfer($responseBody);
    }

    /**
     * @param string $responseBody
     *
     * @return \Generated\Shared\Transfer\CoreMediaApiResponseTransfer
     */
    protected function createSuccessResponseTransfer(string $responseBody): CoreMediaApiResponseTransfer
    {
        return (new CoreMediaApiResponseTransfer())
            ->setStatus(true)
            ->setData($responseBody);
    }

    /**
     * @param string $message
     *
     * @return \Generated\Shared\Transfer\CoreMediaApiResponseTransfer
     */
    protected function createErrorResponseTransfer(string $message): CoreMediaApiResponseTransfer
    {
        return (new CoreMediaApiResponseTransfer())
            ->setStatus(false)
            ->setMessage($message);
    }
}
