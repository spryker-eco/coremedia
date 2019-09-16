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
use SprykerEco\Client\CoreMedia\ApiResponse\ApiResponse;
use SprykerEco\Client\CoreMedia\ApiResponse\ApiResponseInterface;
use SprykerEco\Client\CoreMedia\ApiResponse\Parser\PlaceholderParser;
use SprykerEco\Client\CoreMedia\ApiResponse\Parser\PlaceholderParserInterface;
use SprykerEco\Client\CoreMedia\ApiResponse\PostProcessor\CategoryUrlPlaceholderPostProcessor;
use SprykerEco\Client\CoreMedia\ApiResponse\PostProcessor\CustomPageUrlPlaceholderPostProcessor;
use SprykerEco\Client\CoreMedia\ApiResponse\PostProcessor\PageMetadataPlaceholderPostProcessor;
use SprykerEco\Client\CoreMedia\ApiResponse\PostProcessor\PlaceholderPostProcessorInterface;
use SprykerEco\Client\CoreMedia\ApiResponse\PostProcessor\ProductPricePlaceholderPostProcessor;
use SprykerEco\Client\CoreMedia\ApiResponse\PostProcessor\ProductUrlPlaceholderPostProcessor;
use SprykerEco\Client\CoreMedia\ApiResponse\Replacer\PlaceholderReplacer;
use SprykerEco\Client\CoreMedia\ApiResponse\Replacer\PlaceholderReplacerInterface;
use SprykerEco\Client\CoreMedia\ApiResponse\Resolver\ApiResponseResolverInterface;
use SprykerEco\Client\CoreMedia\ApiResponse\Resolver\PlaceholderResolver;
use SprykerEco\Client\CoreMedia\Dependency\Client\CoreMediaToCategoryStorageClientInterface;
use SprykerEco\Client\CoreMedia\Dependency\Client\CoreMediaToMoneyClientInterface;
use SprykerEco\Client\CoreMedia\Dependency\Client\CoreMediaToPriceProductClientInterface;
use SprykerEco\Client\CoreMedia\Dependency\Client\CoreMediaToPriceProductStorageClientInterface;
use SprykerEco\Client\CoreMedia\Dependency\Client\CoreMediaToProductStorageClientInterface;
use SprykerEco\Client\CoreMedia\Dependency\Guzzle\CoreMediaToGuzzleInterface;
use SprykerEco\Client\CoreMedia\Dependency\Service\CoreMediaToUtilEncodingServiceInterface;
use SprykerEco\Client\CoreMedia\Formatter\ProductPriceFormatter;
use SprykerEco\Client\CoreMedia\Formatter\ProductPriceFormatterInterface;
use SprykerEco\Client\CoreMedia\Reader\Category\CategoryStorageReader;
use SprykerEco\Client\CoreMedia\Reader\Category\CategoryStorageReaderInterface;
use SprykerEco\Client\CoreMedia\Reader\PriceProduct\PriceProductReader;
use SprykerEco\Client\CoreMedia\Reader\PriceProduct\PriceProductReaderInterface;
use SprykerEco\Client\CoreMedia\Reader\Product\ProductAbstractStorageReader;
use SprykerEco\Client\CoreMedia\Reader\Product\ProductAbstractStorageReaderInterface;
use SprykerEco\Client\CoreMedia\Reader\Product\ProductConcreteStorageReader;
use SprykerEco\Client\CoreMedia\Reader\Product\ProductConcreteStorageReaderInterface;
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
            $this->createApiResponse()
        );
    }

    /**
     * @return \SprykerEco\Client\CoreMedia\ApiResponse\ApiResponseInterface
     */
    public function createApiResponse(): ApiResponseInterface
    {
        return new ApiResponse(
            $this->getApiResponseResolvers()
        );
    }

    /**
     * @return \SprykerEco\Client\CoreMedia\ApiResponse\Resolver\ApiResponseResolverInterface[]
     */
    public function getApiResponseResolvers(): array
    {
        return [
            $this->createPlaceholderResolver(),
        ];
    }

    /**
     * @return \SprykerEco\Client\CoreMedia\ApiResponse\Resolver\ApiResponseResolverInterface
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
     * @return \SprykerEco\Client\CoreMedia\ApiResponse\Parser\PlaceholderParserInterface
     */
    public function createPlaceholderParser(): PlaceholderParserInterface
    {
        return new PlaceholderParser(
            $this->getUtilEncodingService(),
            $this->getConfig()
        );
    }

    /**
     * @return \SprykerEco\Client\CoreMedia\ApiResponse\Replacer\PlaceholderReplacerInterface
     */
    public function createPlaceholderReplacer(): PlaceholderReplacerInterface
    {
        return new PlaceholderReplacer();
    }

    /**
     * @return \SprykerEco\Client\CoreMedia\ApiResponse\PostProcessor\PlaceholderPostProcessorInterface
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
     * @return \SprykerEco\Client\CoreMedia\Reader\Product\ProductAbstractStorageReaderInterface
     */
    public function createProductAbstractStorageReader(): ProductAbstractStorageReaderInterface
    {
        return new ProductAbstractStorageReader(
            $this->getProductStorageClient()
        );
    }

    /**
     * @return \SprykerEco\Client\CoreMedia\Reader\Product\ProductConcreteStorageReaderInterface
     */
    public function createProductConcreteStorageReader(): ProductConcreteStorageReaderInterface
    {
        return new ProductConcreteStorageReader(
            $this->getProductStorageClient()
        );
    }

    /**
     * @return \SprykerEco\Client\CoreMedia\ApiResponse\PostProcessor\PlaceholderPostProcessorInterface
     */
    public function createCategoryUrlPlaceholderPostProcessor(): PlaceholderPostProcessorInterface
    {
        return new CategoryUrlPlaceholderPostProcessor(
            $this->getConfig(),
            $this->createCategoryStorageReader()
        );
    }

    /**
     * @return \SprykerEco\Client\CoreMedia\Reader\Category\CategoryStorageReaderInterface
     */
    public function createCategoryStorageReader(): CategoryStorageReaderInterface
    {
        return new CategoryStorageReader(
            $this->getCategoryStorageClient()
        );
    }

    /**
     * @return \SprykerEco\Client\CoreMedia\ApiResponse\PostProcessor\PlaceholderPostProcessorInterface
     */
    public function createPageMetadataPostProcessor(): PlaceholderPostProcessorInterface
    {
        return new PageMetadataPlaceholderPostProcessor($this->getConfig());
    }

    /**
     * @return \SprykerEco\Client\CoreMedia\ApiResponse\PostProcessor\PlaceholderPostProcessorInterface
     */
    public function createProductPricePlaceholderPostProcessor(): PlaceholderPostProcessorInterface
    {
        return new ProductPricePlaceholderPostProcessor(
            $this->getConfig(),
            $this->createProductAbstractStorageReader(),
            $this->createProductConcreteStorageReader(),
            $this->createPriceProductReader(),
            $this->createProductPriceFormatter()
        );
    }

    /**
     * @return \SprykerEco\Client\CoreMedia\Reader\PriceProduct\PriceProductReaderInterface
     */
    public function createPriceProductReader(): PriceProductReaderInterface
    {
        return new PriceProductReader(
            $this->getPriceProductStorageClient(),
            $this->getPriceProductClient()
        );
    }

    /**
     * @return \SprykerEco\Client\CoreMedia\Formatter\ProductPriceFormatterInterface
     */
    public function createProductPriceFormatter(): ProductPriceFormatterInterface
    {
        return new ProductPriceFormatter(
            $this->getMoneyClient()
        );
    }

    /**
     * @return \SprykerEco\Client\CoreMedia\ApiResponse\PostProcessor\PlaceholderPostProcessorInterface
     */
    public function createCustomPageUrlPlaceholderPostProcessor(): PlaceholderPostProcessorInterface
    {
        return new CustomPageUrlPlaceholderPostProcessor(
            $this->getConfig()
        );
    }

    /**
     * @return \SprykerEco\Client\CoreMedia\ApiResponse\PostProcessor\PlaceholderPostProcessorInterface[]
     */
    public function getPlaceholderPostProcessors(): array
    {
        return [
            $this->createProductUrlPlaceholderPostProcessor(),
            $this->createCategoryUrlPlaceholderPostProcessor(),
            $this->createPageMetadataPostProcessor(),
            $this->createProductPricePlaceholderPostProcessor(),
            $this->createCustomPageUrlPlaceholderPostProcessor(),
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

    /**
     * @return \SprykerEco\Client\CoreMedia\Dependency\Client\CoreMediaToPriceProductStorageClientInterface
     */
    public function getPriceProductStorageClient(): CoreMediaToPriceProductStorageClientInterface
    {
        return $this->getProvidedDependency(CoreMediaDependencyProvider::CLIENT_PRICE_PRODUCT_STORAGE);
    }

    /**
     * @return \SprykerEco\Client\CoreMedia\Dependency\Client\CoreMediaToPriceProductClientInterface
     */
    public function getPriceProductClient(): CoreMediaToPriceProductClientInterface
    {
        return $this->getProvidedDependency(CoreMediaDependencyProvider::CLIENT_PRICE_PRODUCT);
    }

    /**
     * @return \SprykerEco\Client\CoreMedia\Dependency\Client\CoreMediaToMoneyClientInterface
     */
    public function getMoneyClient(): CoreMediaToMoneyClientInterface
    {
        return $this->getProvidedDependency(CoreMediaDependencyProvider::CLIENT_MONEY);
    }
}
