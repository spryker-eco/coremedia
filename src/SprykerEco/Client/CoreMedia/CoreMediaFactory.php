<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Client\CoreMedia;

use Spryker\Client\Kernel\AbstractFactory;
use SprykerEco\Client\CoreMedia\Api\Builder\RequestBuilder;
use SprykerEco\Client\CoreMedia\Api\Builder\RequestBuilderInterface;
use SprykerEco\Client\CoreMedia\Api\Configuration\UrlConfiguration;
use SprykerEco\Client\CoreMedia\Api\Configuration\UrlConfigurationInterface;
use SprykerEco\Client\CoreMedia\Api\CoreMediaApiClient;
use SprykerEco\Client\CoreMedia\Api\CoreMediaApiClientInterface;
use SprykerEco\Client\CoreMedia\Api\Executor\RequestExecutor;
use SprykerEco\Client\CoreMedia\Api\Executor\RequestExecutorInterface;
use SprykerEco\Client\CoreMedia\Dependency\Guzzle\CoreMediaToGuzzleInterface;
use SprykerEco\Client\CoreMedia\Preparator\CoreMediaApiResponsePreparator;
use SprykerEco\Client\CoreMedia\Preparator\CoreMediaApiResponsePreparatorInterface;
use SprykerEco\Client\CoreMedia\Stub\CoreMediaStub;
use SprykerEco\Client\CoreMedia\Stub\CoreMediaStubInterface;

/**
 * @method \SprykerEco\Client\CoreMedia\CoreMediaConfig getConfig()
 */
class CoreMediaFactory extends AbstractFactory
{
    /**
     * @return \SprykerEco\Client\CoreMedia\Api\CoreMediaApiClientInterface
     */
    public function createCoreMediaApiClient(): CoreMediaApiClientInterface
    {
        return new CoreMediaApiClient(
            $this->createCoreMediaApiRequestBuilder(),
            $this->createCoreMediaApiRequestExecutor(),
            $this->createUrlConfiguration()
        );
    }

    /**
     * @return \SprykerEco\Client\CoreMedia\Api\Builder\RequestBuilderInterface
     */
    public function createCoreMediaApiRequestBuilder(): RequestBuilderInterface
    {
        return new RequestBuilder();
    }

    /**
     * @return \SprykerEco\Client\CoreMedia\Api\Executor\RequestExecutorInterface
     */
    public function createCoreMediaApiRequestExecutor(): RequestExecutorInterface
    {
        return new RequestExecutor(
            $this->getGuzzleClient(),
            $this->getConfig()
        );
    }

    /**
     * @return \SprykerEco\Client\CoreMedia\Api\Configuration\UrlConfigurationInterface
     */
    public function createUrlConfiguration(): UrlConfigurationInterface
    {
        return new UrlConfiguration(
            $this->getConfig()
        );
    }

    /**
     * @return \SprykerEco\Client\CoreMedia\Dependency\Guzzle\CoreMediaToGuzzleInterface
     */
    public function getGuzzleClient(): CoreMediaToGuzzleInterface
    {
        return $this->getProvidedDependency(CoreMediaDependencyProvider::CLIENT_GUZZLE);
    }

    /**
     * @return \SprykerEco\Client\CoreMedia\Stub\CoreMediaStubInterface
     */
    public function createCoreMediaStub(): CoreMediaStubInterface
    {
        return new CoreMediaStub(
            $this->createCoreMediaApiClient(),
            $this->createCoreMediaApiResponsePreparator()
        );
    }

    /**
     * @return \SprykerEco\Client\CoreMedia\Preparator\CoreMediaApiResponsePreparatorInterface
     */
    public function createCoreMediaApiResponsePreparator(): CoreMediaApiResponsePreparatorInterface
    {
        return new CoreMediaApiResponsePreparator();
    }
}
