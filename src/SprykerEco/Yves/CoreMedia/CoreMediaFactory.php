<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\CoreMedia;

use Spryker\Yves\Kernel\AbstractFactory;
use SprykerEco\Yves\CoreMedia\ApiResponse\ApiResponsePreparator;
use SprykerEco\Yves\CoreMedia\ApiResponse\ApiResponsePreparatorInterface;
use SprykerEco\Yves\CoreMedia\ApiResponse\Parser\PlaceholderParser;
use SprykerEco\Yves\CoreMedia\ApiResponse\Parser\PlaceholderParserInterface;
use SprykerEco\Yves\CoreMedia\ApiResponse\PostProcessor\CategoryUrlPlaceholderPostProcessor;
use SprykerEco\Yves\CoreMedia\ApiResponse\PostProcessor\CustomPageUrlPlaceholderPostProcessor;
use SprykerEco\Yves\CoreMedia\ApiResponse\PostProcessor\PageMetadataPlaceholderPostProcessor;
use SprykerEco\Yves\CoreMedia\ApiResponse\PostProcessor\PlaceholderPostProcessorInterface;
use SprykerEco\Yves\CoreMedia\ApiResponse\PostProcessor\ProductPricePlaceholderPostProcessor;
use SprykerEco\Yves\CoreMedia\ApiResponse\PostProcessor\ProductUrlPlaceholderPostProcessor;
use SprykerEco\Yves\CoreMedia\ApiResponse\Replacer\Metadata\DescriptionMetadataReplacer;
use SprykerEco\Yves\CoreMedia\ApiResponse\Replacer\Metadata\KeywordsMetadataReplacer;
use SprykerEco\Yves\CoreMedia\ApiResponse\Replacer\Metadata\MetadataReplacerInterface;
use SprykerEco\Yves\CoreMedia\ApiResponse\Replacer\Metadata\PageNameMetadataReplacer;
use SprykerEco\Yves\CoreMedia\ApiResponse\Replacer\Metadata\TitleMetadataReplacer;
use SprykerEco\Yves\CoreMedia\ApiResponse\Replacer\PlaceholderReplacer;
use SprykerEco\Yves\CoreMedia\ApiResponse\Replacer\PlaceholderReplacerInterface;
use SprykerEco\Yves\CoreMedia\ApiResponse\Resolver\ApiResponseResolverInterface;
use SprykerEco\Yves\CoreMedia\ApiResponse\Resolver\PlaceholderResolver;
use SprykerEco\Yves\CoreMedia\Dependency\Client\CoreMediaToCategoryStorageClientInterface;
use SprykerEco\Yves\CoreMedia\Dependency\Client\CoreMediaToMoneyClientInterface;
use SprykerEco\Yves\CoreMedia\Dependency\Client\CoreMediaToPriceProductClientInterface;
use SprykerEco\Yves\CoreMedia\Dependency\Client\CoreMediaToPriceProductStorageClientInterface;
use SprykerEco\Yves\CoreMedia\Dependency\Client\CoreMediaToProductStorageClientInterface;
use SprykerEco\Yves\CoreMedia\Dependency\Service\CoreMediaToUtilEncodingServiceInterface;
use SprykerEco\Yves\CoreMedia\Formatter\ProductPriceFormatter;
use SprykerEco\Yves\CoreMedia\Formatter\ProductPriceFormatterInterface;
use SprykerEco\Yves\CoreMedia\Mapper\ApiContextMapper;
use SprykerEco\Yves\CoreMedia\Mapper\ApiContextMapperInterface;
use SprykerEco\Yves\CoreMedia\Reader\Category\CategoryStorageReader;
use SprykerEco\Yves\CoreMedia\Reader\Category\CategoryStorageReaderInterface;
use SprykerEco\Yves\CoreMedia\Reader\CmsSlotContent\CmsSlotContentReader;
use SprykerEco\Yves\CoreMedia\Reader\CmsSlotContent\CmsSlotContentReaderInterface;
use SprykerEco\Yves\CoreMedia\Reader\PriceProduct\PriceProductReader;
use SprykerEco\Yves\CoreMedia\Reader\PriceProduct\PriceProductReaderInterface;
use SprykerEco\Yves\CoreMedia\Reader\Product\ProductAbstractStorageReader;
use SprykerEco\Yves\CoreMedia\Reader\Product\ProductAbstractStorageReaderInterface;
use SprykerEco\Yves\CoreMedia\Reader\Product\ProductConcreteStorageReader;
use SprykerEco\Yves\CoreMedia\Reader\Product\ProductConcreteStorageReaderInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * @method \SprykerEco\Yves\CoreMedia\CoreMediaConfig getConfig()
 * @method \SprykerEco\Client\CoreMedia\CoreMediaClientInterface getClient()
 */
class CoreMediaFactory extends AbstractFactory
{
    /**
     * @return \SprykerEco\Yves\CoreMedia\Reader\CmsSlotContent\CmsSlotContentReaderInterface
     */
    public function createCmsSlotContentReader(): CmsSlotContentReaderInterface
    {
        return new CmsSlotContentReader(
            $this->getClient(),
            $this->createApiContextMapper(),
            $this->createApiResponsePreparator()
        );
    }

    /**
     * @return \SprykerEco\Yves\CoreMedia\Mapper\ApiContextMapperInterface
     */
    public function createApiContextMapper(): ApiContextMapperInterface
    {
        return new ApiContextMapper();
    }

    /**
     * @return \SprykerEco\Yves\CoreMedia\ApiResponse\ApiResponsePreparatorInterface
     */
    public function createApiResponsePreparator(): ApiResponsePreparatorInterface
    {
        return new ApiResponsePreparator(
            $this->getApiResponseResolvers()
        );
    }

    /**
     * @return \SprykerEco\Yves\CoreMedia\ApiResponse\Resolver\ApiResponseResolverInterface[]
     */
    public function getApiResponseResolvers(): array
    {
        return [
            $this->createPlaceholderResolver(),
        ];
    }

    /**
     * @return \SprykerEco\Yves\CoreMedia\ApiResponse\Resolver\ApiResponseResolverInterface
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
     * @return \SprykerEco\Yves\CoreMedia\ApiResponse\Parser\PlaceholderParserInterface
     */
    public function createPlaceholderParser(): PlaceholderParserInterface
    {
        return new PlaceholderParser(
            $this->getUtilEncodingService(),
            $this->getConfig()
        );
    }

    /**
     * @return \SprykerEco\Yves\CoreMedia\ApiResponse\Replacer\PlaceholderReplacerInterface
     */
    public function createPlaceholderReplacer(): PlaceholderReplacerInterface
    {
        return new PlaceholderReplacer();
    }

    /**
     * @return \SprykerEco\Yves\CoreMedia\ApiResponse\PostProcessor\PlaceholderPostProcessorInterface
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
     * @return \SprykerEco\Yves\CoreMedia\Reader\Product\ProductAbstractStorageReaderInterface
     */
    public function createProductAbstractStorageReader(): ProductAbstractStorageReaderInterface
    {
        return new ProductAbstractStorageReader(
            $this->getProductStorageClient()
        );
    }

    /**
     * @return \SprykerEco\Yves\CoreMedia\Reader\Product\ProductConcreteStorageReaderInterface
     */
    public function createProductConcreteStorageReader(): ProductConcreteStorageReaderInterface
    {
        return new ProductConcreteStorageReader(
            $this->getProductStorageClient()
        );
    }

    /**
     * @return \SprykerEco\Yves\CoreMedia\ApiResponse\PostProcessor\PlaceholderPostProcessorInterface
     */
    public function createCategoryUrlPlaceholderPostProcessor(): PlaceholderPostProcessorInterface
    {
        return new CategoryUrlPlaceholderPostProcessor(
            $this->getConfig(),
            $this->createCategoryStorageReader()
        );
    }

    /**
     * @return \SprykerEco\Yves\CoreMedia\Reader\Category\CategoryStorageReaderInterface
     */
    public function createCategoryStorageReader(): CategoryStorageReaderInterface
    {
        return new CategoryStorageReader(
            $this->getCategoryStorageClient()
        );
    }

    /**
     * @return \SprykerEco\Yves\CoreMedia\ApiResponse\PostProcessor\PlaceholderPostProcessorInterface
     */
    public function createPageMetadataPostProcessor(): PlaceholderPostProcessorInterface
    {
        return new PageMetadataPlaceholderPostProcessor(
            $this->getConfig(),
            $this->getMetadataReplacers()
        );
    }

    /**
     * @return \SprykerEco\Yves\CoreMedia\ApiResponse\PostProcessor\PlaceholderPostProcessorInterface
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
     * @return \SprykerEco\Yves\CoreMedia\Reader\PriceProduct\PriceProductReaderInterface
     */
    public function createPriceProductReader(): PriceProductReaderInterface
    {
        return new PriceProductReader(
            $this->getPriceProductStorageClient(),
            $this->getPriceProductClient()
        );
    }

    /**
     * @return \SprykerEco\Yves\CoreMedia\Formatter\ProductPriceFormatterInterface
     */
    public function createProductPriceFormatter(): ProductPriceFormatterInterface
    {
        return new ProductPriceFormatter(
            $this->getMoneyClient()
        );
    }

    /**
     * @return \SprykerEco\Yves\CoreMedia\ApiResponse\PostProcessor\PlaceholderPostProcessorInterface
     */
    public function createCustomPageUrlPlaceholderPostProcessor(): PlaceholderPostProcessorInterface
    {
        return new CustomPageUrlPlaceholderPostProcessor(
            $this->getConfig(),
            $this->getUrlGenerator()
        );
    }

    /**
     * @return \SprykerEco\Yves\CoreMedia\ApiResponse\PostProcessor\PlaceholderPostProcessorInterface[]
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
     * @return \SprykerEco\Yves\CoreMedia\ApiResponse\Replacer\Metadata\MetadataReplacerInterface[]
     */
    public function getMetadataReplacers(): array
    {
        return [
            $this->createTitleMetadataReplacer(),
            $this->createDescriptionMetadataReplacer(),
            $this->createKeywordsMetadataReplacer(),
            $this->createPageNameMetadataReplacer(),
        ];
    }

    /**
     * @return \SprykerEco\Yves\CoreMedia\ApiResponse\Replacer\Metadata\MetadataReplacerInterface
     */
    public function createTitleMetadataReplacer(): MetadataReplacerInterface
    {
        return new TitleMetadataReplacer($this->getConfig());
    }

    /**
     * @return \SprykerEco\Yves\CoreMedia\ApiResponse\Replacer\Metadata\MetadataReplacerInterface
     */
    public function createDescriptionMetadataReplacer(): MetadataReplacerInterface
    {
        return new DescriptionMetadataReplacer($this->getConfig());
    }

    /**
     * @return \SprykerEco\Yves\CoreMedia\ApiResponse\Replacer\Metadata\MetadataReplacerInterface
     */
    public function createKeywordsMetadataReplacer(): MetadataReplacerInterface
    {
        return new KeywordsMetadataReplacer($this->getConfig());
    }

    /**
     * @return \SprykerEco\Yves\CoreMedia\ApiResponse\Replacer\Metadata\MetadataReplacerInterface
     */
    public function createPageNameMetadataReplacer(): MetadataReplacerInterface
    {
        return new PageNameMetadataReplacer($this->getConfig());
    }

    /**
     * @return \SprykerEco\Yves\CoreMedia\Dependency\Service\CoreMediaToUtilEncodingServiceInterface
     */
    public function getUtilEncodingService(): CoreMediaToUtilEncodingServiceInterface
    {
        return $this->getProvidedDependency(CoreMediaDependencyProvider::SERVICE_UTIL_ENCODING);
    }

    /**
     * @return \SprykerEco\Yves\CoreMedia\Dependency\Client\CoreMediaToProductStorageClientInterface
     */
    public function getProductStorageClient(): CoreMediaToProductStorageClientInterface
    {
        return $this->getProvidedDependency(CoreMediaDependencyProvider::CLIENT_PRODUCT_STORAGE);
    }

    /**
     * @return \SprykerEco\Yves\CoreMedia\Dependency\Client\CoreMediaToCategoryStorageClientInterface
     */
    public function getCategoryStorageClient(): CoreMediaToCategoryStorageClientInterface
    {
        return $this->getProvidedDependency(CoreMediaDependencyProvider::CLIENT_CATEGORY_STORAGE);
    }

    /**
     * @return \SprykerEco\Yves\CoreMedia\Dependency\Client\CoreMediaToPriceProductStorageClientInterface
     */
    public function getPriceProductStorageClient(): CoreMediaToPriceProductStorageClientInterface
    {
        return $this->getProvidedDependency(CoreMediaDependencyProvider::CLIENT_PRICE_PRODUCT_STORAGE);
    }

    /**
     * @return \SprykerEco\Yves\CoreMedia\Dependency\Client\CoreMediaToPriceProductClientInterface
     */
    public function getPriceProductClient(): CoreMediaToPriceProductClientInterface
    {
        return $this->getProvidedDependency(CoreMediaDependencyProvider::CLIENT_PRICE_PRODUCT);
    }

    /**
     * @return \SprykerEco\Yves\CoreMedia\Dependency\Client\CoreMediaToMoneyClientInterface
     */
    public function getMoneyClient(): CoreMediaToMoneyClientInterface
    {
        return $this->getProvidedDependency(CoreMediaDependencyProvider::CLIENT_MONEY);
    }

    /**
     * @return \Symfony\Component\Routing\Generator\UrlGeneratorInterface
     */
    public function getUrlGenerator(): UrlGeneratorInterface
    {
        return $this->getProvidedDependency(CoreMediaDependencyProvider::URL_GENERATOR);
    }
}
