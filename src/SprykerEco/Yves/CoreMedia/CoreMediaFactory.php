<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\CoreMedia;

use Spryker\Yves\Kernel\AbstractFactory;
use SprykerEco\Yves\CoreMedia\ApiResponse\ApiResponsePreparator;
use SprykerEco\Yves\CoreMedia\ApiResponse\ApiResponsePreparatorInterface;
use SprykerEco\Yves\CoreMedia\ApiResponse\Executor\IncorrectPlaceholderDataExecutor;
use SprykerEco\Yves\CoreMedia\ApiResponse\Executor\IncorrectPlaceholderDataExecutorInterface;
use SprykerEco\Yves\CoreMedia\ApiResponse\Parser\PlaceholderParser;
use SprykerEco\Yves\CoreMedia\ApiResponse\Parser\PlaceholderParserInterface;
use SprykerEco\Yves\CoreMedia\ApiResponse\PostProcessor\PlaceholderPostProcessor;
use SprykerEco\Yves\CoreMedia\ApiResponse\PostProcessor\PlaceholderPostProcessorInterface;
use SprykerEco\Yves\CoreMedia\ApiResponse\Renderer\CategoryUrlPlaceholderReplacementRenderer;
use SprykerEco\Yves\CoreMedia\ApiResponse\Renderer\CustomPageUrlPlaceholderReplacementRenderer;
use SprykerEco\Yves\CoreMedia\ApiResponse\Renderer\PageMetadataPlaceholderReplacementRenderer;
use SprykerEco\Yves\CoreMedia\ApiResponse\Renderer\PlaceholderReplacementRendererInterface;
use SprykerEco\Yves\CoreMedia\ApiResponse\Renderer\ProductPricePlaceholderReplacementRenderer;
use SprykerEco\Yves\CoreMedia\ApiResponse\Renderer\ProductUrlPlaceholderReplacementRenderer;
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
use SprykerEco\Yves\CoreMedia\Reader\CmsSlotContent\CmsSlotContentReader;
use SprykerEco\Yves\CoreMedia\Reader\CmsSlotContent\CmsSlotContentReaderInterface;
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
            $this->createPlaceholderPostProcessor(),
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
    public function createPlaceholderPostProcessor(): PlaceholderPostProcessorInterface
    {
        return new PlaceholderPostProcessor(
            $this->getPlaceholderReplacementRenderers(),
            $this->createIncorrectPlaceholderDataExecutor()
        );
    }

    /**
     * @return \SprykerEco\Yves\CoreMedia\ApiResponse\Renderer\PlaceholderReplacementRendererInterface
     */
    public function createProductUrlPlaceholderReplacementRenderer(): PlaceholderReplacementRendererInterface
    {
        return new ProductUrlPlaceholderReplacementRenderer(
            $this->getProductStorageClient()
        );
    }

    /**
     * @return \SprykerEco\Yves\CoreMedia\ApiResponse\Renderer\PlaceholderReplacementRendererInterface
     */
    public function createCategoryUrlPlaceholderReplacementRenderer(): PlaceholderReplacementRendererInterface
    {
        return new CategoryUrlPlaceholderReplacementRenderer(
            $this->getCategoryStorageClient()
        );
    }

    /**
     * @return \SprykerEco\Yves\CoreMedia\ApiResponse\Renderer\PlaceholderReplacementRendererInterface
     */
    public function createPageMetadataPlaceholderReplacementRenderer(): PlaceholderReplacementRendererInterface
    {
        return new PageMetadataPlaceholderReplacementRenderer(
            $this->getMetadataReplacers()
        );
    }

    /**
     * @return \SprykerEco\Yves\CoreMedia\ApiResponse\Renderer\PlaceholderReplacementRendererInterface
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
     * @return \SprykerEco\Yves\CoreMedia\ApiResponse\Renderer\PlaceholderReplacementRendererInterface
     */
    public function createCustomPageUrlPlaceholderReplacementRenderer(): PlaceholderReplacementRendererInterface
    {
        return new CustomPageUrlPlaceholderReplacementRenderer(
            $this->getUrlGenerator()
        );
    }

    /**
     * @return \SprykerEco\Yves\CoreMedia\ApiResponse\Renderer\PlaceholderReplacementRendererInterface[]
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
     * @return \SprykerEco\Yves\CoreMedia\Formatter\ProductPriceFormatterInterface
     */
    public function createProductPriceFormatter(): ProductPriceFormatterInterface
    {
        return new ProductPriceFormatter(
            $this->getMoneyClient()
        );
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
     * @return \SprykerEco\Yves\CoreMedia\ApiResponse\Executor\IncorrectPlaceholderDataExecutorInterface
     */
    public function createIncorrectPlaceholderDataExecutor(): IncorrectPlaceholderDataExecutorInterface
    {
        return new IncorrectPlaceholderDataExecutor($this->getConfig());
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
