<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Client\Coremedia;

use Spryker\Client\Kernel\AbstractFactory;
use SprykerEco\Client\Coremedia\Api\ApiClient;
use SprykerEco\Client\Coremedia\Api\ApiClientInterface;
use SprykerEco\Client\Coremedia\Api\Builder\RequestBuilder;
use SprykerEco\Client\Coremedia\Api\Builder\RequestBuilderInterface;
use SprykerEco\Client\Coremedia\Api\Builder\UrlBuilder;
use SprykerEco\Client\Coremedia\Api\Builder\UrlBuilderInterface;
use SprykerEco\Client\Coremedia\Api\Configuration\UrlConfiguration;
use SprykerEco\Client\Coremedia\Api\Configuration\UrlConfigurationInterface;
use SprykerEco\Client\Coremedia\Api\Executor\RequestExecutor;
use SprykerEco\Client\Coremedia\Api\Executor\RequestExecutorInterface;
use SprykerEco\Client\Coremedia\Dependency\Guzzle\CoremediaToGuzzleInterface;

/**
 * @method \SprykerEco\Client\Coremedia\CoremediaConfig getConfig()
 */
class CoremediaFactory extends AbstractFactory
{
    /**
     * @return \SprykerEco\Client\Coremedia\Api\ApiClientInterface
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
     * @return \SprykerEco\Client\Coremedia\Api\Builder\RequestBuilderInterface
     */
    public function createApiRequestBuilder(): RequestBuilderInterface
    {
        return new RequestBuilder();
    }

    /**
     * @return \SprykerEco\Client\Coremedia\Api\Executor\RequestExecutorInterface
     */
    public function createApiRequestExecutor(): RequestExecutorInterface
    {
        return new RequestExecutor(
            $this->getGuzzleClient(),
            $this->getConfig()
        );
    }

    /**
     * @return \SprykerEco\Client\Coremedia\Api\Builder\UrlBuilderInterface
     */
    public function createUrlBuilder(): UrlBuilderInterface
    {
        return new UrlBuilder(
            $this->createUrlConfiguration()
        );
    }

    /**
     * @return \SprykerEco\Client\Coremedia\Api\Configuration\UrlConfigurationInterface
     */
    public function createUrlConfiguration(): UrlConfigurationInterface
    {
        return new UrlConfiguration($this->getConfig());
    }

    /**
     * @return \SprykerEco\Client\Coremedia\Dependency\Guzzle\CoremediaToGuzzleInterface
     */
    public function getGuzzleClient(): CoremediaToGuzzleInterface
    {
        return $this->getProvidedDependency(CoremediaDependencyProvider::CLIENT_GUZZLE);
    }
}
