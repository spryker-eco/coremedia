<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Client\CoreMedia\ApiResponse\PostProcessor;

use Generated\Shared\Transfer\CoreMediaPlaceholderTransfer;
use Generated\Shared\Transfer\MoneyTransfer;
use SprykerEco\Client\CoreMedia\CoreMediaConfig;
use SprykerEco\Client\CoreMedia\Dependency\Client\CoreMediaToMoneyClientInterface;
use SprykerEco\Client\CoreMedia\Dependency\Client\CoreMediaToPriceProductClientInterface;
use SprykerEco\Client\CoreMedia\Reader\PriceProduct\PriceProductAbstractStorageReaderInterface;
use SprykerEco\Client\CoreMedia\Reader\PriceProduct\PriceProductConcreteStorageReaderInterface;
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
     * @var \SprykerEco\Client\CoreMedia\Reader\PriceProduct\PriceProductAbstractStorageReaderInterface
     */
    protected $priceProductAbstractStorageReader;

    /**
     * @var \SprykerEco\Client\CoreMedia\Reader\PriceProduct\PriceProductConcreteStorageReaderInterface
     */
    protected $priceProductConcreteStorageReader;

    /**
     * @var \SprykerEco\Client\CoreMedia\Dependency\Client\CoreMediaToPriceProductClientInterface
     */
    protected $priceProductClient;

    /**
     * @var \SprykerEco\Client\CoreMedia\Dependency\Client\CoreMediaToMoneyClientInterface
     */
    protected $moneyClient;

    /**
     * @param \SprykerEco\Client\CoreMedia\CoreMediaConfig $config
     * @param \SprykerEco\Client\CoreMedia\Reader\Product\ProductAbstractStorageReaderInterface $productAbstractStorageReader
     * @param \SprykerEco\Client\CoreMedia\Reader\Product\ProductConcreteStorageReaderInterface $productConcreteStorageReader
     * @param \SprykerEco\Client\CoreMedia\Reader\PriceProduct\PriceProductAbstractStorageReaderInterface $priceProductAbstractStorageReader
     * @param \SprykerEco\Client\CoreMedia\Reader\PriceProduct\PriceProductConcreteStorageReaderInterface $priceProductConcreteStorageReader
     * @param \SprykerEco\Client\CoreMedia\Dependency\Client\CoreMediaToPriceProductClientInterface $priceProductClient
     * @param \SprykerEco\Client\CoreMedia\Dependency\Client\CoreMediaToMoneyClientInterface $moneyClient
     */
    public function __construct(
        CoreMediaConfig $config,
        ProductAbstractStorageReaderInterface $productAbstractStorageReader,
        ProductConcreteStorageReaderInterface $productConcreteStorageReader,
        PriceProductAbstractStorageReaderInterface $priceProductAbstractStorageReader,
        PriceProductConcreteStorageReaderInterface $priceProductConcreteStorageReader,
        CoreMediaToPriceProductClientInterface $priceProductClient,
        CoreMediaToMoneyClientInterface $moneyClient
    ) {
        parent::__construct($config);

        $this->productAbstractStorageReader = $productAbstractStorageReader;
        $this->productConcreteStorageReader = $productConcreteStorageReader;
        $this->priceProductAbstractStorageReader = $priceProductAbstractStorageReader;
        $this->priceProductConcreteStorageReader = $priceProductConcreteStorageReader;
        $this->priceProductClient = $priceProductClient;
        $this->moneyClient = $moneyClient;
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

        $priceProductTransfers = $this->priceProductConcreteStorageReader
            ->resolvePriceProductConcrete(
                $concreteProductData[static::PRODUCT_DATA_KEY_ID_PRODUCT_CONCRETE],
                $concreteProductData[static::PRODUCT_DATA_KEY_ID_PRODUCT_ABSTRACT]
            );

        return $this->getFormattedProductPrice($priceProductTransfers);
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

        $priceProductTransfers = $this->priceProductAbstractStorageReader
            ->getPriceProductAbstractTransfers($abstractProductData[static::PRODUCT_DATA_KEY_ID_PRODUCT_ABSTRACT]);

        return $this->getFormattedProductPrice($priceProductTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer[] $priceProductTransfers
     *
     * @return string|null
     */
    protected function getFormattedProductPrice(array $priceProductTransfers): ?string
    {
        $currentProductPriceTransfer = $this->priceProductClient->resolveProductPriceTransfer($priceProductTransfers);

        if (!$currentProductPriceTransfer->getCurrency() || !$currentProductPriceTransfer->getPrice()) {
            return null;
        }

        $moneyTransfer = (new MoneyTransfer())
            ->setCurrency($currentProductPriceTransfer->getCurrency())
            ->setAmount((string)$currentProductPriceTransfer->getPrice());

        return $this->moneyClient->formatWithSymbol($moneyTransfer);
    }

    /**
     * @param array|null $concreteProductData
     *
     * @return bool
     */
    protected function validateConcreteProductData(?array $concreteProductData): bool
    {
        return isset($concreteProductData[static::PRODUCT_DATA_KEY_ID_PRODUCT_CONCRETE])
            && isset($concreteProductData[static::PRODUCT_DATA_KEY_ID_PRODUCT_ABSTRACT]);
    }
}
