<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Coremedia;

use Spryker\Yves\Kernel\AbstractFactory;
use SprykerEco\Yves\Coremedia\ApiResponse\ApiResponsePreparator;
use SprykerEco\Yves\Coremedia\ApiResponse\ApiResponsePreparatorInterface;
use SprykerEco\Yves\Coremedia\ApiResponse\Executor\IncorrectPlaceholderDataExecutor;
use SprykerEco\Yves\Coremedia\ApiResponse\Executor\IncorrectPlaceholderDataExecutorInterface;
use SprykerEco\Yves\Coremedia\ApiResponse\Parser\PlaceholderParser;
use SprykerEco\Yves\Coremedia\ApiResponse\Parser\PlaceholderParserInterface;
use SprykerEco\Yves\Coremedia\ApiResponse\PostProcessor\PlaceholderPostProcessor;
use SprykerEco\Yves\Coremedia\ApiResponse\PostProcessor\PlaceholderPostProcessorInterface;
use SprykerEco\Yves\Coremedia\ApiResponse\Renderer\CategoryUrlPlaceholderReplacementRenderer;
use SprykerEco\Yves\Coremedia\ApiResponse\Renderer\CustomPageUrlPlaceholderReplacementRenderer;
use SprykerEco\Yves\Coremedia\ApiResponse\Renderer\PageMetadataPlaceholderReplacementRenderer;
use SprykerEco\Yves\Coremedia\ApiResponse\Renderer\PlaceholderReplacementRendererInterface;
use SprykerEco\Yves\Coremedia\ApiResponse\Renderer\ProductPricePlaceholderReplacementRenderer;
use SprykerEco\Yves\Coremedia\ApiResponse\Renderer\ProductUrlPlaceholderReplacementRenderer;
use SprykerEco\Yves\Coremedia\ApiResponse\Replacer\Metadata\DescriptionMetadataReplacer;
use SprykerEco\Yves\Coremedia\ApiResponse\Replacer\Metadata\KeywordsMetadataReplacer;
use SprykerEco\Yves\Coremedia\ApiResponse\Replacer\Metadata\MetadataReplacerInterface;
use SprykerEco\Yves\Coremedia\ApiResponse\Replacer\Metadata\PageNameMetadataReplacer;
use SprykerEco\Yves\Coremedia\ApiResponse\Replacer\Metadata\TitleMetadataReplacer;
use SprykerEco\Yves\Coremedia\ApiResponse\Replacer\PlaceholderReplacer;
use SprykerEco\Yves\Coremedia\ApiResponse\Replacer\PlaceholderReplacerInterface;
use SprykerEco\Yves\Coremedia\ApiResponse\Resolver\ApiResponseResolverInterface;
use SprykerEco\Yves\Coremedia\ApiResponse\Resolver\PlaceholderResolver;
use SprykerEco\Yves\Coremedia\Dependency\Client\CoremediaToCategoryStorageClientInterface;
use SprykerEco\Yves\Coremedia\Dependency\Client\CoremediaToMoneyClientInterface;
use SprykerEco\Yves\Coremedia\Dependency\Client\CoremediaToPriceProductClientInterface;
use SprykerEco\Yves\Coremedia\Dependency\Client\CoremediaToPriceProductStorageClientInterface;
use SprykerEco\Yves\Coremedia\Dependency\Client\CoremediaToProductStorageClientInterface;
use SprykerEco\Yves\Coremedia\Dependency\Client\CoremediaToStoreClientInterface;
use SprykerEco\Yves\Coremedia\Dependency\Service\CoremediaToUtilEncodingServiceInterface;
use SprykerEco\Yves\Coremedia\Formatter\ProductPriceFormatter;
use SprykerEco\Yves\Coremedia\Formatter\ProductPriceFormatterInterface;
use SprykerEco\Yves\Coremedia\Mapper\ApiContextMapper;
use SprykerEco\Yves\Coremedia\Mapper\ApiContextMapperInterface;
use SprykerEco\Yves\Coremedia\Reader\CmsSlotContent\CmsSlotContentReader;
use SprykerEco\Yves\Coremedia\Reader\CmsSlotContent\CmsSlotContentReaderInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * @method \SprykerEco\Yves\Coremedia\CoremediaConfig getConfig()
 * @method \SprykerEco\Client\Coremedia\CoremediaClientInterface getClient()
 */
class CoremediaFactory extends AbstractFactory
{
    /**
     * @return \SprykerEco\Yves\Coremedia\Reader\CmsSlotContent\CmsSlotContentReaderInterface
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
     * @return \SprykerEco\Yves\Coremedia\Mapper\ApiContextMapperInterface
     */
    public function createApiContextMapper(): ApiContextMapperInterface
    {
        return new ApiContextMapper();
    }

    /**
     * @return \SprykerEco\Yves\Coremedia\ApiResponse\ApiResponsePreparatorInterface
     */
    public function createApiResponsePreparator(): ApiResponsePreparatorInterface
    {
        return new ApiResponsePreparator(
            $this->getApiResponseResolvers()
        );
    }

    /**
     * @return \SprykerEco\Yves\Coremedia\ApiResponse\Resolver\ApiResponseResolverInterface[]
     */
    public function getApiResponseResolvers(): array
    {
        return [
            $this->createPlaceholderResolver(),
        ];
    }

    /**
     * @return \SprykerEco\Yves\Coremedia\ApiResponse\Resolver\ApiResponseResolverInterface
     */
    public function createPlaceholderResolver(): ApiResponseResolverInterface
    {
        return new PlaceholderResolver(
            $this->createPlaceholderParser(),
            $this->createPlaceholderPostProcessor(),
            $this->createPlaceholderReplacer()
        );
    }

    /**
     * @return \SprykerEco\Yves\Coremedia\ApiResponse\Parser\PlaceholderParserInterface
     */
    public function createPlaceholderParser(): PlaceholderParserInterface
    {
        return new PlaceholderParser(
            $this->getUtilEncodingService(),
            $this->getConfig()
        );
    }

    /**
     * @return \SprykerEco\Yves\Coremedia\ApiResponse\Replacer\PlaceholderReplacerInterface
     */
    public function createPlaceholderReplacer(): PlaceholderReplacerInterface
    {
        return new PlaceholderReplacer();
    }

    /**
     * @return \SprykerEco\Yves\Coremedia\ApiResponse\PostProcessor\PlaceholderPostProcessorInterface
     */
    public function createPlaceholderPostProcessor(): PlaceholderPostProcessorInterface
    {
        return new PlaceholderPostProcessor(
            $this->getPlaceholderReplacementRenderers(),
            $this->createIncorrectPlaceholderDataExecutor()
        );
    }

    /**
     * @return \SprykerEco\Yves\Coremedia\ApiResponse\Renderer\PlaceholderReplacementRendererInterface
     */
    public function createProductUrlPlaceholderReplacementRenderer(): PlaceholderReplacementRendererInterface
    {
        return new ProductUrlPlaceholderReplacementRenderer(
            $this->getProductStorageClient()
        );
    }

    /**
     * @return \SprykerEco\Yves\Coremedia\ApiResponse\Renderer\PlaceholderReplacementRendererInterface
     */
    public function createCategoryUrlPlaceholderReplacementRenderer(): PlaceholderReplacementRendererInterface
    {
        return new CategoryUrlPlaceholderReplacementRenderer(
            $this->getCategoryStorageClient(),
            $this->getStoreClient()
        );
    }

    /**
     * @return \SprykerEco\Yves\Coremedia\ApiResponse\Renderer\PlaceholderReplacementRendererInterface
     */
    public function createPageMetadataPlaceholderReplacementRenderer(): PlaceholderReplacementRendererInterface
    {
        return new PageMetadataPlaceholderReplacementRenderer(
            $this->getMetadataReplacers()
        );
    }

    /**
     * @return \SprykerEco\Yves\Coremedia\ApiResponse\Renderer\PlaceholderReplacementRendererInterface
     */
    public function createProductPricePlaceholderReplacementRenderer(): PlaceholderReplacementRendererInterface
    {
        return new ProductPricePlaceholderReplacementRenderer(
            $this->getProductStorageClient(),
            $this->getPriceProductStorageClient(),
            $this->getPriceProductClient(),
            $this->createProductPriceFormatter()
        );
    }

    /**
     * @return \SprykerEco\Yves\Coremedia\ApiResponse\Renderer\PlaceholderReplacementRendererInterface
     */
    public function createCustomPageUrlPlaceholderReplacementRenderer(): PlaceholderReplacementRendererInterface
    {
        return new CustomPageUrlPlaceholderReplacementRenderer(
            $this->getUrlGenerator()
        );
    }

    /**
     * @return \SprykerEco\Yves\Coremedia\ApiResponse\Renderer\PlaceholderReplacementRendererInterface[]
     */
    public function getPlaceholderReplacementRenderers(): array
    {
        return [
            $this->createProductUrlPlaceholderReplacementRenderer(),
            $this->createCategoryUrlPlaceholderReplacementRenderer(),
            $this->createPageMetadataPlaceholderReplacementRenderer(),
            $this->createProductPricePlaceholderReplacementRenderer(),
            $this->createCustomPageUrlPlaceholderReplacementRenderer(),
        ];
    }

    /**
     * @return \SprykerEco\Yves\Coremedia\Formatter\ProductPriceFormatterInterface
     */
    public function createProductPriceFormatter(): ProductPriceFormatterInterface
    {
        return new ProductPriceFormatter(
            $this->getMoneyClient()
        );
    }

    /**
     * @return \SprykerEco\Yves\Coremedia\ApiResponse\Replacer\Metadata\MetadataReplacerInterface[]
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
     * @return \SprykerEco\Yves\Coremedia\ApiResponse\Replacer\Metadata\MetadataReplacerInterface
     */
    public function createTitleMetadataReplacer(): MetadataReplacerInterface
    {
        return new TitleMetadataReplacer($this->getConfig());
    }

    /**
     * @return \SprykerEco\Yves\Coremedia\ApiResponse\Replacer\Metadata\MetadataReplacerInterface
     */
    public function createDescriptionMetadataReplacer(): MetadataReplacerInterface
    {
        return new DescriptionMetadataReplacer($this->getConfig());
    }

    /**
     * @return \SprykerEco\Yves\Coremedia\ApiResponse\Replacer\Metadata\MetadataReplacerInterface
     */
    public function createKeywordsMetadataReplacer(): MetadataReplacerInterface
    {
        return new KeywordsMetadataReplacer($this->getConfig());
    }

    /**
     * @return \SprykerEco\Yves\Coremedia\ApiResponse\Replacer\Metadata\MetadataReplacerInterface
     */
    public function createPageNameMetadataReplacer(): MetadataReplacerInterface
    {
        return new PageNameMetadataReplacer($this->getConfig());
    }

    /**
     * @return \SprykerEco\Yves\Coremedia\ApiResponse\Executor\IncorrectPlaceholderDataExecutorInterface
     */
    public function createIncorrectPlaceholderDataExecutor(): IncorrectPlaceholderDataExecutorInterface
    {
        return new IncorrectPlaceholderDataExecutor($this->getConfig());
    }

    /**
     * @return \SprykerEco\Yves\Coremedia\Dependency\Service\CoremediaToUtilEncodingServiceInterface
     */
    public function getUtilEncodingService(): CoremediaToUtilEncodingServiceInterface
    {
        return $this->getProvidedDependency(CoremediaDependencyProvider::SERVICE_UTIL_ENCODING);
    }

    /**
     * @return \SprykerEco\Yves\Coremedia\Dependency\Client\CoremediaToProductStorageClientInterface
     */
    public function getProductStorageClient(): CoremediaToProductStorageClientInterface
    {
        return $this->getProvidedDependency(CoremediaDependencyProvider::CLIENT_PRODUCT_STORAGE);
    }

    /**
     * @return \SprykerEco\Yves\Coremedia\Dependency\Client\CoremediaToCategoryStorageClientInterface
     */
    public function getCategoryStorageClient(): CoremediaToCategoryStorageClientInterface
    {
        return $this->getProvidedDependency(CoremediaDependencyProvider::CLIENT_CATEGORY_STORAGE);
    }

    /**
     * @return \SprykerEco\Yves\Coremedia\Dependency\Client\CoremediaToPriceProductStorageClientInterface
     */
    public function getPriceProductStorageClient(): CoremediaToPriceProductStorageClientInterface
    {
        return $this->getProvidedDependency(CoremediaDependencyProvider::CLIENT_PRICE_PRODUCT_STORAGE);
    }

    /**
     * @return \SprykerEco\Yves\Coremedia\Dependency\Client\CoremediaToPriceProductClientInterface
     */
    public function getPriceProductClient(): CoremediaToPriceProductClientInterface
    {
        return $this->getProvidedDependency(CoremediaDependencyProvider::CLIENT_PRICE_PRODUCT);
    }

    /**
     * @return \SprykerEco\Yves\Coremedia\Dependency\Client\CoremediaToMoneyClientInterface
     */
    public function getMoneyClient(): CoremediaToMoneyClientInterface
    {
        return $this->getProvidedDependency(CoremediaDependencyProvider::CLIENT_MONEY);
    }

    /**
     * @return \SprykerEco\Yves\Coremedia\Dependency\Client\CoremediaToStoreClientInterface
     */
    public function getStoreClient(): CoremediaToStoreClientInterface
    {
        return $this->getProvidedDependency(CoremediaDependencyProvider::CLIENT_STORE);
    }

    /**
     * @return \Symfony\Component\Routing\Generator\UrlGeneratorInterface
     */
    public function getUrlGenerator(): UrlGeneratorInterface
    {
        return $this->getProvidedDependency(CoremediaDependencyProvider::URL_GENERATOR);
    }
}
