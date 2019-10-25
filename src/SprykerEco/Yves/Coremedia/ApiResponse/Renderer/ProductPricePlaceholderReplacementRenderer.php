<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Coremedia\ApiResponse\Renderer;

use Generated\Shared\Transfer\CoremediaPlaceholderTransfer;
use Generated\Shared\Transfer\CurrentProductPriceTransfer;
use SprykerEco\Yves\Coremedia\Dependency\Client\CoremediaToPriceProductClientInterface;
use SprykerEco\Yves\Coremedia\Dependency\Client\CoremediaToPriceProductStorageClientInterface;
use SprykerEco\Yves\Coremedia\Dependency\Client\CoremediaToProductStorageClientInterface;
use SprykerEco\Yves\Coremedia\Formatter\ProductPriceFormatterInterface;

class ProductPricePlaceholderReplacementRenderer implements PlaceholderReplacementRendererInterface
{
    protected const PLACEHOLDER_OBJECT_TYPE = 'product';
    protected const PLACEHOLDER_RENDER_TYPE = 'price';

    protected const PRODUCT_DATA_KEY_ID_PRODUCT_CONCRETE = 'id_product_concrete';
    protected const PRODUCT_DATA_KEY_ID_PRODUCT_ABSTRACT = 'id_product_abstract';

    protected const PRODUCT_ABSTRACT_MAPPING_TYPE = 'sku';

    /**
     * @var \SprykerEco\Yves\Coremedia\Dependency\Client\CoremediaToProductStorageClientInterface
     */
    protected $productStorageClient;

    /**
     * @var \SprykerEco\Yves\Coremedia\Dependency\Client\CoremediaToPriceProductStorageClientInterface
     */
    protected $priceProductStorageClient;

    /**
     * @var \SprykerEco\Yves\Coremedia\Dependency\Client\CoremediaToPriceProductClientInterface
     */
    protected $priceProductClient;

    /**
     * @var \SprykerEco\Yves\Coremedia\Formatter\ProductPriceFormatterInterface
     */
    protected $productPriceFormatter;

    /**
     * @param \SprykerEco\Yves\Coremedia\Dependency\Client\CoremediaToProductStorageClientInterface $productStorageClient
     * @param \SprykerEco\Yves\Coremedia\Dependency\Client\CoremediaToPriceProductStorageClientInterface $priceProductStorageClient
     * @param \SprykerEco\Yves\Coremedia\Dependency\Client\CoremediaToPriceProductClientInterface $priceProductClient
     * @param \SprykerEco\Yves\Coremedia\Formatter\ProductPriceFormatterInterface $productPriceFormatter
     */
    public function __construct(
        CoremediaToProductStorageClientInterface $productStorageClient,
        CoremediaToPriceProductStorageClientInterface $priceProductStorageClient,
        CoremediaToPriceProductClientInterface $priceProductClient,
        ProductPriceFormatterInterface $productPriceFormatter
    ) {
        $this->productStorageClient = $productStorageClient;
        $this->priceProductStorageClient = $priceProductStorageClient;
        $this->priceProductClient = $priceProductClient;
        $this->productPriceFormatter = $productPriceFormatter;
    }

    /**
     * @param \Generated\Shared\Transfer\CoremediaPlaceholderTransfer $coreMediaPlaceholderTransfer
     *
     * @return bool
     */
    public function isApplicable(CoremediaPlaceholderTransfer $coreMediaPlaceholderTransfer): bool
    {
        return $coreMediaPlaceholderTransfer->getObjectType() === static::PLACEHOLDER_OBJECT_TYPE &&
            $coreMediaPlaceholderTransfer->getRenderType() === static::PLACEHOLDER_RENDER_TYPE;
    }

    /**
     * @param \Generated\Shared\Transfer\CoremediaPlaceholderTransfer $coreMediaPlaceholderTransfer
     * @param string $locale
     *
     * @return string|null
     */
    public function getPlaceholderReplacement(
        CoremediaPlaceholderTransfer $coreMediaPlaceholderTransfer,
        string $locale
    ): ?string {
        if (!$coreMediaPlaceholderTransfer->getProductId()) {
            return null;
        }

        $currentProductPriceTransfer = $this->findAbstractProductPrice(
            $coreMediaPlaceholderTransfer,
            $locale
        );

        if ($currentProductPriceTransfer) {
            return $this->productPriceFormatter->getFormattedProductPrice($currentProductPriceTransfer);
        }

        $currentProductPriceTransfer = $this->findConcreteProductPrice(
            $coreMediaPlaceholderTransfer,
            $locale
        );

        if ($currentProductPriceTransfer) {
            return $this->productPriceFormatter->getFormattedProductPrice($currentProductPriceTransfer);
        }

        return null;
    }

    /**
     * @return string|null
     */
    public function getFallbackPlaceholderReplacement(): ?string
    {
        return '';
    }

    /**
     * @param \Generated\Shared\Transfer\CoremediaPlaceholderTransfer $coreMediaPlaceholderTransfer
     * @param string $locale
     *
     * @return \Generated\Shared\Transfer\CurrentProductPriceTransfer|null
     */
    protected function findAbstractProductPrice(
        CoremediaPlaceholderTransfer $coreMediaPlaceholderTransfer,
        string $locale
    ): ?CurrentProductPriceTransfer {
        $abstractProductData = $this->productStorageClient->findProductAbstractStorageDataByMapping(
            static::PRODUCT_ABSTRACT_MAPPING_TYPE,
            $coreMediaPlaceholderTransfer->getProductId(),
            $locale
        );

        if (!isset($abstractProductData[static::PRODUCT_DATA_KEY_ID_PRODUCT_ABSTRACT])) {
            return null;
        }

        $priceProductTransfers = $this->priceProductStorageClient
            ->getPriceProductAbstractTransfers($abstractProductData[static::PRODUCT_DATA_KEY_ID_PRODUCT_ABSTRACT]);

        return $this->resolveProductPriceTransfer($priceProductTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\CoremediaPlaceholderTransfer $coreMediaPlaceholderTransfer
     * @param string $locale
     *
     * @return \Generated\Shared\Transfer\CurrentProductPriceTransfer|null
     */
    protected function findConcreteProductPrice(
        CoremediaPlaceholderTransfer $coreMediaPlaceholderTransfer,
        string $locale
    ): ?CurrentProductPriceTransfer {
        $concreteProductData = $this->productStorageClient->findProductConcreteStorageDataByMapping(
            static::PRODUCT_ABSTRACT_MAPPING_TYPE,
            $coreMediaPlaceholderTransfer->getProductId(),
            $locale
        );

        if (!$this->validateConcreteProductData($concreteProductData)) {
            return null;
        }

        $priceProductTransfers = $this->priceProductStorageClient
            ->getResolvedPriceProductConcreteTransfers(
                $concreteProductData[static::PRODUCT_DATA_KEY_ID_PRODUCT_CONCRETE],
                $concreteProductData[static::PRODUCT_DATA_KEY_ID_PRODUCT_ABSTRACT]
            );

        return $this->resolveProductPriceTransfer(
            $priceProductTransfers
        );
    }

    /**
     * @param array|null $concreteProductData
     *
     * @return bool
     */
    protected function validateConcreteProductData(?array $concreteProductData): bool
    {
        return $concreteProductData
            && isset($concreteProductData[static::PRODUCT_DATA_KEY_ID_PRODUCT_CONCRETE], $concreteProductData[static::PRODUCT_DATA_KEY_ID_PRODUCT_ABSTRACT]);
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer[] $priceProductTransfers
     *
     * @return \Generated\Shared\Transfer\CurrentProductPriceTransfer|null
     */
    protected function resolveProductPriceTransfer(array $priceProductTransfers): ?CurrentProductPriceTransfer
    {
        $currentProductPriceTransfer = $this->priceProductClient->resolveProductPriceTransfer($priceProductTransfers);

        if (!$currentProductPriceTransfer->getCurrency() || !$currentProductPriceTransfer->getPrice()) {
            return null;
        }

        return $currentProductPriceTransfer;
    }
}
