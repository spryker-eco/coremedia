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
use SprykerEco\Client\CoreMedia\Dependency\Client\CoreMediaToCategoryStorageClientInterface;
use SprykerEco\Client\CoreMedia\Dependency\Client\CoreMediaToProductStorageClientInterface;
use SprykerEco\Client\CoreMedia\Dependency\Guzzle\CoreMediaToGuzzleInterface;
use SprykerEco\Client\CoreMedia\Dependency\Service\CoreMediaToUtilEncodingServiceInterface;
use SprykerEco\Client\CoreMedia\Preparator\CoreMediaApiResponsePreparator;
use SprykerEco\Client\CoreMedia\Preparator\CoreMediaApiResponsePreparatorInterface;
use SprykerEco\Client\CoreMedia\Preparator\Parser\CoreMediaPlaceholderParser;
use SprykerEco\Client\CoreMedia\Preparator\Parser\CoreMediaPlaceholderParserInterface;
use SprykerEco\Client\CoreMedia\Preparator\PostProcessor\CategoryUrlCoreMediaPlaceholderPostProcessor;
use SprykerEco\Client\CoreMedia\Preparator\PostProcessor\CoreMediaPlaceholderPostProcessorInterface;
use SprykerEco\Client\CoreMedia\Preparator\PostProcessor\ProductUrlCoreMediaPlaceholderPostProcessor;
use SprykerEco\Client\CoreMedia\Preparator\Replacer\CoreMediaPlaceholderReplacer;
use SprykerEco\Client\CoreMedia\Preparator\Replacer\CoreMediaPlaceholderReplacerInterface;
use SprykerEco\Client\CoreMedia\Preparator\Resolver\CoreMediaApiResponseResolverInterface;
use SprykerEco\Client\CoreMedia\Preparator\Resolver\CoreMediaPlaceholderResolver;
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
        return new CoreMediaApiResponsePreparator(
            $this->getCoreMediaApiResponseResolvers()
        );
    }

    /**
     * @return \SprykerEco\Client\CoreMedia\Preparator\Resolver\CoreMediaApiResponseResolverInterface[]
     */
    public function getCoreMediaApiResponseResolvers(): array
    {
        return [
            $this->createCoreMediaPlaceholderResolver(),
        ];
    }

    /**
     * @return \SprykerEco\Client\CoreMedia\Preparator\Resolver\CoreMediaApiResponseResolverInterface
     */
    public function createCoreMediaPlaceholderResolver(): CoreMediaApiResponseResolverInterface
    {
        return new CoreMediaPlaceholderResolver(
            $this->createCoreMediaPlaceholderParser(),
            $this->getCoreMediaPlaceholderPostProcessors(),
            $this->createCoreMediaPlaceholderReplacer()
        );
    }

    /**
     * @return \SprykerEco\Client\CoreMedia\Preparator\Parser\CoreMediaPlaceholderParserInterface
     */
    public function createCoreMediaPlaceholderParser(): CoreMediaPlaceholderParserInterface
    {
        return new CoreMediaPlaceholderParser(
            $this->getUtilEncodingService()
        );
    }

    /**
     * @return \SprykerEco\Client\CoreMedia\Preparator\Replacer\CoreMediaPlaceholderReplacerInterface
     */
    public function createCoreMediaPlaceholderReplacer(): CoreMediaPlaceholderReplacerInterface
    {
        return new CoreMediaPlaceholderReplacer();
    }

    /**
     * @return \SprykerEco\Client\CoreMedia\Preparator\PostProcessor\CoreMediaPlaceholderPostProcessorInterface
     */
    public function createProductUrlCoreMediaPlaceholderPostProcessor(): CoreMediaPlaceholderPostProcessorInterface
    {
        return new ProductUrlCoreMediaPlaceholderPostProcessor(
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
     * @return \SprykerEco\Client\CoreMedia\Preparator\PostProcessor\CoreMediaPlaceholderPostProcessorInterface
     */
    public function createCategoryUrlCoreMediaPlaceholderPostProcessor(): CoreMediaPlaceholderPostProcessorInterface
    {
        return new CategoryUrlCoreMediaPlaceholderPostProcessor(
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
     * @return \SprykerEco\Client\CoreMedia\Preparator\PostProcessor\CoreMediaPlaceholderPostProcessorInterface[]
     */
    public function getCoreMediaPlaceholderPostProcessors(): array
    {
        return [
            $this->createProductUrlCoreMediaPlaceholderPostProcessor(),
            $this->createCategoryUrlCoreMediaPlaceholderPostProcessor(),
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
