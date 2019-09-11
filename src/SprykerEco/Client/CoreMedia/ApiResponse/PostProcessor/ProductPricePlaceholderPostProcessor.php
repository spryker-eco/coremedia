<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Client\CoreMedia\ApiResponse\PostProcessor;

use Generated\Shared\Transfer\CoreMediaPlaceholderTransfer;
use SprykerEco\Client\CoreMedia\CoreMediaConfig;
use SprykerEco\Client\CoreMedia\Formatter\ProductPriceFormatterInterface;
use SprykerEco\Client\CoreMedia\Reader\PriceProduct\PriceProductStorageReaderInterface;
use SprykerEco\Client\CoreMedia\Reader\Product\ProductAbstractStorageReaderInterface;
use SprykerEco\Client\CoreMedia\Reader\Product\ProductConcreteStorageReaderInterface;

class ProductPricePlaceholderPostProcessor extends AbstractPlaceholderPostProcessor
{
    protected const PLACEHOLDER_OBJECT_TYPE = 'product';
    protected const PLACEHOLDER_RENDER_TYPE = 'price';

    protected const PRODUCT_DATA_KEY_ID_PRODUCT_CONCRETE = 'id_product_concrete';
    protected const PRODUCT_DATA_KEY_ID_PRODUCT_ABSTRACT = 'id_product_abstract';

    /**
     * @var \SprykerEco\Client\CoreMedia\Reader\Product\ProductAbstractStorageReaderInterface
     */
    protected $productAbstractStorageReader;

    /**
     * @var \SprykerEco\Client\CoreMedia\Reader\Product\ProductConcreteStorageReaderInterface
     */
    protected $productConcreteStorageReader;

    /**
     * @var \SprykerEco\Client\CoreMedia\Reader\PriceProduct\PriceProductStorageReaderInterface
     */
    protected $priceProductStorageReader;

    /**
     * @var \SprykerEco\Client\CoreMedia\Formatter\ProductPriceFormatterInterface
     */
    protected $productPriceFormatter;

    /**
     * @param \SprykerEco\Client\CoreMedia\CoreMediaConfig $config
     * @param \SprykerEco\Client\CoreMedia\Reader\Product\ProductAbstractStorageReaderInterface $productAbstractStorageReader
     * @param \SprykerEco\Client\CoreMedia\Reader\Product\ProductConcreteStorageReaderInterface $productConcreteStorageReader
     * @param \SprykerEco\Client\CoreMedia\Reader\PriceProduct\PriceProductStorageReaderInterface $priceProductStorageReader
     * @param \SprykerEco\Client\CoreMedia\Formatter\ProductPriceFormatterInterface $productPriceFormatter
     */
    public function __construct(
        CoreMediaConfig $config,
        ProductAbstractStorageReaderInterface $productAbstractStorageReader,
        ProductConcreteStorageReaderInterface $productConcreteStorageReader,
        PriceProductStorageReaderInterface $priceProductStorageReader,
        ProductPriceFormatterInterface $productPriceFormatter
    ) {
        parent::__construct($config);

        $this->productAbstractStorageReader = $productAbstractStorageReader;
        $this->productConcreteStorageReader = $productConcreteStorageReader;
        $this->priceProductStorageReader = $priceProductStorageReader;
        $this->productPriceFormatter = $productPriceFormatter;
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
        return $this->findProductPrice($coreMediaPlaceholderTransfer, $locale);
    }

    /**
     * @param \Generated\Shared\Transfer\CoreMediaPlaceholderTransfer $coreMediaPlaceholderTransfer
     * @param string $locale
     *
     * @return string|null
     */
    protected function findProductPrice(
        CoreMediaPlaceholderTransfer $coreMediaPlaceholderTransfer,
        string $locale
    ): ?string {
        if (!$coreMediaPlaceholderTransfer->getProductId()) {
            return null;
        }

        $abstractProductPrice = $this->findAbstractProductPrice(
            $coreMediaPlaceholderTransfer,
            $locale
        );

        if ($abstractProductPrice) {
            return $abstractProductPrice;
        }

        return $this->findConcreteProductPrice(
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
    protected function findAbstractProductPrice(
        CoreMediaPlaceholderTransfer $coreMediaPlaceholderTransfer,
        string $locale
    ): ?string {
        $abstractProductData = $this->productAbstractStorageReader->getProductAbstractData(
            $coreMediaPlaceholderTransfer->getProductId(),
            $locale
        );

        if (!isset($abstractProductData[static::PRODUCT_DATA_KEY_ID_PRODUCT_ABSTRACT])) {
            return null;
        }

        $priceProductTransfers = $this->priceProductStorageReader
            ->getPriceProductAbstractTransfers($abstractProductData[static::PRODUCT_DATA_KEY_ID_PRODUCT_ABSTRACT]);

        return $this->productPriceFormatter->getFormattedProductPrice($priceProductTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\CoreMediaPlaceholderTransfer $coreMediaPlaceholderTransfer
     * @param string $locale
     *
     * @return string|null
     */
    protected function findConcreteProductPrice(
        CoreMediaPlaceholderTransfer $coreMediaPlaceholderTransfer,
        string $locale
    ): ?string {
        $concreteProductData = $this->productConcreteStorageReader->getProductConcreteData(
            $coreMediaPlaceholderTransfer->getProductId(),
            $locale
        );

        if (!$this->validateConcreteProductData($concreteProductData)) {
            return null;
        }

        $priceProductTransfers = $this->priceProductStorageReader
            ->getResolvedPriceProductConcreteTransfers(
                $concreteProductData[static::PRODUCT_DATA_KEY_ID_PRODUCT_CONCRETE],
                $concreteProductData[static::PRODUCT_DATA_KEY_ID_PRODUCT_ABSTRACT]
            );

        return $this->productPriceFormatter->getFormattedProductPrice($priceProductTransfers);
    }

    /**
     * @param array|null $concreteProductData
     *
     * @return bool
     */
    protected function validateConcreteProductData(?array $concreteProductData): bool
    {
        return $concreteProductData
            && isset($concreteProductData[static::PRODUCT_DATA_KEY_ID_PRODUCT_CONCRETE])
            && isset($concreteProductData[static::PRODUCT_DATA_KEY_ID_PRODUCT_ABSTRACT]);
    }
}
