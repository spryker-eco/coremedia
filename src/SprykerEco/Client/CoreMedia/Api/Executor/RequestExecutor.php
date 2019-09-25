<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Client\CoreMedia\Api\Executor;

use Generated\Shared\Transfer\CoreMediaApiResponseTransfer;
use Psr\Http\Message\RequestInterface;
use RuntimeException;
use Spryker\Shared\ErrorHandler\ErrorLogger;
use SprykerEco\Client\CoreMedia\CoreMediaConfig;
use SprykerEco\Client\CoreMedia\Dependency\Guzzle\CoreMediaToGuzzleInterface;

class RequestExecutor implements RequestExecutorInterface
{
    /**
     * @var \SprykerEco\Client\CoreMedia\Dependency\Guzzle\CoreMediaToGuzzleInterface
     */
    protected $httpClient;

    /**
     * @var \SprykerEco\Client\CoreMedia\CoreMediaConfig
     */
    protected $config;

    /**
     * @param \SprykerEco\Client\CoreMedia\Dependency\Guzzle\CoreMediaToGuzzleInterface $httpClient
     * @param \SprykerEco\Client\CoreMedia\CoreMediaConfig $config
     */
    public function __construct(CoreMediaToGuzzleInterface $httpClient, CoreMediaConfig $config)
    {
        $this->httpClient = $httpClient;
        $this->config = $config;
    }

    /**
     * @param \Psr\Http\Message\RequestInterface $request
     *
     * @return \Generated\Shared\Transfer\CoreMediaApiResponseTransfer
     */
    public function execute(RequestInterface $request): CoreMediaApiResponseTransfer
    {
        try {
            $response = $this->httpClient->send($request);
        } catch (RuntimeException $runtimeException) {
            if ($this->config->isDebugModeEnabled()) {
                ErrorLogger::getInstance()->log($runtimeException);
            }

            return (new CoreMediaApiResponseTransfer())
                ->setIsSuccessful(false);
        }

        return (new CoreMediaApiResponseTransfer())
            ->setIsSuccessful(true)
            ->setData($response->getBody()->getContents());
    }
}
