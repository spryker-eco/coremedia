<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Client\CoreMedia;

use Spryker\Client\Kernel\AbstractFactory;
use SprykerEco\Client\CoreMedia\Api\ApiClient;
use SprykerEco\Client\CoreMedia\Api\ApiClientInterface;
use SprykerEco\Client\CoreMedia\Api\Builder\RequestBuilder;
use SprykerEco\Client\CoreMedia\Api\Builder\RequestBuilderInterface;
use SprykerEco\Client\CoreMedia\Api\Configuration\UrlConfiguration;
use SprykerEco\Client\CoreMedia\Api\Configuration\UrlConfigurationInterface;
use SprykerEco\Client\CoreMedia\Api\Executor\RequestExecutor;
use SprykerEco\Client\CoreMedia\Api\Executor\RequestExecutorInterface;
use SprykerEco\Client\CoreMedia\Dependency\Client\CoreMediaToCategoryStorageClientInterface;
use SprykerEco\Client\CoreMedia\Dependency\Client\CoreMediaToProductStorageClientInterface;
use SprykerEco\Client\CoreMedia\Dependency\Guzzle\CoreMediaToGuzzleInterface;
use SprykerEco\Client\CoreMedia\Dependency\Service\CoreMediaToUtilEncodingServiceInterface;
use SprykerEco\Client\CoreMedia\Preparator\ApiResponsePreparator;
use SprykerEco\Client\CoreMedia\Preparator\ApiResponsePreparatorInterface;
use SprykerEco\Client\CoreMedia\Preparator\Parser\PlaceholderParser;
use SprykerEco\Client\CoreMedia\Preparator\Parser\PlaceholderParserInterface;
use SprykerEco\Client\CoreMedia\Preparator\PostProcessor\CategoryUrlPlaceholderPostProcessor;
use SprykerEco\Client\CoreMedia\Preparator\PostProcessor\PlaceholderPostProcessorInterface;
use SprykerEco\Client\CoreMedia\Preparator\PostProcessor\ProductUrlPlaceholderPostProcessor;
use SprykerEco\Client\CoreMedia\Preparator\Replacer\PlaceholderReplacer;
use SprykerEco\Client\CoreMedia\Preparator\Replacer\PlaceholderReplacerInterface;
use SprykerEco\Client\CoreMedia\Preparator\Resolver\ApiResponseResolverInterface;
use SprykerEco\Client\CoreMedia\Preparator\Resolver\PlaceholderResolver;
use SprykerEco\Client\CoreMedia\Reader\CategoryStorageReader;
use SprykerEco\Client\CoreMedia\Reader\CategoryStorageReaderInterface;
use SprykerEco\Client\CoreMedia\Reader\ProductAbstractStorageReader;
use SprykerEco\Client\CoreMedia\Reader\ProductAbstractStorageReaderInterface;
use SprykerEco\Client\CoreMedia\Reader\ProductConcreteStorageReader;
use SprykerEco\Client\CoreMedia\Reader\ProductConcreteStorageReaderInterface;
use SprykerEco\Client\CoreMedia\Stub\CoreMediaStub;
use SprykerEco\Client\CoreMedia\Stub\CoreMediaStubInterface;

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
            $this->createUrlConfiguration()
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
     * @return \SprykerEco\Client\CoreMedia\Api\Configuration\UrlConfigurationInterface
     */
    public function createUrlConfiguration(): UrlConfigurationInterface
    {
        return new UrlConfiguration(
            $this->getConfig()
        );
    }

    /**
     * @return \SprykerEco\Client\CoreMedia\Stub\CoreMediaStubInterface
     */
    public function createCoreMediaStub(): CoreMediaStubInterface
    {
        return new CoreMediaStub(
            $this->createApiClient(),
            $this->createApiResponsePreparator()
        );
    }

    /**
     * @return \SprykerEco\Client\CoreMedia\Preparator\ApiResponsePreparatorInterface
     */
    public function createApiResponsePreparator(): ApiResponsePreparatorInterface
    {
        return new ApiResponsePreparator(
            $this->getApiResponseResolvers()
        );
    }

    /**
     * @return \SprykerEco\Client\CoreMedia\Preparator\Resolver\ApiResponseResolverInterface[]
     */
    public function getApiResponseResolvers(): array
    {
        return [
            $this->createPlaceholderResolver(),
        ];
    }

    /**
     * @return \SprykerEco\Client\CoreMedia\Preparator\Resolver\ApiResponseResolverInterface
     */
    public function createPlaceholderResolver(): ApiResponseResolverInterface
    {
        return new PlaceholderResolver(
            $this->createPlaceholderParser(),
            $this->getPlaceholderPostProcessors(),
            $this->createPlaceholderReplacer()
        );
    }

    /**
     * @return \SprykerEco\Client\CoreMedia\Preparator\Parser\PlaceholderParserInterface
     */
    public function createPlaceholderParser(): PlaceholderParserInterface
    {
        return new PlaceholderParser(
            $this->getUtilEncodingService()
        );
    }

    /**
     * @return \SprykerEco\Client\CoreMedia\Preparator\Replacer\PlaceholderReplacerInterface
     */
    public function createPlaceholderReplacer(): PlaceholderReplacerInterface
    {
        return new PlaceholderReplacer();
    }

    /**
     * @return \SprykerEco\Client\CoreMedia\Preparator\PostProcessor\PlaceholderPostProcessorInterface
     */
    public function createProductUrlPlaceholderPostProcessor(): PlaceholderPostProcessorInterface
    {
        return new ProductUrlPlaceholderPostProcessor(
            $this->getConfig(),
            $this->createProductAbstractStorageReader(),
            $this->createProductConcreteStorageReader()
        );
    }

    /**
     * @return \SprykerEco\Client\CoreMedia\Reader\ProductAbstractStorageReaderInterface
     */
    public function createProductAbstractStorageReader(): ProductAbstractStorageReaderInterface
    {
        return new ProductAbstractStorageReader(
            $this->getProductStorageClient()
        );
    }

    /**
     * @return \SprykerEco\Client\CoreMedia\Reader\ProductConcreteStorageReaderInterface
     */
    public function createProductConcreteStorageReader(): ProductConcreteStorageReaderInterface
    {
        return new ProductConcreteStorageReader(
            $this->getProductStorageClient()
        );
    }

    /**
     * @return \SprykerEco\Client\CoreMedia\Preparator\PostProcessor\PlaceholderPostProcessorInterface
     */
    public function createCategoryUrlPlaceholderPostProcessor(): PlaceholderPostProcessorInterface
    {
        return new CategoryUrlPlaceholderPostProcessor(
            $this->getConfig(),
            $this->createCategoryStorageReader()
        );
    }

    /**
     * @return \SprykerEco\Client\CoreMedia\Reader\CategoryStorageReaderInterface
     */
    public function createCategoryStorageReader(): CategoryStorageReaderInterface
    {
        return new CategoryStorageReader(
            $this->getCategoryStorageClient()
        );
    }

    /**
     * @return \SprykerEco\Client\CoreMedia\Preparator\PostProcessor\PlaceholderPostProcessorInterface[]
     */
    public function getPlaceholderPostProcessors(): array
    {
        return [
            $this->createProductUrlPlaceholderPostProcessor(),
            $this->createCategoryUrlPlaceholderPostProcessor(),
        ];
    }

    /**
     * @return \SprykerEco\Client\CoreMedia\Dependency\Guzzle\CoreMediaToGuzzleInterface
     */
    public function getGuzzleClient(): CoreMediaToGuzzleInterface
    {
        return $this->getProvidedDependency(CoreMediaDependencyProvider::CLIENT_GUZZLE);
    }

    /**
     * @return \SprykerEco\Client\CoreMedia\Dependency\Service\CoreMediaToUtilEncodingServiceInterface
     */
    public function getUtilEncodingService(): CoreMediaToUtilEncodingServiceInterface
    {
        return $this->getProvidedDependency(CoreMediaDependencyProvider::SERVICE_UTIL_ENCODING);
    }

    /**
     * @return \SprykerEco\Client\CoreMedia\Dependency\Client\CoreMediaToProductStorageClientInterface
     */
    public function getProductStorageClient(): CoreMediaToProductStorageClientInterface
    {
        return $this->getProvidedDependency(CoreMediaDependencyProvider::CLIENT_PRODUCT_STORAGE);
    }

    /**
     * @return \SprykerEco\Client\CoreMedia\Dependency\Client\CoreMediaToCategoryStorageClientInterface
     */
    public function getCategoryStorageClient(): CoreMediaToCategoryStorageClientInterface
    {
        return $this->getProvidedDependency(CoreMediaDependencyProvider::CLIENT_CATEGORY_STORAGE);
    }
}
