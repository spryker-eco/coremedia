<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\CoreMedia\ApiResponse\PostProcessor;

use Generated\Shared\Transfer\CoreMediaPlaceholderTransfer;
use SprykerEco\Yves\CoreMedia\CoreMediaConfig;
use SprykerEco\Yves\CoreMedia\Reader\Product\ProductAbstractStorageReaderInterface;
use SprykerEco\Yves\CoreMedia\Reader\Product\ProductConcreteStorageReaderInterface;

class ProductUrlPlaceholderPostProcessor extends AbstractPlaceholderPostProcessor
{
    protected const PLACEHOLDER_OBJECT_TYPE = 'product';
    protected const PLACEHOLDER_RENDER_TYPE = 'url';
    protected const PRODUCT_DATA_KEY_URL = 'url';

    /**
     * @var \SprykerEco\Yves\CoreMedia\Reader\Product\ProductAbstractStorageReaderInterface
     */
    protected $productAbstractStorageReader;

    /**
     * @var \SprykerEco\Yves\CoreMedia\Reader\Product\ProductConcreteStorageReaderInterface
     */
    protected $productConcreteStorageReader;

    /**
     * @param \SprykerEco\Yves\CoreMedia\CoreMediaConfig $config
     * @param \SprykerEco\Yves\CoreMedia\Reader\Product\ProductAbstractStorageReaderInterface $productAbstractStorageReader
     * @param \SprykerEco\Yves\CoreMedia\Reader\Product\ProductConcreteStorageReaderInterface $productConcreteStorageReader
     */
    public function __construct(
        CoreMediaConfig $config,
        ProductAbstractStorageReaderInterface $productAbstractStorageReader,
        ProductConcreteStorageReaderInterface $productConcreteStorageReader
    ) {
        parent::__construct($config);

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
     * @return string|null
     */
    protected function getPlaceholderReplacement(
        CoreMediaPlaceholderTransfer $coreMediaPlaceholderTransfer,
        string $locale
    ): ?string {
        return $this->findProductUrl($coreMediaPlaceholderTransfer, $locale);
    }

    /**
     * @param \Generated\Shared\Transfer\CoreMediaPlaceholderTransfer $coreMediaPlaceholderTransfer
     * @param string $locale
     *
     * @return string|null
     */
    protected function findProductUrl(
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
     * @param \Generated\Shared\Transfer\CoreMediaPlaceholderTransfer $coreMediaPlaceholderTransfer
     * @param string $locale
     *
     * @return string|null
     */
    protected function findAbstractProductUrl(
        CoreMediaPlaceholderTransfer $coreMediaPlaceholderTransfer,
        string $locale
    ): ?string {
        $abstractProductData = $this->productAbstractStorageReader->getProductAbstractData(
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
        $concreteProductData = $this->productConcreteStorageReader->getProductConcreteData(
            $coreMediaPlaceholderTransfer->getProductId(),
            $locale
        );

        return $concreteProductData[static::PRODUCT_DATA_KEY_URL] ?? null;
    }
}
