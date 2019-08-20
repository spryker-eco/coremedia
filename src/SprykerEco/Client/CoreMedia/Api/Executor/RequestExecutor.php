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
    protected $httpClient;

    /**
     * @param \SprykerEco\Client\CoreMedia\Dependency\Guzzle\CoreMediaToGuzzleInterface $httpClient
     */
    public function __construct(CoreMediaToGuzzleInterface $httpClient)
    {
        $this->httpClient = $httpClient;
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
            $response = $this->httpClient->send($request);
        } catch (RuntimeException $runtimeException) {
            return (new CoreMediaApiResponseTransfer())
                ->setStatus(false)
                ->setMessage($runtimeException->getMessage());
        }

        return (new CoreMediaApiResponseTransfer())
            ->setStatus(true)
            ->setData($response->getBody()->getContents());
    }
}
