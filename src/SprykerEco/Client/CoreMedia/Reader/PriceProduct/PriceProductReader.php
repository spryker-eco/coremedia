<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Client\CoreMedia\Reader\PriceProduct;

use Generated\Shared\Transfer\CurrentProductPriceTransfer;
use SprykerEco\Client\CoreMedia\Dependency\Client\CoreMediaToPriceProductClientInterface;
use SprykerEco\Client\CoreMedia\Dependency\Client\CoreMediaToPriceProductStorageClientInterface;

class PriceProductReader implements PriceProductReaderInterface
{
    /**
     * @var \SprykerEco\Client\CoreMedia\Dependency\Client\CoreMediaToPriceProductStorageClientInterface
     */
    protected $priceProductStorageClient;

    /**
     * @var \SprykerEco\Client\CoreMedia\Dependency\Client\CoreMediaToPriceProductClientInterface
     */
    protected $priceProductClient;

    /**
     * @var \Generated\Shared\Transfer\CurrentProductPriceTransfer[]
     */
    protected static $priceProductAbstractDataCache = [];

    /**
     * @var \Generated\Shared\Transfer\CurrentProductPriceTransfer[]
     */
    protected static $priceProductConcreteDataCache = [];

    /**
     * @param \SprykerEco\Client\CoreMedia\Dependency\Client\CoreMediaToPriceProductStorageClientInterface $priceProductStorageClient
     * @param \SprykerEco\Client\CoreMedia\Dependency\Client\CoreMediaToPriceProductClientInterface $priceProductClient
     */
    public function __construct(
        CoreMediaToPriceProductStorageClientInterface $priceProductStorageClient,
        CoreMediaToPriceProductClientInterface $priceProductClient
    ) {
        $this->priceProductStorageClient = $priceProductStorageClient;
        $this->priceProductClient = $priceProductClient;
    }

    /**
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\CurrentProductPriceTransfer|null
     */
    public function findCurrentAbstractProductPrice(int $idProductAbstract): ?CurrentProductPriceTransfer
    {
        if (isset(static::$priceProductAbstractDataCache[$idProductAbstract])) {
            return static::$priceProductAbstractDataCache[$idProductAbstract];
        }

        $priceProductTransfers = $this->priceProductStorageClient
            ->getPriceProductAbstractTransfers($idProductAbstract);

        static::$priceProductAbstractDataCache[$idProductAbstract] = $this->resolveProductPriceTransfer(
            $priceProductTransfers
        );

        return static::$priceProductAbstractDataCache[$idProductAbstract];
    }

    /**
     * @param int $idProductConcrete
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\CurrentProductPriceTransfer|null
     */
    public function findCurrentConcreteProductPrice(
        int $idProductConcrete,
        int $idProductAbstract
    ): ?CurrentProductPriceTransfer {
        if (isset(static::$priceProductConcreteDataCache[$idProductConcrete])) {
            return static::$priceProductConcreteDataCache[$idProductConcrete];
        }

        $priceProductTransfers = $this->priceProductStorageClient
            ->getResolvedPriceProductConcreteTransfers($idProductConcrete, $idProductAbstract);

        static::$priceProductConcreteDataCache[$idProductConcrete] = $this->resolveProductPriceTransfer(
            $priceProductTransfers
        );

        return static::$priceProductConcreteDataCache[$idProductConcrete];
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
