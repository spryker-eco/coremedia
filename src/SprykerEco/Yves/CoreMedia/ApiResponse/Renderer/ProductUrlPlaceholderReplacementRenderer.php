<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\CoreMedia\ApiResponse\Renderer;

use Generated\Shared\Transfer\CoreMediaPlaceholderTransfer;
use SprykerEco\Yves\CoreMedia\Dependency\Client\CoreMediaToProductStorageClientInterface;

class ProductUrlPlaceholderReplacementRenderer implements PlaceholderReplacementRendererInterface
{
    protected const PLACEHOLDER_OBJECT_TYPE = 'product';
    protected const PLACEHOLDER_RENDER_TYPE = 'url';
    protected const PRODUCT_DATA_KEY_URL = 'url';

    protected const PRODUCT_MAPPING_TYPE = 'sku';

    /**
     * @var \SprykerEco\Yves\CoreMedia\Dependency\Client\CoreMediaToProductStorageClientInterface
     */
    protected $productStorageClient;

    /**
     * @param \SprykerEco\Yves\CoreMedia\Dependency\Client\CoreMediaToProductStorageClientInterface $productStorageClient
     */
    public function __construct(CoreMediaToProductStorageClientInterface $productStorageClient)
    {
        $this->productStorageClient = $productStorageClient;
    }

    /**
     * @param \Generated\Shared\Transfer\CoreMediaPlaceholderTransfer $coreMediaPlaceholderTransfer
     *
     * @return bool
     */
    public function isApplicable(CoreMediaPlaceholderTransfer $coreMediaPlaceholderTransfer): bool
    {
        return $coreMediaPlaceholderTransfer->getObjectType() === static::PLACEHOLDER_OBJECT_TYPE &&
            $coreMediaPlaceholderTransfer->getRenderType() === static::PLACEHOLDER_RENDER_TYPE;
    }

    /**
     * @param \Generated\Shared\Transfer\CoreMediaPlaceholderTransfer $coreMediaPlaceholderTransfer
     * @param string $locale
     *
     * @return string|null
     */
    public function getPlaceholderReplacement(
        CoreMediaPlaceholderTransfer $coreMediaPlaceholderTransfer,
        string $locale
    ): ?string {
        if (!$coreMediaPlaceholderTransfer->getProductId()) {
            return null;
        }

        $abstractProductUrl = $this->findAbstractProductUrl(
            $coreMediaPlaceholderTransfer,
            $locale
        );

        if ($abstractProductUrl) {
            return $abstractProductUrl;
        }

        return $this->findConcreteProductUrl(
            $coreMediaPlaceholderTransfer,
            $locale
        );
    }

    /**
     * @return string|null
     */
    public function getFallbackPlaceholderReplacement(): ?string
    {
        return '';
    }

    /**
     * @param \Generated\Shared\Transfer\CoreMediaPlaceholderTransfer $coreMediaPlaceholderTransfer
     * @param string $locale
     *
     * @return string|null
     */
    protected function findAbstractProductUrl(
        CoreMediaPlaceholderTransfer $coreMediaPlaceholderTransfer,
        string $locale
    ): ?string {
        $abstractProductData = $this->productStorageClient->findProductAbstractStorageDataByMapping(
            static::PRODUCT_MAPPING_TYPE,
            $coreMediaPlaceholderTransfer->getProductId(),
            $locale
        );

        return $abstractProductData[static::PRODUCT_DATA_KEY_URL] ?? null;
    }

    /**
     * @param \Generated\Shared\Transfer\CoreMediaPlaceholderTransfer $coreMediaPlaceholderTransfer
     * @param string $locale
     *
     * @return string|null
     */
    protected function findConcreteProductUrl(
        CoreMediaPlaceholderTransfer $coreMediaPlaceholderTransfer,
        string $locale
    ): ?string {
        $concreteProductData = $this->productStorageClient->findProductConcreteStorageDataByMapping(
            static::PRODUCT_MAPPING_TYPE,
            $coreMediaPlaceholderTransfer->getProductId(),
            $locale
        );

        return $concreteProductData[static::PRODUCT_DATA_KEY_URL] ?? null;
    }
}
