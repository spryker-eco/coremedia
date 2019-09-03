<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Client\CoreMedia\Preparator\PostProcessor;

use Generated\Shared\Transfer\CoreMediaPlaceholderTransfer;
use SprykerEco\Client\CoreMedia\Dependency\Client\CoreMediaToProductStorageClientInterface;

class ProductUrlCoreMediaPlaceholderPostProcessor implements CoreMediaPlaceholderPostProcessorInterface
{
    protected const PLACEHOLDER_OBJECT_TYPE = 'product';
    protected const PLACEHOLDER_RENDER_TYPE = 'url';
    protected const PRODUCT_ABSTRACT_MAPPING_TYPE = 'sku';
    protected const PRODUCT_ABSTRACT_DATA_KEY_URL = 'url';

    /**
     * @var \SprykerEco\Client\CoreMedia\Dependency\Client\CoreMediaToProductStorageClientInterface
     */
    protected $productStorageClient;

    /**
     * @param \SprykerEco\Client\CoreMedia\Dependency\Client\CoreMediaToProductStorageClientInterface $productStorageClient
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
     * @return \Generated\Shared\Transfer\CoreMediaPlaceholderTransfer
     */
    public function addReplacement(
        CoreMediaPlaceholderTransfer $coreMediaPlaceholderTransfer,
        string $locale
    ): CoreMediaPlaceholderTransfer {
        $coreMediaPlaceholderTransfer->requireProductId();

        $abstractProductUrl = $this->getAbstractProductUrlByCoreMediaPlaceholderTransfer(
            $coreMediaPlaceholderTransfer,
            $locale
        );

        if (!$abstractProductUrl) {
            return $coreMediaPlaceholderTransfer;
        }

        $coreMediaPlaceholderTransfer->setPlaceholderReplacement($abstractProductUrl);

        return $coreMediaPlaceholderTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CoreMediaPlaceholderTransfer $coreMediaPlaceholderTransfer
     * @param string $locale
     *
     * @return string|null
     */
    protected function getAbstractProductUrlByCoreMediaPlaceholderTransfer(
        CoreMediaPlaceholderTransfer $coreMediaPlaceholderTransfer,
        string $locale
    ): ?string {
        $abstractProductData = $this->productStorageClient->findProductAbstractStorageDataByMapping(
            static::PRODUCT_ABSTRACT_MAPPING_TYPE,
            $coreMediaPlaceholderTransfer->getProductId(),
            $locale
        );

        return $abstractProductData[static::PRODUCT_ABSTRACT_DATA_KEY_URL] ?? null;
    }
}
