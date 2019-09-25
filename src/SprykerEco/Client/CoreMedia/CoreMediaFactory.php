<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Client\CoreMedia;

use Spryker\Client\Kernel\AbstractFactory;
use SprykerEco\Client\CoreMedia\Api\ApiClient;
use SprykerEco\Client\CoreMedia\Api\ApiClientInterface;
use SprykerEco\Client\CoreMedia\Api\Builder\RequestBuilder;
use SprykerEco\Client\CoreMedia\Api\Builder\RequestBuilderInterface;
use SprykerEco\Client\CoreMedia\Api\Builder\UrlBuilder;
use SprykerEco\Client\CoreMedia\Api\Builder\UrlBuilderInterface;
use SprykerEco\Client\CoreMedia\Api\Configuration\UrlConfiguration;
use SprykerEco\Client\CoreMedia\Api\Configuration\UrlConfigurationInterface;
use SprykerEco\Client\CoreMedia\Api\Executor\RequestExecutor;
use SprykerEco\Client\CoreMedia\Api\Executor\RequestExecutorInterface;
use SprykerEco\Client\CoreMedia\Dependency\Guzzle\CoreMediaToGuzzleInterface;

/**
 * @method \SprykerEco\Client\CoreMedia\CoreMediaConfig getConfig()
 */
class CoreMediaFactory extends AbstractFactory
{
    /**
     * @return \SprykerEco\Client\CoreMedia\Api\ApiClientInterface
     */
    public function createApiClient(): ApiClientInterface
    {
        return new ApiClient(
            $this->createApiRequestBuilder(),
            $this->createApiRequestExecutor(),
            $this->createUrlBuilder()
        );
    }

    /**
     * @return \SprykerEco\Client\CoreMedia\Api\Builder\RequestBuilderInterface
     */
    public function createApiRequestBuilder(): RequestBuilderInterface
    {
        return new RequestBuilder();
    }

    /**
     * @return \SprykerEco\Client\CoreMedia\Api\Executor\RequestExecutorInterface
     */
    public function createApiRequestExecutor(): RequestExecutorInterface
    {
        return new RequestExecutor(
            $this->getGuzzleClient(),
            $this->getConfig()
        );
    }

    /**
     * @return \SprykerEco\Client\CoreMedia\Api\Builder\UrlBuilderInterface
     */
    public function createUrlBuilder(): UrlBuilderInterface
    {
        return new UrlBuilder(
            $this->createUrlConfiguration()
        );
    }

    /**
     * @return \SprykerEco\Client\CoreMedia\Api\Configuration\UrlConfigurationInterface
     */
    public function createUrlConfiguration(): UrlConfigurationInterface
    {
        return new UrlConfiguration($this->getConfig());
    }

    /**
     * @return \SprykerEco\Client\CoreMedia\Dependency\Guzzle\CoreMediaToGuzzleInterface
     */
    public function getGuzzleClient(): CoreMediaToGuzzleInterface
    {
        return $this->getProvidedDependency(CoreMediaDependencyProvider::CLIENT_GUZZLE);
    }
}
