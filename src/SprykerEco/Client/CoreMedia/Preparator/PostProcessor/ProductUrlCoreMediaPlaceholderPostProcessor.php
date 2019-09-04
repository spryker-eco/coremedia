<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Client\CoreMedia\Preparator\PostProcessor;

use Generated\Shared\Transfer\CoreMediaPlaceholderTransfer;
use SprykerEco\Client\CoreMedia\Reader\ProductAbstractStorageReaderInterface;
use SprykerEco\Client\CoreMedia\Reader\ProductConcreteStorageReaderInterface;

class ProductUrlCoreMediaPlaceholderPostProcessor implements CoreMediaPlaceholderPostProcessorInterface
{
    protected const PLACEHOLDER_OBJECT_TYPE = 'product';
    protected const PLACEHOLDER_RENDER_TYPE = 'url';
    protected const PRODUCT_ABSTRACT_DATA_KEY_URL = 'url';
    protected const PRODUCT_CONCRETE_DATA_KEY_URL = 'url';

    /**
     * @var \SprykerEco\Client\CoreMedia\Reader\ProductAbstractStorageReaderInterface
     */
    protected $productAbstractStorageReader;

    /**
     * @var \SprykerEco\Client\CoreMedia\Reader\ProductConcreteStorageReaderInterface
     */
    protected $productConcreteStorageReader;

    /**
     * @param \SprykerEco\Client\CoreMedia\Reader\ProductAbstractStorageReaderInterface $productAbstractStorageReader
     * @param \SprykerEco\Client\CoreMedia\Reader\ProductConcreteStorageReaderInterface $productConcreteStorageReader
     */
    public function __construct(
        ProductAbstractStorageReaderInterface $productAbstractStorageReader,
        ProductConcreteStorageReaderInterface $productConcreteStorageReader
    ) {
        $this->productAbstractStorageReader = $productAbstractStorageReader;
        $this->productConcreteStorageReader = $productConcreteStorageReader;
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
        $abstractProductUrl = $this->getProductUrlByCoreMediaPlaceholderTransfer(
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
    protected function getProductUrlByCoreMediaPlaceholderTransfer(
        CoreMediaPlaceholderTransfer $coreMediaPlaceholderTransfer,
        string $locale
    ): ?string {
        $abstractProductUrl = $this->getAbstractProductUrl(
            $coreMediaPlaceholderTransfer,
            $locale
        );

        if ($abstractProductUrl) {
            return $abstractProductUrl;
        }

        return $this->getConcreteProductUrl(
            $coreMediaPlaceholderTransfer,
            $locale
        );
    }

    /**
     * @param \Generated\Shared\Transfer\CoreMediaPlaceholderTransfer $coreMediaPlaceholderTransfer
     * @param string $locale
     *
     * @return string|null
     */
    protected function getAbstractProductUrl(
        CoreMediaPlaceholderTransfer $coreMediaPlaceholderTransfer,
        string $locale
    ): ?string {
        $abstractProductData = $this->productAbstractStorageReader->getProductAbstractData(
            $coreMediaPlaceholderTransfer->getProductId(),
            $locale
        );

        return $abstractProductData[static::PRODUCT_ABSTRACT_DATA_KEY_URL] ?? null;
    }

    /**
     * @param \Generated\Shared\Transfer\CoreMediaPlaceholderTransfer $coreMediaPlaceholderTransfer
     * @param string $locale
     *
     * @return string|null
     */
    protected function getConcreteProductUrl(
        CoreMediaPlaceholderTransfer $coreMediaPlaceholderTransfer,
        string $locale
    ): ?string {
        $concreteProductData = $this->productConcreteStorageReader->getProductConcreteData(
            $coreMediaPlaceholderTransfer->getProductId(),
            $locale
        );

        return $concreteProductData[static::PRODUCT_CONCRETE_DATA_KEY_URL] ?? null;
    }
}
