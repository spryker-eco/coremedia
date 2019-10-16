<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Client\Coremedia\Api\Executor;

use Generated\Shared\Transfer\CoremediaApiResponseTransfer;
use Psr\Http\Message\RequestInterface;
use RuntimeException;
use Spryker\Shared\ErrorHandler\ErrorLogger;
use SprykerEco\Client\Coremedia\CoremediaConfig;
use SprykerEco\Client\Coremedia\Dependency\Guzzle\CoremediaToGuzzleInterface;

class RequestExecutor implements RequestExecutorInterface
{
    /**
     * @var \SprykerEco\Client\Coremedia\Dependency\Guzzle\CoremediaToGuzzleInterface
     */
    protected $httpClient;

    /**
     * @var \SprykerEco\Client\Coremedia\CoremediaConfig
     */
    protected $config;

    /**
     * @param \SprykerEco\Client\Coremedia\Dependency\Guzzle\CoremediaToGuzzleInterface $httpClient
     * @param \SprykerEco\Client\Coremedia\CoremediaConfig $config
     */
    public function __construct(CoremediaToGuzzleInterface $httpClient, CoremediaConfig $config)
    {
        $this->httpClient = $httpClient;
        $this->config = $config;
    }

    /**
     * @param \Psr\Http\Message\RequestInterface $request
     *
     * @return \Generated\Shared\Transfer\CoremediaApiResponseTransfer
     */
    public function execute(RequestInterface $request): CoremediaApiResponseTransfer
    {
        try {
            $response = $this->httpClient->send($request);
        } catch (RuntimeException $runtimeException) {
            if ($this->config->isDebugModeEnabled()) {
                ErrorLogger::getInstance()->log($runtimeException);
            }

            return (new CoremediaApiResponseTransfer())
                ->setIsSuccessful(false);
        }

        return (new CoremediaApiResponseTransfer())
            ->setIsSuccessful(true)
            ->setData($response->getBody()->getContents());
    }
}
