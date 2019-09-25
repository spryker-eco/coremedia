<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Yves\CoreMedia\ApiResponse\Renderer;

use Generated\Shared\Transfer\CoreMediaPlaceholderTransfer;
use Generated\Shared\Transfer\CurrentProductPriceTransfer;
use SprykerEco\Yves\CoreMedia\Dependency\Client\CoreMediaToPriceProductClientInterface;
use SprykerEco\Yves\CoreMedia\Dependency\Client\CoreMediaToPriceProductStorageClientInterface;
use SprykerEco\Yves\CoreMedia\Dependency\Client\CoreMediaToProductStorageClientInterface;
use SprykerEco\Yves\CoreMedia\Formatter\ProductPriceFormatterInterface;

class ProductPricePlaceholderReplacementRenderer implements PlaceholderReplacementRendererInterface
{
    protected const PLACEHOLDER_OBJECT_TYPE = 'product';
    protected const PLACEHOLDER_RENDER_TYPE = 'price';

    protected const PRODUCT_DATA_KEY_ID_PRODUCT_CONCRETE = 'id_product_concrete';
    protected const PRODUCT_DATA_KEY_ID_PRODUCT_ABSTRACT = 'id_product_abstract';

    protected const PRODUCT_ABSTRACT_MAPPING_TYPE = 'sku';

    /**
     * @var \SprykerEco\Yves\CoreMedia\Dependency\Client\CoreMediaToProductStorageClientInterface
     */
    protected $productStorageClient;

    /**
     * @var \SprykerEco\Yves\CoreMedia\Dependency\Client\CoreMediaToPriceProductStorageClientInterface
     */
    protected $priceProductStorageClient;

    /**
     * @var \SprykerEco\Yves\CoreMedia\Dependency\Client\CoreMediaToPriceProductClientInterface
     */
    protected $priceProductClient;

    /**
     * @var \SprykerEco\Yves\CoreMedia\Formatter\ProductPriceFormatterInterface
     */
    protected $productPriceFormatter;

    /**
     * @param \SprykerEco\Yves\CoreMedia\Dependency\Client\CoreMediaToProductStorageClientInterface $productStorageClient
     * @param \SprykerEco\Yves\CoreMedia\Dependency\Client\CoreMediaToPriceProductStorageClientInterface $priceProductStorageClient
     * @param \SprykerEco\Yves\CoreMedia\Dependency\Client\CoreMediaToPriceProductClientInterface $priceProductClient
     * @param \SprykerEco\Yves\CoreMedia\Formatter\ProductPriceFormatterInterface $productPriceFormatter
     */
    public function __construct(
        CoreMediaToProductStorageClientInterface $productStorageClient,
        CoreMediaToPriceProductStorageClientInterface $priceProductStorageClient,
        CoreMediaToPriceProductClientInterface $priceProductClient,
        ProductPriceFormatterInterface $productPriceFormatter
    ) {
        $this->productStorageClient = $productStorageClient;
        $this->priceProductStorageClient = $priceProductStorageClient;
        $this->priceProductClient = $priceProductClient;
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
    public function getPlaceholderReplacement(
        CoreMediaPlaceholderTransfer $coreMediaPlaceholderTransfer,
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
     * @param \Generated\Shared\Transfer\CoreMediaPlaceholderTransfer $coreMediaPlaceholderTransfer
     * @param string $locale
     *
     * @return \Generated\Shared\Transfer\CurrentProductPriceTransfer|null
     */
    protected function findAbstractProductPrice(
        CoreMediaPlaceholderTransfer $coreMediaPlaceholderTransfer,
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
     * @param \Generated\Shared\Transfer\CoreMediaPlaceholderTransfer $coreMediaPlaceholderTransfer
     * @param string $locale
     *
     * @return \Generated\Shared\Transfer\CurrentProductPriceTransfer|null
     */
    protected function findConcreteProductPrice(
        CoreMediaPlaceholderTransfer $coreMediaPlaceholderTransfer,
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
